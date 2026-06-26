<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('stages', function (Blueprint $table) {
            $table->string('jury_members')->nullable();
            $table->dateTime('defense_date')->nullable();
            $table->decimal('final_grade', 4, 2)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('stages', function (Blueprint $table) {
            $table->dropColumn(['jury_members', 'defense_date', 'final_grade']);
        });
    }
};
