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
        Schema::create('vehicle_views', function (Blueprint $table) {
            $table->increments("id");
            $table->foreignId('vehicle_id')->nullable()->constrained()->nullOnDelete();
            $table->string("url");
            $table->string("session_id");
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string("ip");
            $table->string("agent");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehicle_views', function (Blueprint $table) {
            $table->dropForeign(['vehicle_id', 'user_id']);
        });
        Schema::dropIfExists('vehicle_views');
    }
};
