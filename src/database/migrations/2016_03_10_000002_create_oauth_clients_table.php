<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOauthClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oauth_clients', function (Blueprint $table) {
            $table->increments('id');
            $table->string('external_id')->unique();
            $table->string('secret');
            $table->string('website_address');
            $table->integer('oauth_user_id')->unsigned()->nullable();
            $table->timestamps();

            $table
                ->foreign('oauth_user_id')
                ->references('id')
                ->on('oauth_users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('oauth_clients');
    }
}
