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

/**
 * Обновляет сообщения о заявке у всех получателей MAX.
 */
class SyncMaxLidMessages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 5;

    public array $backoff = [15, 60, 180, 300];

    public function __construct(
        public int $lidId,
        public int $responsibleId
    ) {}

    public function handle(MaxBotService $maxBot): void
    {
        $responsible = User::query()
            ->find($this->responsibleId);

        if (!$responsible) {
            throw new RuntimeException(
                'MAX: не найден назначенный ответственный.'
            );
        }

        $marker = "\n<b>Ответственный:</b> ";

        $suffix = $marker . e(
                (string) $responsible->name
            );

        MaxSentMessage::query()
            ->where('lid_id', $this->lidId)
            ->whereNotNull('message_id')
            ->orderBy('id')
            ->chunkById(50, function ($messages) use (
                $maxBot,
                $marker,
                $suffix
            ) {
                foreach ($messages as $message) {

                    // Не добавляем ответственного повторно
                    if (str_contains(
                        (string) $message->text,
                        $marker
                    )) {
                        continue;
                    }

                    $newText = rtrim(
                            (string) $message->text
                        ) . $suffix;

                    // Проверяем длину сообщения
                    if (mb_strlen(strip_tags($newText)) > 4000) {
                        Log::error(
                            'MAX: сообщение слишком длинное',
                            [
                                'message_id' => $message->message_id,
                                'lid_id' => $this->lidId,
                            ]
                        );

                        continue;
                    }

                    // Редактируем сообщение в MAX
                    // и удаляем кнопку принятия заявки
                    $maxBot->editMessage(
                        (string) $message->message_id,
                        $newText
                    );

                    // Сохраняем изменённый текст в БД
                    $message->update([
                        'text' => $newText,
                    ]);

                    Log::info('MAX: сообщение обновлено', [
                        'message_id' => $message->message_id,
                        'lid_id' => $this->lidId,
                        'responsible_id' => $this->responsibleId,
                    ]);
                }
            });
    }
}
