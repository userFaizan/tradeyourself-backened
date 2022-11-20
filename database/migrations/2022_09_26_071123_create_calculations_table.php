<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalculationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calculations', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned();
            $table->integer('goal_id')->unsigned();
            $table->double('High');
            $table->double('Low');
            $table->double('Open');
            $table->double('Close');
            $table->double('Totalpoints');
            $table->string('Date');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('goal_id')->references('id')->on('goals')->onDelete('cascade');


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
        Schema::dropIfExists('calculations');
    }
}
