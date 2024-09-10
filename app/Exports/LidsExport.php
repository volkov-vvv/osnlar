<?php

namespace App\Exports;

use App\Models\Lid;
use App\Models\Status;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\Style;
use Maatwebsite\Excel\Concerns\WithDefaultStyles;

class LidsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithDefaultStyles
{
    use Exportable;

    public function __construct()
    {
        $this->statuses = Status::all();
        $this->users = User::all();
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
            'Отчество',
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
        $lastname = $lid->lastname;
        $firstname = $lid->firstname;
        $middlename = $lid->middlename;
        $email = $lid->email;
        $created_at = $lid->created_at;

        if($lid->phone_prefix == '7'){
            $phone = '8' . $lid->phone;
        }else{
            $phone = $lid->phone_prefix . $lid->phone;
        }


        $status = $lid->status_title;
        $activity = '';
        $interval = '';

        $activites = $lid->activities2;
        if($activites) {
            $firstTimeAction = $activites->where('description', '=', 'Изменение статуса')->first();
            if ($firstTimeAction) {
                $interval = dateDiff($firstTimeAction->created_at, $lid->created_at);
            } else {
                $interval = '---';
            }
            foreach ($activites as $item){
                $activitiesStatuses = $item->properties;
                $activity .= $item->updated_at . '  ';
                $activity .= $this->users->where('id',$item->causer_id)->first()->name . '  ';
                $activity .= $this->statuses->where('id',$activitiesStatuses['status_id_old'])->first()->title
                    . '->'
                    . $this->statuses->where('id',$activitiesStatuses['status_id'])->first()->title
                    . '  ';
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
            $middlename,
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
