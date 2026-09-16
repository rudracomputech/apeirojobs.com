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
      Schema::create('student_progress', function (Blueprint $table) {
    $table->id();

    $table->foreignId('student_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->foreignId('course_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->foreignId('module_id')
        ->constrained('course_modules')
        ->cascadeOnDelete();

    $table->decimal('completion_percentage', 5, 2)
        ->default(0);

    $table->timestamp('completed_at')->nullable();

    $table->timestamp('last_sync_at')->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_progress');
    }
};
