<?php

namespace App\Jobs;

use App\Models\MaxSentMessage;
use App\Models\User;
use App\Service\MaxBotService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class SyncMaxLidMessages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 5;
    public array $backoff = [15, 60, 180, 300];

    public function __construct(
        public int $lidId,
        public int $responsibleId
    ) {}

    public static function formatMessage(
        string $originalText,
        bool $forResponsible,
        string $responsibleName
    ): string {
        $text = rtrim($originalText);

        // Удаляем ранее добавленный служебный статус,
        // чтобы он не дублировался при повторной обработке.
        $patterns = [
            '/(?:\r?\n){1,2}Вы назначены ответственным\.?\z/u',
            '/(?:\r?\n){1,2}<b>Вы назначены ответственным<\/b>\.?\z/u',
            '/(?:\r?\n){1,2}(?:<b>)?Ответственный:(?:<\/b>)?[ \t]*[^\r\n]*\z/u',
        ];

        foreach ($patterns as $pattern) {
            $cleaned = preg_replace($pattern, '', $text);

            if ($cleaned !== null && $cleaned !== $text) {
                $text = rtrim($cleaned);
                break;
            }
        }

        $status = $forResponsible
            ? 'Вы назначены ответственным'
            : 'Ответственный: ' . e($responsibleName);

        return $text . "\n\n" . $status;
    }

    public function handle(MaxBotService $maxBot): void
    {
        $responsible = User::find($this->responsibleId);

        if (!$responsible) {
            throw new RuntimeException(
                'MAX: ответственный не найден.'
            );
        }

        MaxSentMessage::query()
            ->where('lid_id', $this->lidId)
            ->whereNotNull('message_id')
            ->chunkById(50, function ($messages) use (
                $maxBot,
                $responsible
            ) {
                foreach ($messages as $message) {

                    $forResponsible =
                        (string) $message->user_id ===
                        (string) $this->responsibleId;

                    $newText = self::formatMessage(
                        (string) $message->text,
                        $forResponsible,
                        (string) $responsible->name
                    );

                    if (mb_strlen($newText) > 4000) {
                        Log::error(
                            'MAX: сообщение слишком длинное',
                            [
                                'message_id' => $message->message_id,
                                'lid_id' => $this->lidId,
                            ]
                        );
                        continue;
                    }

                    // Редактируем существующее сообщение
                    // и удаляем клавиатуру.
                    $maxBot->editMessage(
                        (string) $message->message_id,
                        $newText
                    );

                    // Сохраняем актуальный текст в журнале.
                    $message->update([
                        'text' => $newText,
                    ]);

                    Log::info('MAX: уведомление обновлено', [
                        'lid_id' => $this->lidId,
                        'message_id' => $message->message_id,
                        'user_id' => $message->user_id,
                    ]);
                }
            });
    }
}
