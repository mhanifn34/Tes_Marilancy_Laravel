<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['job_id', 'task_name', 'status'];

    // Satu task pasti punyanya satu job tertentu
    public function job() {
        return $this->belongsTo(Job::class);
    }
}