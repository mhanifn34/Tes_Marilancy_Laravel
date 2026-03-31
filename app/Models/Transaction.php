<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    // Fillable attributes untuk mass assignment
    protected $fillable = [
        'user_id',
        'job_id',
        'amount',
        'type',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Job
    public function job()
    {
        return $this->belongsTo(Job::class);
    }
}
