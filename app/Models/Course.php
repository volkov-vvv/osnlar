<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'courses';
    protected $guarded = false;

    public function authors(){
        return $this->belongsToMany(Author::class, 'course_authors', 'course_id', 'author_id');
    }

}
