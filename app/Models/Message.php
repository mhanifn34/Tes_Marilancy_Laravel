<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    // Fillable biar bisa mass assign
    protected $fillable = [
        'job_id',
        'sender_id',
        'receiver_id',
        'message',
        'is_read',
    ];

    // Relasi ke Job
    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    // Relasi ke Sender (User)
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // Relasi ke Receiver (User)
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
