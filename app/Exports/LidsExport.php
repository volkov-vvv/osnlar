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
    public function __construct()
    {
        $this->statuses = Status::all();
        $lid= new Lid();
        $this->activites = $lid->activities();
    }


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
        $email = $lid->email;
        $created_at = $lid->created_at;

        if($lid->phone_prefix == '7'){
            $phone = '8' . $lid->phone;
        }else{
            $phone = $lid->phone_prefix . $lid->phone;
        }


        $status = $lid->status_title;
        start_measure('activities');
        $activites = $this->activites->where('subject_id', $id);
        $activity = '';
        if($activites){
            $firstTimeAction = $activites->where('description', '=', 'Изменение статуса')->first();
            if($firstTimeAction){
                $interval = dateDiff($firstTimeAction->created_at, $lid->created_at);
            }else{
                $interval = '---';
            }

            foreach ($activites as $item){
                $activitiesStatuses = json_decode($item->properties, true);
                $activity .= $item->updated_at . '  ';
                $activity .= $item->user_name . '  ';
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
        stop_measure('activities');
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
