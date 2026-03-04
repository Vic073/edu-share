<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->foreignId('institution_id')->nullable()->after('user_id')->constrained()->onDelete('set null');
            $table->foreignId('faculty_id')->nullable()->after('institution_id')->constrained()->onDelete('set null');
            $table->foreignId('course_id')->nullable()->after('faculty_id')->constrained()->onDelete('set null');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved')->after('visibility');
        });
    }

    public function down()
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->dropForeign(['institution_id']);
            $table->dropForeign(['faculty_id']);
            $table->dropForeign(['course_id']);
            $table->dropColumn(['institution_id', 'faculty_id', 'course_id', 'status']);
        });
    }
};
