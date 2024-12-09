<?php

namespace App\Exports;

use App\Models\Lid;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ReportExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
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
            'Агент',
            'Регион',
            'Курс',
            'Фамилия',
            'Имя',
            'Email',
            'Категория',
            'Статус',
            'Дата создания',
            'utm_source',
            'utm_medium',
            'utm_campaign'
        ];
    }

    public function map($lid): array
    {
        $id = $lid->id;
        if(isset($lid->agent)) {
            $agent = $lid->agent->title;
        }else{
            $agent = '';
        }
        $course = $lid->course->title;
        $region = $lid->region->title;
        $lastname = $lid->lastname;
        $email = $lid->email;
        $firstname = $lid->firstname;
        $created_at = $lid->created_at;
        $status = $lid->status->title;

        if($lid->category){
            $category = $lid->category->title;
        }else{
            $category = '';
        }

        $utm_source = $lid->utm_source;
        $utm_medium = $lid->utm_medium;
        $utm_campaign = $lid->utm_campaign;

        return [
            $id,
            $agent,
            $region,
            $course,
            $lastname,
            $firstname,
            $email,
            $category,
            $status,
            $created_at,
            $utm_source,
            $utm_medium,
            $utm_campaign
        ];
    }
}
