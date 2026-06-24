<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->string('domain')->nullable()->after('location');
            $table->string('education_level')->nullable()->after('domain');
            $table->decimal('salary', 10, 2)->nullable()->after('education_level');
            $table->string('duration')->nullable()->after('salary');
            $table->string('contract_type')->default('Stage')->after('duration');
            $table->string('work_mode')->default('Presentiel')->after('contract_type');
            $table->text('required_skills')->nullable()->after('work_mode');
        });
    }

    public function down(): void
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->dropColumn([
                'domain',
                'education_level',
                'salary',
                'duration',
                'contract_type',
                'work_mode',
                'required_skills',
            ]);
        });
    }
};
