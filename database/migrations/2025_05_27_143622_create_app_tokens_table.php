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
        Schema::create('app_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('device_id')->unique();
            $table->string('app_key')->unique();
            $table->string('platform')->nullable();
            $table->timestamp('last_used_at')->nullable();
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
        Schema::dropIfExists('app_tokens');
    }
};
