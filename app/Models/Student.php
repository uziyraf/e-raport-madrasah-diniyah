<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'nis',
        'name',
        'gender',
        'birth_place',
        'birth_date',
        'address',
        'guardian_phone',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
