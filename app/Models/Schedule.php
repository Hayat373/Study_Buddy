<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'start_time',
        'end_time',
        'type',
        'color',
        'recurring',
        'recurring_pattern',
        'recurring_until'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'recurring_until' => 'date',
        'recurring' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}