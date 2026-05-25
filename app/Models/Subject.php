<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'arabic_name',
        'code',
        'sort_order',
        'status',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
