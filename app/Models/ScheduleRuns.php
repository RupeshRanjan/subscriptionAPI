<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleRuns extends Model
{
    use HasFactory;

    protected $fillable = [
        'task',
    ];
}
