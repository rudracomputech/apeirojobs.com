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

    $table->string('student_code')->unique();

    $table->string('first_name');
    $table->string('last_name')->nullable();

    $table->string('email')->nullable()->index();
    $table->string('mobile')->index();

    $table->enum('gender', ['male', 'female', 'other'])->nullable();

    $table->date('dob')->nullable();

    $table->string('father_name')->nullable();
    $table->string('mother_name')->nullable();

    $table->text('address')->nullable();
    $table->string('city')->nullable();
    $table->string('state')->nullable();
    $table->string('country')->nullable();

    $table->date('admission_date')->nullable();

    $table->boolean('status')->default(true);

    $table->foreignId('created_by')->nullable()
        ->constrained('users')
        ->nullOnDelete();

    $table->timestamps();
    $table->softDeletes();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
