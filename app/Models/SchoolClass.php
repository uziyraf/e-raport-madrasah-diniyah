<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SchoolClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'level_id',
        'grade_level',
        'parallel_name',
        'name',
        'code',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'grade_level' => 'integer',
        'sort_order' => 'integer',
    ];

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class);
    }

    public function arabicName(): string
    {
        $grade = strtr((string) ($this->grade_level ?? ''), [
            '0' => '٠',
            '1' => '١',
            '2' => '٢',
            '3' => '٣',
            '4' => '٤',
            '5' => '٥',
            '6' => '٦',
            '7' => '٧',
            '8' => '٨',
            '9' => '٩',
        ]);

        $parallel = strtr(strtoupper((string) ($this->parallel_name ?? '')), [
            'A' => 'أ',
            'B' => 'ب',
            'C' => 'ج',
            'D' => 'د',
            'E' => 'ه',
            'F' => 'و',
        ]);

        $level = $this->level?->arabicName() ?? '';

        return trim($grade . ' ' . $level . ' ' . $parallel);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function classEnrollments(): HasMany
    {
        return $this->hasMany(StudentClassEnrollment::class);
    }

    public function homeroomAssignments(): HasMany
    {
        return $this->hasMany(HomeroomAssignment::class);
    }

    public function teachingAssignments(): HasMany
    {
        return $this->hasMany(TeachingAssignment::class);
    }
}
