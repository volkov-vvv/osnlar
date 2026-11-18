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

class SyncMaxBioLidMessages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 5;
    public array $backoff = [15, 60, 180, 300];

    public function __construct(
        public int $bioLidId,
        public int $responsibleId
    ) {}

    public function handle(MaxBotService $maxBot): void
    {
        $responsible = User::find($this->responsibleId);

        if (!$responsible) {
            throw new RuntimeException(
                'MAX: ответственный не найден.'
            );
        }

        MaxSentMessage::query()
            ->where('bio_lid_id', $this->bioLidId)
            ->whereNotNull('message_id')
            ->chunkById(50, function ($messages) use (
                $maxBot,
                $responsible
            ) {
                foreach ($messages as $message) {

                    $forResponsible =
                        (string) $message->user_id ===
                        (string) $this->responsibleId;

                    $newText = SyncMaxLidMessages::formatMessage(
                        (string) $message->text,
                        $forResponsible,
                        (string) $responsible->name
                    );

                    if (mb_strlen($newText) > 4000) {
                        Log::error(
                            'MAX: сообщение БИО слишком длинное',
                            [
                                'message_id' => $message->message_id,
                                'bio_lid_id' => $this->bioLidId,
                            ]
                        );
                        continue;
                    }

                    $maxBot->editMessage(
                        (string) $message->message_id,
                        $newText
                    );

                    $message->update([
                        'text' => $newText,
                    ]);

                    Log::info('MAX: уведомление БИО обновлено', [
                        'bio_lid_id' => $this->bioLidId,
                        'message_id' => $message->message_id,
                        'user_id' => $message->user_id,
                    ]);
                }
            });
    }
}
