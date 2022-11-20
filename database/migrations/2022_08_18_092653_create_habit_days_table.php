<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHabitDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('habit_days', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('days_id')->unsigned();
            $table->foreign('days_id')->references('id')->on('days')->onDelete('cascade');
            $table->integer('habit_id')->unsigned();
            $table->foreign('habit_id')->references('id')->on('habits')->onDelete('cascade');
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
        Schema::dropIfExists('habit_days');
    }
}
