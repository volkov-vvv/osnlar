<?php

namespace App\Http\Controllers\Lid;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lid\BioeconomyPersonalStoreRequest;
use App\Models\BioLid;
use App\Models\Course;
use App\Models\User;
use App\Service\BioLidAutomationService;
use App\Service\MaxBotService;
use Illuminate\Support\Facades\Log;
use Throwable;

class StoreBioeconomyPersonalController extends Controller
{
    public function __construct(private BioLidAutomationService $automation)
    {
    }

    public function __invoke(BioeconomyPersonalStoreRequest $request, MaxBotService $maxBot)
    {
        $data = $request->safe()->except(['org_id', 'phone_prefix', 'politic']);
        $data['politic'] = true;

        $course = Course::query()->findOrFail($data['course_id']);
        $bioLid = BioLid::create($data);

        $this->automation->processAfterCreate($bioLid, $course);

        $this->notifyMaxSubscribers($maxBot, $bioLid, $course);

        return redirect()->route('lid.index');
    }

    private function notifyMaxSubscribers(MaxBotService $maxBot, BioLid $bioLid, Course $course): void
    {
        $users = User::query()
            ->whereHas('maxSubscription', function ($query) {
                $query->whereNotNull('subscribed_at');
            })
            ->get();

        if ($users->isEmpty()) {
            return;
        }

        $messageData = [
            'id' => $bioLid->id,
            'course_title' => $course->title,
            'lastname' => $bioLid->lastname,
            'firstname' => $bioLid->firstname,
            'middlename' => $bioLid->middlename,
            'phone_display' => $this->formatPhoneForMax($bioLid->phone),
            'email' => $bioLid->email,
        ];

        $message = view('messages.new_biolid', $messageData)->render();

        $buttons = [
            [
                MaxBotService::callbackButton(
                    'Принять заявку',
                    'accept_biolid:' . $bioLid->id
                ),
            ],
        ];

        foreach ($users as $user) {
            try {
                $maxBot->sendNotificationToUser(
                    localUserId: $user->id,
                    text: trim($message),
                    lidId: null,
                    options: ['format' => 'html'],
                    buttons: $buttons,
                    bioLidId: $bioLid->id
                );
            } catch (Throwable $e) {
                Log::error('MAX: не удалось отправить уведомление о заявке БИО', [
                    'bio_lid_id' => $bioLid->id,
                    'user_id' => $user->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    private function formatPhoneForMax(?string $phone): string
    {
        $digits = preg_replace('/\D+/', '', (string) $phone) ?? '';
        if ($digits === '') {
            return '';
        }

        if (strlen($digits) === 10) {
            $digits = '7'.$digits;
        } elseif (strlen($digits) === 11 && str_starts_with($digits, '8')) {
            $digits = '7'.substr($digits, 1);
        }

        return '+'.$digits;
    }
}
