<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reddit_id')->nullable();
            $table->text('title')->nullable();
            $table->text('url')->nullable();
            $table->longText('text')->nullable();
            $table->longText('html')->nullable();
            $table->text('permalink')->nullable();
            $table->text('author')->nullable();
            $table->integer('created_utc')->nullable();
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
        Schema::dropIfExists('questions');
    }
}
