<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("forum_topics", function (Blueprint $table) {
            $table->increments('id');
            $table->string('subject');
            $table->string('slug')->unique();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedMediumInteger("forum_id");
            $table->unsignedMediumInteger("last_post")->nullable();
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("forum_topics");
    }
}