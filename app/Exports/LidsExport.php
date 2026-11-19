<?php

namespace App\Exports;

use App\Models\Lid;
use App\Models\Status;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithCustomChunkSize;
use PhpOffice\PhpSpreadsheet\Style\Style;
use Maatwebsite\Excel\Concerns\WithDefaultStyles;

class LidsExport implements FromQuery, WithHeadings, WithMapping, WithCustomChunkSize, WithDefaultStyles
{
    use Exportable;

    private $statuses;
    private $users;
    private $currentUserRole;
    private $param;
    private $columnSortName;
    private $columnSortOrder;

    public function __construct()
    {
        $this->statuses = Status::all()->keyBy('id');
        $this->users = User::all()->keyBy('id');
        $this->currentUserRole = auth()->user()->role;
    }

    public function Params($param)
    {
        $this->param = $param;
        return $this;
    }

    public function Order($columnSortName, $columnSortOrder)
    {
        if(!empty($columnSortName)){
            $this->columnSortName = $columnSortName;
        }else{
            $this->columnSortName = 'id';
        }

        if(!empty($columnSortOrder)){
            $this->columnSortOrder = $columnSortOrder;
        }else{
            $this->columnSortOrder = 'desc';
        }

        return $this;
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function query()
    {
        return Lid::filter($this->param)
            ->with(['activities2' => function ($query) {
                $query->where('subject_type', Lid::class)
                    ->orderBy('created_at');
            }])
            ->orderBy($this->columnSortName, $this->columnSortOrder);
    }

    public function headings(): array
    {
        $result= array();
        switch ($this->currentUserRole) {
            case 1:
                $result = [
                    '№',
                    'Ответственный',
                    'Агент',
                    'Курс',
                    'Регион',
                    'Категория',
                    'Фамилия',
                    'Имя',
                    'Отчество',
                    'Email',
                    'Телефон',
                    'Статус',
                    'Реакция',
                    'Активность',
                    'Дата создания',
                    'Дата обновления'
                ];
                break;
            case 3:
                $result = [
                    '№',
                    'Ответственный',
                    'Курс',
                    'Регион',
                    'Категория',
                    'Фамилия',
                    'Имя',
                    'Отчество',
                    'Email',
                    'Телефон',
                    'Статус',
                    'Реакция',
                    'Активность',
                    'Дата создания',
                    'Дата обновления'
                ];
                break;
        }
        return $result;
    }

    public function map($lid): array
    {

        $id = $lid->id;
        if(isset($lid->responsible_name)){
            $responsible = $lid->responsible_name;
        }else{
            $responsible = '';
        }

        if(isset($lid->agent_title)) {
            $agent = $lid->agent_title;
        }else{
            $agent = '';
        }
        $course = $lid->course_title;
        $region = $lid->region_title;
        $category = $lid->category_title;
        $lastname = $lid->lastname;
        $firstname = $lid->firstname;
        $middlename = $lid->middlename;
        $email = $lid->email;
        $created_at = $lid->created_at;
        $updated_at = $lid->updated_at;

        if($lid->phone_prefix == '7'){
            $phone = '8' . $lid->phone;
        }else{
            $phone = $lid->phone_prefix . $lid->phone;
        }


        $status = $lid->status_title;
        $activity = '';
        $interval = '---';

        $activites = $lid->activities2;
        if($activites && $activites->isNotEmpty()) {
            $firstTimeAction = $activites->firstWhere('description', 'Изменение статуса');
            if ($firstTimeAction) {
                $interval = dateDiff($firstTimeAction->created_at, $lid->created_at);
            }
            foreach ($activites as $item){
                $activitiesStatuses = $item->properties ?? [];
                $userName = optional($this->users->get($item->causer_id))->name ?? '—';
                $statusOld = optional($this->statuses->get($activitiesStatuses['status_id_old'] ?? null))->title ?? '—';
                $statusNew = optional($this->statuses->get($activitiesStatuses['status_id'] ?? null))->title ?? '—';

                $activity .= $item->updated_at . '  ';
                $activity .= $userName . '  ';
                $activity .= $statusOld . '->' . $statusNew . '  ';
                if(isset($activitiesStatuses['comment'])){
                    $activity .= "\n";
                    $activity .= $activitiesStatuses['comment'];
                }
                $activity .= "\n-------------\n\n";
            }
        }
        $result = array();
        switch ($this->currentUserRole) {
            case 1:
                $result = [
                    $id,
                    $responsible,
                    $agent,
                    $course,
                    $region,
                    $category,
                    $lastname,
                    $firstname,
                    $middlename,
                    $email,
                    $phone,
                    $status,
                    $interval,
                    $activity,
                    $created_at,
                    $updated_at,
                ];
                break;
            case 3:
                $result = [
                    $id,
                    $responsible,
                    $course,
                    $region,
                    $category,
                    $lastname,
                    $firstname,
                    $middlename,
                    $email,
                    $phone,
                    $status,
                    $interval,
                    $activity,
                    $created_at,
                    $updated_at,
                ];
                break;
        }

        return $result;
    }

    public function defaultStyles(Style $defaultStyle)
    {
        return [
            'alignment' => [
                'wrapText' => true,
            ],
        ];
    }



}
