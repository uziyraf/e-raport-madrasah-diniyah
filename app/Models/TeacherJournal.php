<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherJournal extends Model
{
    use HasFactory;

    protected $fillable = [
        'journal_date',
        'teacher_id',
        'teaching_assignment_id',
        'school_class_id',
        'academic_year_id',
        'semester_id',
        'journal_type',
        'student_id',
        'memorization_type',
        'memorization_target',
        'memorization_result',
        'kitab_name',
        'kitab_page',
        'legalization_status',
        'daily_score',
        'exam_score',
        'predicate',
        'note',
        'status',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'journal_date' => 'date',
            'daily_score' => 'decimal:2',
            'exam_score' => 'decimal:2',
        ];
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function teachingAssignment(): BelongsTo
    {
        return $this->belongsTo(TeachingAssignment::class);
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class);
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
