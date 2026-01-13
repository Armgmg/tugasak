<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    protected $fillable = [
        'name',
        'category',
        'description',
        'poin_required',
        'image',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}
