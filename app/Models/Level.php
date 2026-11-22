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

    public const ARABIC_NAMES = [
        "I'dadiyah" => 'الإعدادية',
        "Ibtida'iyah" => 'الابتدائية',
        'Tsanawiyah' => 'الثانوية',
        'Aliyah' => 'العالية',
    ];

    public function arabicName(): string
    {
        return $this->arabic_name ?: (self::ARABIC_NAMES[$this->name] ?? $this->name);
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
