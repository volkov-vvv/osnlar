<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Leveledu extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'levels_edu';
    protected $guarded = false;


}
