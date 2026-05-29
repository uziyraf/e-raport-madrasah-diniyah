<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\AttendanceDetail;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'nis',
        'name',
        'arabic_name',
        'gender',
        'birth_place',
        'birth_date',
        'address',
        'father_name',
        'mother_name',
        'guardian_name',
        'guardian_phone',
        'photo_path',
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

    public function classEnrollments(): HasMany
    {
        return $this->hasMany(StudentClassEnrollment::class);
    }

    public function activeEnrollment()
    {
        return $this->hasOne(StudentClassEnrollment::class)
            ->where('is_active', true)
            ->with('schoolClass.level', 'academicYear', 'semester');
    }

    public function attendanceDetails(): HasMany
    {
        return $this->hasMany(AttendanceDetail::class);
    }
}
