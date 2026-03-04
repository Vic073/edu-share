<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('institution_id')->nullable()->after('role')->constrained()->onDelete('set null');
            $table->enum('subscription_tier', ['free', 'premium'])->default('free')->after('institution_id');
            $table->enum('kyc_status', ['pending', 'verified', 'rejected'])->default('pending')->after('subscription_tier');
            $table->string('id_document_path')->nullable()->after('kyc_status');
            $table->string('phone', 20)->nullable()->after('email');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['institution_id']);
            $table->dropColumn(['institution_id', 'subscription_tier', 'kyc_status', 'id_document_path', 'phone']);
        });
    }
};
