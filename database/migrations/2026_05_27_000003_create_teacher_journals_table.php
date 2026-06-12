<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teacher_journals', function (Blueprint $table) {
            $table->id();
            $table->date('journal_date');
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
            $table->foreignId('teaching_assignment_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('school_class_id')->constrained()->onDelete('cascade');
            $table->foreignId('academic_year_id')->constrained()->onDelete('cascade');
            $table->foreignId('semester_id')->constrained()->onDelete('cascade');
            $table->string('journal_type');
            $table->foreignId('student_id')->nullable()->constrained()->onDelete('set null');
            $table->string('memorization_type', 100)->nullable();
            $table->string('memorization_target', 255)->nullable();
            $table->string('memorization_result', 255)->nullable();
            $table->string('kitab_name', 255)->nullable();
            $table->string('kitab_page', 100)->nullable();
            $table->string('legalization_status', 100)->nullable();
            $table->decimal('daily_score', 5, 2)->nullable();
            $table->decimal('exam_score', 5, 2)->nullable();
            $table->string('predicate', 50)->nullable();
            $table->text('note')->nullable();
            $table->enum('status', ['draft', 'submitted'])->default('draft');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->index('journal_date');
            $table->index('journal_type');
            $table->index('teacher_id');
            $table->index('school_class_id');
            $table->index('teaching_assignment_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_journals');
    }
};
