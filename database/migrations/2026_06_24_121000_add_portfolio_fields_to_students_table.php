<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->text('skills')->nullable()->after('bio');
            $table->text('projects')->nullable()->after('skills');
            $table->text('experiences')->nullable()->after('projects');
            $table->text('certifications')->nullable()->after('experiences');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'skills',
                'projects',
                'experiences',
                'certifications',
            ]);
        });
    }
};
