<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained()->cascadeOnDelete();
            $table->text('question_text');
            $table->json('options')->nullable(); // array of choices for QCM
            $table->integer('correct_option')->nullable(); // index of correct option
            $table->boolean('is_code_exercise')->default(false);
            $table->text('code_starter')->nullable();
            $table->json('code_test_cases')->nullable(); // e.g. input/output expectations
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_questions');
    }
};
