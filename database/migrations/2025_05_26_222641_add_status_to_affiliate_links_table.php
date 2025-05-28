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
        Schema::table('affiliate_links', function (Blueprint $table) {
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_checked_at')->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('affiliate_links', function (Blueprint $table) {
            $table->dropColumn(['is_active', 'last_checked_at']);
        });
    }
};
