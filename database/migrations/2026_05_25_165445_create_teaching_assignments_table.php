<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('teaching_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('restrict');
            $table->foreignId('school_class_id')->constrained()->onDelete('cascade');
            $table->foreignId('academic_year_id')->constrained()->onDelete('restrict');
            $table->foreignId('semester_id')->constrained()->onDelete('restrict');
            $table->timestamps();

            $table->unique(['teacher_id', 'subject_id', 'school_class_id', 'academic_year_id', 'semester_id'], 'ta_unique');
            $table->index('teacher_id');
            $table->index('subject_id');
            $table->index('school_class_id');
            $table->index('academic_year_id');
            $table->index('semester_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teaching_assignments');
    }
};
