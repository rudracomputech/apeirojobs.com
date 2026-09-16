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
      Schema::create('invoices', function (Blueprint $table) {
    $table->id();

    $table->string('invoice_no')->unique();

    $table->foreignId('student_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->foreignId('enrollment_id')
        ->nullable()
        ->constrained()
        ->nullOnDelete();

    $table->decimal('total_amount', 12, 2);

    $table->decimal('discount', 12, 2)
        ->default(0);

    $table->decimal('tax', 12, 2)
        ->default(0);

    $table->decimal('paid_amount', 12, 2)
        ->default(0);

    $table->decimal('due_amount', 12, 2)
        ->default(0);

    $table->enum('status', [
        'paid',
        'partially_paid',
        'due',
        'overdue'
    ])->default('due');

    $table->date('due_date')->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
