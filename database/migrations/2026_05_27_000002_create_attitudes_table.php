<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attitudes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('school_class_id')->constrained()->onDelete('cascade');
            $table->foreignId('academic_year_id')->constrained()->onDelete('cascade');
            $table->foreignId('semester_id')->constrained()->onDelete('cascade');
            $table->foreignId('homeroom_teacher_id')->constrained('teachers')->onDelete('cascade');
            $table->string('akhlak', 50)->nullable();
            $table->string('discipline', 50)->nullable();
            $table->string('cleanliness', 50)->nullable();
            $table->text('attitude_note')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'academic_year_id', 'semester_id'], 'attitudes_unique');
            $table->index('student_id');
            $table->index('school_class_id');
            $table->index('academic_year_id');
            $table->index('semester_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attitudes');
    }
};
