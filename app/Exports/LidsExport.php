<?php

namespace App\Exports;

use App\Models\Lid;
use App\Models\Status;
use App\Models\User;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDefaultStyles;
use PhpOffice\PhpSpreadsheet\Style\Style;

class LidsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithDefaultStyles
{
    use Exportable;

    public function Params($param)
    {
        $this->param = $param;

        return $this;
    }

    public function Order($columnSortName, $columnSortOrder)
    {
        $this->columnSortName = $columnSortName;
        $this->columnSortOrder = $columnSortOrder;

        return $this;
    }

    public function query()
    {
        return Lid::filter($this->param)->orderBy($this->columnSortName,$this->columnSortOrder);
    }

    public function headings(): array
    {
        return [
            '№',
            'Ответственный',
            'Агент',
            'Курс',
            'Регион',
            'Фамилия',
            'Имя',
            'Email',
            'Телефон',
            'Статус',
            'Реакция',
            'Активность',
            'Дата создания'
        ];
    }

    public function map($lid): array
    {
        $statuses = Status::all();
        $users = User::all();
        $id = $lid->id;
        if(isset($lid->responsible)){
            $responsible = $lid->responsible->name;
        }else{
            $responsible = '';
        }

        if(isset($lid->agent)) {
            $agent = $lid->agent->title;
        }else{
            $agent = '';
        }
        $course = $lid->course->title;
        $region = $lid->region->title;
        $lastname = $lid->lastname;
        $firstname = $lid->firstname;
        $email = $lid->email;
        $created_at = $lid->created_at;

        if($lid->phone_prefix == '7'){
            $phone = '8' . $lid->phone;
        }else{
            $phone = $lid->phone_prefix . $lid->phone;
        }

        $status = $lid->status->title;
        if($lid->activity){
            $interval = dateDiff($lid->activity->created_at, $lid->created_at);
        }else{
            $interval = '---';
        }
        $activites = $lid->activities;

        $activity = '';
        if($activites){
            foreach ($activites as $item){
                $activitiesStatuses = $item->properties;
                $activity .= $item->updated_at . '  ';
                $activity .= $users->where('id', $item->causer_id)->first()->name . '  ';
                $activity .= $statuses->where('id',  $activitiesStatuses['status_id_old'])->first()->title . '->' . $statuses->where('id',  $activitiesStatuses['status_id'])->first()->title . '  ';
                if(isset($activitiesStatuses['comment'])){
                    $activity .= "\n";
                    $activity .= $activitiesStatuses['comment'];
                }
                $activity .= "\n-------------\n\n";
            }
        }

        return [
            $id,
            $responsible,
            $agent,
            $course,
            $region,
            $lastname,
            $firstname,
            $email,
            $phone,
            $status,
            $interval,
            $activity,
            $created_at,
        ];
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
