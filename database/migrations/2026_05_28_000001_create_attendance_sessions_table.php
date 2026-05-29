<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_sessions', function (Blueprint $table) {
            $table->id();
            $table->date('attendance_date');
            $table->string('attendance_type');
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
            $table->foreignId('teaching_assignment_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('school_class_id')->constrained()->onDelete('cascade');
            $table->foreignId('academic_year_id')->constrained()->onDelete('cascade');
            $table->foreignId('semester_id')->constrained()->onDelete('cascade');
            $table->string('status')->default('draft');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->index('attendance_date');
            $table->index('teacher_id');
            $table->index('school_class_id');
            $table->index('attendance_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_sessions');
    }
};
