<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'tipe_transaksi', // setor / tukar
        'jenis_sampah',
        'berat',
        'reward',
        'poin',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getDescriptionAttribute()
    {
        return $this->jenis_sampah ?? ($this->reward ?? 'Transaksi');
    }

    public function getDisplayPoinAttribute()
    {
        return $this->poin;
    }

    public function getTypeAttribute($value)
    {
        if ($this->tipe_transaksi === 'setor') {
            return 'scan';
        }
        return $this->tipe_transaksi; // 'tukar' or others
    }
}
