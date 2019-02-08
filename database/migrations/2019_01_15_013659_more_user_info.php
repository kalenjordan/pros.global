<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MoreUserInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->after('password')->nullable();
            $table->string('headline')->after('username')->nullable();
            $table->text('about')->after('headline')->nullable();
            $table->string('avatar_path')->after('about')->nullable();
            $table->string('linkedin_token', 500)->after('avatar_path')->nullable();
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
