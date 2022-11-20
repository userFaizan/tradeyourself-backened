<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHabitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('habits', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('category_id')->unsigned();
            $table->integer('goal_id')->unsigned();
            $table->integer('priority_id')->unsigned();
            $table->string('name');
            $table->time('reminder_time');
            $table->integer('status')->default('0');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('habit_categories')->onDelete('cascade');
            $table->foreign('goal_id')->references('id')->on('goals')->onDelete('cascade');
            $table->foreign('priority_id')->references('id')->on('priorities')->onDelete('cascade');
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
        Schema::dropIfExists('habits');
    }
}
