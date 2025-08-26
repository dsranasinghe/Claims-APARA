<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('apara_claims', function (Blueprint $table) {
             $table->string('default_reason')->nullable();   // stores selected reason (eg. deceased, fraud)
    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('apara_claims', function (Blueprint $table) {
        $table->dropColumn(['default_reason']);
        });
    }
};
