<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Level extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'arabic_name',
        'description',
        'sort_order',
        'status',
    ];

    public static function tingkatanOptions(): array
    {
        return [
            "I'dadiyah" => 'الإعدادية',
            "Ibtida'iyah" => 'الابتدائية',
            'Tsanawiyah' => 'الثانوية',
        ];
    }

    public function schoolClasses(): HasMany
    {
        return $this->hasMany(SchoolClass::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
