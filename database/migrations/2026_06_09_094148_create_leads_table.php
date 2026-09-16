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
     Schema::create('leads', function (Blueprint $table) {
    $table->id();

    $table->string('name');
    $table->string('email')->nullable();
    $table->string('mobile')->index();

    $table->string('source')->nullable();

    $table->foreignId('course_interest_id')
        ->nullable()
        ->constrained('courses')
        ->nullOnDelete();

    $table->enum('status', [
        'new',
        'contacted',
        'interested',
        'follow_up',
        'demo_scheduled',
        'converted',
        'lost'
    ])->default('new');

    $table->foreignId('assigned_to')
        ->nullable()
        ->constrained('users')
        ->nullOnDelete();

    $table->foreignId('student_id')
        ->nullable()
        ->constrained('students')
        ->nullOnDelete();

    $table->date('next_followup_date')->nullable();

    $table->timestamp('converted_at')->nullable();

    $table->longText('remarks')->nullable();

    $table->timestamps();
    $table->softDeletes();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
