<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JadwalPelajaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'tahun_ajaran_id',
        'semester_id',
        'kelas_id',
        'mapel_id',
        'guru_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'keterangan',
        'created_by',
    ];

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class, 'tahun_ajaran_id');
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'kelas_id');
    }

    public function mapel(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'mapel_id');
    }

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'guru_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
