<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('father_name')->nullable()->after('address');
            $table->string('mother_name')->nullable()->after('father_name');
            $table->string('guardian_name')->nullable()->after('mother_name');
            $table->string('arabic_name')->nullable()->after('guardian_name');
            $table->string('photo_path')->nullable()->after('arabic_name');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['father_name', 'mother_name', 'guardian_name', 'arabic_name', 'photo_path']);
        });
    }
};
