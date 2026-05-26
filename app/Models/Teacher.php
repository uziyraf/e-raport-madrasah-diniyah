<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'teacher_code',
        'name',
        'gender',
        'birth_place',
        'birth_date',
        'phone',
        'email',
        'address',
        'signature_path',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function homeroomAssignments(): HasMany
    {
        return $this->hasMany(HomeroomAssignment::class);
    }

    public function teachingAssignments(): HasMany
    {
        return $this->hasMany(TeachingAssignment::class);
    }

    public function currentHomeroomAssignment()
    {
        return $this->hasOne(HomeroomAssignment::class)
            ->whereHas('academicYear', fn($q) => $q->where('is_active', true))
            ->whereHas('semester', fn($q) => $q->where('is_active', true))
            ->with('schoolClass.level', 'academicYear', 'semester');
    }

    public function currentTeachingAssignments()
    {
        return $this->hasMany(TeachingAssignment::class)
            ->whereHas('academicYear', fn($q) => $q->where('is_active', true))
            ->whereHas('semester', fn($q) => $q->where('is_active', true))
            ->with('subject', 'schoolClass.level');
    }
}
