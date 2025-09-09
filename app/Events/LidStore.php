<?php


namespace App\Events;


use App\Models\Lid;

class LidStore
{
    public $lid;

    public function __construct(Lid $lid)
    {
        $this->lid = $lid;
    }

}
