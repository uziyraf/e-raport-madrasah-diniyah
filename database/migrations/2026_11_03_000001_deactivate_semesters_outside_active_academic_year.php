<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $activeAcademicYearId = DB::table('academic_years')
            ->where('is_active', true)
            ->value('id');

        if (! $activeAcademicYearId) {
            return;
        }

        DB::table('semesters')
            ->where('is_active', true)
            ->where('academic_year_id', '!=', $activeAcademicYearId)
            ->update(['is_active' => false]);
    }

    public function down(): void {}
};
