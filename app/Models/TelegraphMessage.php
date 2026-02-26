<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegraphMessage extends Model
{
    use HasFactory;

    protected $table = 'telegraph_messages';
    protected $guarded = false;
}
