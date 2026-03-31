<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Job extends Model
{
    use HasFactory;

    // Biar bisa input data lewat controller sekaligus (Mass Assignment)
    protected $fillable = [
        'client_id', 
        'title', 
        'description', 
        'category', 
        'budget', 
        'status'
    ];

    /**
     * Relasi: Job ini dimiliki oleh siapa (Client)
     * Balik ke tabel Users dengan role client
     */
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Relasi: Satu Job bisa punya banyak lamaran/bidding
     */
    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    /**
     * Relasi: Satu Job bisa punya banyak transaksi
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Relasi: Satu Job bisa punya banyak pesan
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}