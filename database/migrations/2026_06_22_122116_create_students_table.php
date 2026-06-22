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
    Schema::create('students', function (Blueprint $table) {
        $table->id();

        $table->foreignId('user_id')
              ->constrained()
              ->onDelete('cascade');

        $table->string('phone')->nullable();
        $table->date('birth_date')->nullable();
        $table->string('city')->nullable();
        $table->string('school')->nullable();
        $table->string('level')->nullable();

        $table->text('bio')->nullable();

        $table->string('cv')->nullable();
        $table->string('photo')->nullable();

        $table->timestamps();
    });
}
};
