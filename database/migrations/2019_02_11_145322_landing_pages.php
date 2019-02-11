<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LandingPages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saved_searches_related', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('saved_search_id');
            $table->foreign('saved_search_id')->references('id')->on('saved_searches');

            $table->unsignedInteger('related_saved_search_id');
            $table->foreign('related_saved_search_id')->references('id')->on('saved_searches');

            $table->integer('sort_order')->default(0);

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
        //
    }
}
