<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SchoolClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'level_id',
        'name',
        'code',
        'sort_order',
        'status',
    ];

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
