<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->increments('id');
            $table->text('reddit_id')->nullable();
            $table->unsignedInteger('question_id');
            $table->longText('body')->nullable();
            $table->longText('body_html')->nullable();
            $table->text('permalink')->nullable();
            $table->text('author')->nullable();
            $table->text('author_flair_text')->nullable();
            $table->text('distinguished')->nullable();
            $table->integer('created')->nullable();
            $table->json('replies')->nullable();
            $table->boolean('display')->default(true);
            $table->timestamps();
            $table->foreign('question_id')
                ->references('id')
                ->on('questions')
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
        Schema::dropIfExists('answers');
    }
}
