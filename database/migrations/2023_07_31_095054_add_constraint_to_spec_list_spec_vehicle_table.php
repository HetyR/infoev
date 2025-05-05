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
        Schema::table('spec_list_spec_vehicle', function (Blueprint $table) {
            $table->foreign('spec_vehicle_id')->references('id')->on('spec_vehicle')->onDelete('cascade');
            $table->foreignId('spec_list_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('spec_list_spec_vehicle', function (Blueprint $table) {
            $table->dropForeign(['spec_vehicle_id', 'spec_list_id']);
        });
    }
};
