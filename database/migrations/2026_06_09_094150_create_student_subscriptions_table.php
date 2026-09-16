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
      Schema::create('student_subscriptions', function (Blueprint $table) {
    $table->id();

    $table->foreignId('student_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->foreignId('campaign_id')
        ->constrained('email_campaigns')
        ->cascadeOnDelete();

    $table->timestamp('subscribed_at')
        ->nullable();

    $table->boolean('status')
        ->default(true);

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_subscriptions');
    }
};
