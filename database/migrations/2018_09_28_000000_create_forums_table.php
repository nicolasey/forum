<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("forum_forums", function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text("content")->nullable();
            $table->unsignedMediumInteger("last_post")->nullable();
            $table->unsignedMediumInteger("order")->nullable();
            $table->timestamps();
            $table->softDeletes();
            // missing nested elements
            $table->nestedSet();
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("forum_posts");
    }
}