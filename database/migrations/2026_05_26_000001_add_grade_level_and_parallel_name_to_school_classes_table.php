<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('school_classes', function (Blueprint $table) {
            $table->unsignedTinyInteger('grade_level')->nullable()->after('level_id');
            $table->string('parallel_name')->nullable()->after('grade_level');

            $table->index(['level_id', 'grade_level']);
        });
    }

    public function down(): void
    {
        Schema::table('school_classes', function (Blueprint $table) {
            $table->dropIndex(['level_id', 'grade_level']);

            $table->dropColumn('parallel_name');
            $table->dropColumn('grade_level');
        });
    }
};
