<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scan extends Model
{
    protected $fillable = [
        'user_id',
        'image_path',
        'ai_result',
        'detected_items',
        'status',
        'admin_notes',
        'confirmed_by',
        'total_weight',
        'poin_earned',
    ];

    protected $casts = [
        'ai_result' => 'array',
        'detected_items' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function confirmedBy()
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }
}
