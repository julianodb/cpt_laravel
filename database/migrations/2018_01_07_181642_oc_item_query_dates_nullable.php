<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OcItemQueryDatesNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('oc_item_queries', function (Blueprint $table) {
            $table->dateTime('created_at')->nullable()->change();
            $table->dateTime('sent_at')->nullable()->change();
            $table->dateTime('accepted_at')->nullable()->change();
            $table->dateTime('cancelled_at')->nullable()->change();
            $table->dateTime('updated_at')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('oc_item_queries', function (Blueprint $table) {
            $table->dateTime('created_at')->nullable(false)->change();
            $table->dateTime('sent_at')->nullable(false)->change();
            $table->dateTime('accepted_at')->nullable(false)->change();
            $table->dateTime('cancelled_at')->nullable(false)->change();
            $table->dateTime('updated_at')->nullable(false)->change();
        });
    }
}
