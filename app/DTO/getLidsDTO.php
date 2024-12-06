<?php


namespace App\DTO;


use Spatie\DataTransferObject\DataTransferObject;

class getLidsDTO extends DataTransferObject
{
    public int $draw;
    public int $start;
    public int $length;
    public $columns;
    public $order;
    public $search;
    public bool $commerce;
}
