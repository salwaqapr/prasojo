<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('inventaris', function (Blueprint $table) {
            $table->dropColumn('saldo');
        });
    }

    public function down()
    {
        Schema::table('inventaris', function (Blueprint $table) {
            $table->integer('saldo')->default(0);
        });
    }
};
