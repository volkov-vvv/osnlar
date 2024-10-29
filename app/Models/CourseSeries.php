<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseSeries extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'course_series';
    protected $guarded = false;
}
