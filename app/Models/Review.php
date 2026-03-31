<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'job_id',
        'freelancer_id',
        'rating',
        'comment',
    ];

    // Relasi ke Job
    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    // Relasi ke Freelancer (User)
    public function freelancer()
    {
        return $this->belongsTo(User::class, 'freelancer_id');
    }
}
