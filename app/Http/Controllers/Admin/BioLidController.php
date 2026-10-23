<?php

namespace App\Http\Controllers\Admin;

use App\Exports\BioLidsQuestionnaireExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BioLid\UpdateRequest;
use App\Mail\SendEmail;
use App\Models\BioLid;
use App\Models\Course;
use App\Models\Status;
use App\Models\StudyGroup;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Activitylog\Models\Activity;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BioLidController extends Controller
{
    public function index()
    {
        $bioLids = BioLid::query()
            ->with(['course', 'status', 'responsible', 'user.studyGroups'])
            ->latest()
            ->get();

        $courses = Course::query()
            ->where('bioeconomy', 1)
            ->orderBy('title')
            ->get();

        $organizations = BioLid::query()
            ->whereNotNull('organization_title')
            ->where('organization_title', '!=', '')
            ->distinct()
            ->orderBy('organization_title')
            ->pluck('organization_title');

        $statuses = Status::all();
        $users = User::where('role', User::ROLE_CONTACT)->get();
        $studyGroups = StudyGroup::query()->orderBy('title')->get();

        return view('admin.biolid.index', compact(
            'bioLids',
            'courses',
            'organizations',
            'statuses',
            'users',
            'studyGroups'
        ));
    }

    public function show(BioLid $biolid)
    {
        $biolid->load(['course', 'status', 'responsible', 'user']);
        $statuses = Status::all();
        $activites = $this->bioLidActivities($biolid, $statuses);

        return view('admin.biolid.show', [
            'bioLid' => $biolid,
            'statuses' => $statuses,
            'activites' => $activites,
        ]);
    }

    public function edit(BioLid $biolid)
    {
        $courses = Course::query()
            ->where('bioeconomy', 1)
            ->orderBy('title')
            ->get();

        $statuses = Status::all();
        $users = User::where('role', User::ROLE_CONTACT)->get();

        return view('admin.biolid.edit', [
            'bioLid' => $biolid,
            'courses' => $courses,
            'genders' => BioLid::GENDERS,
            'statuses' => $statuses,
            'users' => $users,
        ]);
    }

    public function update(UpdateRequest $request, BioLid $biolid)
    {
        $data = $request->validated();
        $oldStatus = $biolid->status_id;
        $biolid->update($data);

        $actionDescription = $oldStatus != ($data['status_id'] ?? $oldStatus)
            ? 'Изменение статуса'
            : 'Изменение информации';

        activity()
            ->causedBy(auth()->user())
            ->performedOn($biolid)
            ->withProperties([
                'status_id_old' => $oldStatus,
                'status_id' => $data['status_id'] ?? $oldStatus,
                'comment' => $request->input('comment'),
            ])
            ->log($actionDescription);

        return redirect()->route('admin.biolid.show', $biolid);
    }

    public function destroy(BioLid $biolid)
    {
        $biolid->delete();

        return redirect()
            ->route('admin.biolid.index')
            ->with('success', 'Заявка удалена');
    }

    public function questionnaire(Request $request): BinaryFileResponse
    {
        $ids = $request->input('ids');
        if (! is_array($ids)) {
            $ids = [];
        }

        $filename = 'anketa-gruppy-bio-'.now()->format('Y-m-d_H-i-s').'.xlsx';

        return Excel::download(new BioLidsQuestionnaireExport($ids), $filename);
    }

    public function createUser(BioLid $biolid): RedirectResponse
    {
        if ($biolid->user) {
            return redirect()
                ->route('admin.biolid.show', $biolid)
                ->with('error', 'Учётка уже привязана');
        }

        // На случай «осиротевшей» привязки после удаления пользователя.
        if ($biolid->user_id) {
            $biolid->forceFill(['user_id' => null])->save();
        }

        $email = trim((string) $biolid->email);
        if ($email === '') {
            return redirect()
                ->route('admin.biolid.show', $biolid)
                ->with('error', 'У заявки не указан email');
        }

        $user = User::query()->where('email', $email)->first();
        $created = false;

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

        activity()
            ->causedBy(auth()->user())
            ->performedOn($biolid)
            ->withProperties([
                'status_id_old' => $biolid->status_id,
                'status_id' => $biolid->status_id,
                'comment' => ($created ? 'Создан' : 'Привязан').' пользователь #'.$user->id,
                'user_id' => $user->id,
            ])
            ->log($created ? 'Создание учётной записи' : 'Привязка учётной записи');

        return redirect()
            ->route('admin.biolid.show', $biolid)
            ->with(
                'success',
                $created
                    ? 'Учётная запись создана и привязана к заявке'
                    : 'Существующая учётная запись привязана к заявке'
            );
    }

    public function sendCredentials(BioLid $biolid): RedirectResponse
    {
        $biolid->loadMissing('user');

        if (! $biolid->user) {
            return redirect()
                ->route('admin.biolid.show', $biolid)
                ->with('error', 'Сначала создайте или привяжите учётную запись');
        }

        $password = Str::random(8);
        $biolid->user->update([
            'password' => Hash::make($password),
        ]);

        $mailPayload = [
            'firstname' => $biolid->firstname,
            'middlename' => $biolid->middlename,
            'login' => $biolid->user->email,
            'password' => $password,
            'login_url' => route('login'),
        ];

        try {
            $mailData = collect($mailPayload);
            $mailData->subject = 'Доступ в личный кабинет';
            $mailData->template = 'mails.bioeconomy_credentials';
            Mail::to($biolid->user->email)->send(new SendEmail($mailData));
        } catch (\Throwable $e) {
            report($e);

            return redirect()
                ->route('admin.biolid.show', $biolid)
                ->with('error', 'Не удалось отправить письмо. Пароль обновлён, попробуйте ещё раз.');
        }

        activity()
            ->causedBy(auth()->user())
            ->performedOn($biolid)
            ->withProperties([
                'status_id_old' => $biolid->status_id,
                'status_id' => $biolid->status_id,
                'comment' => 'Отправлено письмо с доступом на '.$biolid->user->email,
                'user_id' => $biolid->user->id,
            ])
            ->log('Отправка письма с доступом');

        return redirect()
            ->route('admin.biolid.show', $biolid)
            ->with('success', 'Письмо с доступом отправлено');
    }

    protected function normalizePhoneForUser(?string $phone): string
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

    protected function bioLidActivities(BioLid $bioLid, Collection $statuses): Collection
    {
        $activites = Activity::query()
            ->where('subject_type', BioLid::class)
            ->where('subject_id', $bioLid->id)
            ->orderBy('created_at')
            ->get();

        $activites->each(function ($item) use ($statuses) {
            $properties = $item->properties;
            $oldStatus = $statuses->where('id', $properties['status_id_old'] ?? null)->first();
            $newStatus = $statuses->where('id', $properties['status_id'] ?? null)->first();
            $item->status_old = $oldStatus->title ?? '—';
            $item->status_new = $newStatus->title ?? '—';
            $item->comment = $properties['comment'] ?? '';
            $item->user = User::find($item->causer_id)?->name ?? '—';
        });

        $firstActivity = $activites->firstWhere('description', 'Изменение статуса');
        if ($firstActivity) {
            $interval = date_diff($firstActivity->created_at, $bioLid->created_at);
            $formatStr = '%hч %iмин';
            if ($interval->d > 0) {
                $formatStr = '%dд '.$formatStr;
            }
            if ($interval->m > 0) {
                $formatStr = '%mм '.$formatStr;
            }
            if ($interval->y > 0) {
                $formatStr = '%yг '.$formatStr;
            }
            $activites->interval = $interval->format($formatStr);
        } else {
            $activites->interval = '---';
        }

        return $activites;
    }
}
