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
      Schema::create('enrollments', function (Blueprint $table) {
    $table->id();

    $table->foreignId('student_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->foreignId('course_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->foreignId('batch_id')
        ->nullable()
        ->constrained()
        ->nullOnDelete();

    $table->date('enrollment_date');

    $table->decimal('fee_amount', 12, 2);

    $table->decimal('discount_amount', 12, 2)
        ->default(0);

    $table->enum('status', [
        'active',
        'completed',
        'dropped',
        'pending'
    ])->default('active');

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
