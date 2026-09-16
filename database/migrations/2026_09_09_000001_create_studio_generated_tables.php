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
        // 1. Admit Cards
        Schema::create('admit_cards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id')->nullable()->index();
            $table->string('student_name');
            $table->string('roll_no')->nullable()->index();
            $table->string('enrollment_no')->nullable()->index();
            $table->string('course_name')->nullable();
            $table->string('parent_name')->nullable();
            $table->string('batch')->nullable();
            $table->string('pass_year')->nullable();
            $table->string('exam_centre')->nullable();
            $table->string('council_name')->nullable();
            $table->longText('photo')->nullable();
            $table->json('payload')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('students')->nullOnDelete();
        });

        // 2. Diplomas
        Schema::create('diplomas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id')->nullable()->index();
            $table->string('student_name');
            $table->string('father_name')->nullable();
            $table->string('gender')->nullable();
            $table->string('serial_no')->nullable()->index();
            $table->string('enrollment_no')->nullable()->index();
            $table->string('course_name')->nullable();
            $table->string('course_category')->nullable();
            $table->string('institute_name')->nullable();
            $table->string('location')->nullable();
            $table->string('division')->nullable();
            $table->string('cert_date')->nullable();
            $table->string('place')->nullable();
            $table->longText('photo')->nullable();
            $table->json('payload')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('students')->nullOnDelete();
        });

        // 3. ID Cards
        Schema::create('id_cards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id')->nullable()->index();
            $table->string('card_uid')->nullable()->index();
            $table->string('student_name');
            $table->string('roll_no')->nullable()->index();
            $table->string('enrollment_no')->nullable()->index();
            $table->string('course')->nullable();
            $table->string('session')->nullable();
            $table->string('center_name')->nullable();
            $table->string('org_name')->nullable();
            $table->string('org_subtitle')->nullable();
            $table->string('card_title')->nullable();
            $table->string('blood_group')->nullable();
            $table->string('emergency_contact')->nullable();
            $table->string('valid_upto')->nullable();
            $table->text('student_address')->nullable();
            $table->longText('photo')->nullable();
            $table->json('payload')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('students')->nullOnDelete();
        });

        // 4. Marksheets
        Schema::create('marksheets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id')->nullable()->index();
            $table->string('student_name');
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('roll_number')->nullable()->index();
            $table->string('enrollment_no')->nullable()->index();
            $table->string('course_name')->nullable();
            $table->string('session')->nullable();
            $table->string('date_of_birth')->nullable();
            $table->string('issue_date')->nullable();
            $table->string('total_marks_obtained')->nullable();
            $table->string('grand_total_max')->nullable();
            $table->string('percentage')->nullable();
            $table->string('overall_grade')->nullable();
            $table->string('result')->nullable();
            $table->json('subjects')->nullable();
            $table->longText('photo')->nullable();
            $table->json('payload')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('students')->nullOnDelete();
        });

        // 5. Migration Certificates
        Schema::create('migration_certificates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id')->nullable()->index();
            $table->string('student_name');
            $table->string('student_prefix')->nullable();
            $table->string('sd_prefix')->nullable();
            $table->string('parent_name')->nullable();
            $table->string('serial_no')->nullable()->index();
            $table->string('enrollment_no')->nullable()->index();
            $table->string('course_name')->nullable();
            $table->string('pass_year')->nullable();
            $table->string('inst_name')->nullable();
            $table->string('place_name')->nullable();
            $table->string('cert_date')->nullable();
            $table->string('signatory_title')->nullable();
            $table->json('payload')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('students')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('migration_certificates');
        Schema::dropIfExists('marksheets');
        Schema::dropIfExists('id_cards');
        Schema::dropIfExists('diplomas');
        Schema::dropIfExists('admit_cards');
    }
};
