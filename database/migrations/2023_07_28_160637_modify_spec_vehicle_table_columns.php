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
        Schema::table('spec_vehicle', function (Blueprint $table) {
            $table->decimal('value')->nullable()->change();
            $table->text('value_desc')->nullable();
            $table->boolean('value_bool')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('spec_vehicle', function (Blueprint $table) {
            $table->string('value')->change();
            $table->dropColumn(['value_desc', 'value_bool']);
        });
    }
};
