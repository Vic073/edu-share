<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDownloadCountToMaterialsTable extends Migration
{
    public function up()
{
    Schema::table('materials', function (Blueprint $table) {
        $table->unsignedBigInteger('download_count')->default(0);
    });
}

public function down()
{
    Schema::table('materials', function (Blueprint $table) {
        $table->dropColumn('download_count');
    });
}

}
