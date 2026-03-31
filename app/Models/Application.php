<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id', 
        'freelancer_id', 
        'bid_amount', 
        'proposal', 
        'status'
    ];

    // Relasi ke tabel Job (Lamaran ini buat proyek yang mana?)
    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    // Relasi ke User (Siapa freelancer yang melamar?)
    public function freelancer()
    {
        return $this->belongsTo(User::class, 'freelancer_id');
    }
}