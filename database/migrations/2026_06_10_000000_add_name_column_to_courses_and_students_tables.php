<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            if (! Schema::hasColumn('courses', 'name')) {
                $table->string('name')->nullable()->after('title');
            }
        });

        Schema::table('students', function (Blueprint $table) {
            if (! Schema::hasColumn('students', 'name')) {
                $table->string('name')->nullable()->after('student_code');
            }
        });

        if (Schema::hasTable('courses')) {
            
            DB::table('courses')->whereNull('name')->update([
                'name' => DB::raw('COALESCE(name, "")'),
            ]);
        }

        if (Schema::hasTable('students')) {
            $students = DB::table('students')->select('id', 'first_name', 'last_name')->get();

            foreach ($students as $student) {
                $name = trim(sprintf('%s %s', $student->first_name, $student->last_name));
                DB::table('students')->where('id', $student->id)->update(['name' => $name]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            if (Schema::hasColumn('courses', 'name')) {
                $table->dropColumn('name');
            }
        });

        Schema::table('students', function (Blueprint $table) {
            if (Schema::hasColumn('students', 'name')) {
                $table->dropColumn('name');
            }
        });
    }
};
