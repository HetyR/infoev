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
        Schema::table('specs', function (Blueprint $table) {
            $table->after('name', function (Blueprint $table) {
                $table->char('type', 20)->comment('price, unit, list, description, availability');
                $table->char('unit', 20)->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('specs', function (Blueprint $table) {
            $table->dropColumn(['type', 'unit']);
        });
    }
};
