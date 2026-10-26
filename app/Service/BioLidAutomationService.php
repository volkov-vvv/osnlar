<?php

namespace App\Service;

use App\Mail\SendEmail;
use App\Models\BioLid;
use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class BioLidAutomationService
{
    /**
     * @return array{user: ?User, created: bool, password: ?string}
     */
    public function ensureUser(BioLid $biolid): array
    {
        $biolid->loadMissing('user');

        if ($biolid->user) {
            return [
                'user' => $biolid->user,
                'created' => false,
                'password' => null,
            ];
        }

        // На случай «осиротевшей» привязки после удаления пользователя.
        if ($biolid->user_id) {
            $biolid->forceFill(['user_id' => null])->save();
        }

        $email = trim((string) $biolid->email);
        if ($email === '') {
            throw new \InvalidArgumentException('У заявки не указан email');
        }

        $user = User::query()->where('email', $email)->first();
        $created = false;
        $password = null;

        if (! $user) {
            $password = Str::random(8);
            $user = User::create([
                'name' => $biolid->firstname,
                'lastname' => $biolid->lastname,
                'middlename' => $biolid->middlename,
                'email' => $email,
                'phone_prefix' => '7',
                'phone' => $this->normalizePhoneForUser($biolid->phone),
                'role' => User::ROLE_USER,
                'password' => Hash::make($password),
            ]);
            $created = true;
        }

        $biolid->update(['user_id' => $user->id]);
        $biolid->setRelation('user', $user);

        return [
            'user' => $user,
            'created' => $created,
            'password' => $password,
        ];
    }

    public function attachToStudyGroup(User $user, int $studyGroupId): void
    {
        $user->studyGroups()->syncWithoutDetaching([$studyGroupId]);
    }

    public function sendConfirmationEmail(BioLid $biolid): void
    {
        $mailPayload = [
            'firstname' => $biolid->firstname,
            'lastname' => $biolid->lastname,
            'middlename' => $biolid->middlename,
            'email' => $biolid->email,
            'course_id' => $biolid->course_id,
        ];

        $mailData = collect($mailPayload);
        $mailData->subject = 'Ваша заявка на обучение принята';
        $mailData->template = 'mails.bioeconomy';
        Mail::to($biolid->email)->send(new SendEmail($mailData));
    }

    /**
     * Отправляет письмо с доступом. Если пароль не передан — генерирует новый.
     */
    public function sendCredentialsEmail(BioLid $biolid, ?string $password = null): string
    {
        $biolid->loadMissing('user');

        if (! $biolid->user) {
            throw new \InvalidArgumentException('Сначала создайте или привяжите учётную запись');
        }

        if ($password === null) {
            $password = Str::random(8);
            $biolid->user->update([
                'password' => Hash::make($password),
            ]);
        }

        $mailPayload = [
            'firstname' => $biolid->firstname,
            'middlename' => $biolid->middlename,
            'login' => $biolid->user->email,
            'password' => $password,
            'login_url' => route('login'),
        ];

        $mailData = collect($mailPayload);
        $mailData->subject = 'Доступ в личный кабинет';
        $mailData->template = 'mails.bioeconomy_credentials';
        Mail::to($biolid->user->email)->send(new SendEmail($mailData));

        return $password;
    }

    public function processAfterCreate(BioLid $biolid, Course $course): void
    {
        if ($course->biolid_send_confirmation_email) {
            try {
                $this->sendConfirmationEmail($biolid);
            } catch (\Throwable $e) {
                report($e);
            }
        }

        if (! $course->biolid_auto_create_user) {
            return;
        }

        try {
            $result = $this->ensureUser($biolid);
        } catch (\Throwable $e) {
            report($e);

            return;
        }

        $user = $result['user'];
        if (! $user) {
            return;
        }

        if ($course->biolid_study_group_id) {
            try {
                $this->attachToStudyGroup($user, (int) $course->biolid_study_group_id);
            } catch (\Throwable $e) {
                report($e);
            }
        }

        if ($course->biolid_send_credentials_email) {
            try {
                $password = $result['password'];
                if ($password === null) {
                    // Существующий пользователь — выдаём новый пароль.
                    $this->sendCredentialsEmail($biolid);
                } else {
                    $this->sendCredentialsEmail($biolid, $password);
                }
            } catch (\Throwable $e) {
                report($e);
            }
        }
    }

    public function normalizePhoneForUser(?string $phone): string
    {
        $digits = preg_replace('/\D+/', '', (string) $phone) ?? '';
        if (
            (str_starts_with($digits, '8') || str_starts_with($digits, '7'))
            && strlen($digits) === 11
        ) {
            return substr($digits, 1);
        }

        return $digits;
    }
}
