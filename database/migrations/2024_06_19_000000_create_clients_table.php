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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->string('client_name');
            $table->string('contact_number')->nullable();
            $table->string('contact_person_name')->nullable();
            $table->string('mobile_no')->nullable();
            $table->string('email_id')->nullable();
            $table->string('area')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->longText('client_details')->nullable();
            $table->string('calling_status')->nullable();
            $table->longText('feedback')->nullable();
            $table->string('vacancy_status')->nullable();
            $table->string('requirement_status')->nullable();
            $table->string('proposal_status')->nullable();
            $table->string('empannel')->nullable();
            $table->string('internship_payment')->nullable();
            $table->longText('final_remark')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
