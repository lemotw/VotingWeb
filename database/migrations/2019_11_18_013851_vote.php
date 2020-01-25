<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Vote extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('AuthToken', function (Blueprint $table) {
            $table->Increments('id');
            $table->integer('Election');
            $table->string('Token', 64);
            $table->string('sid', 64);
            $table->boolean('Voted');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('VoteRecord', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('ElectionPosition', 64);
            $table->string('Candidate', 64);
            $table->integer('YN_Vote');
            $table->boolean('broken');
            $table->timestamps();
        });

        Schema::create('VoteResult', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('ElectionPosition', 64);
            $table->string('Candidate', 64);
            $table->Integer('VoteCount')->default(0);
            $table->Integer('Yes')->default(0);
            $table->Integer('No')->default(0);
            $table->Integer('disable')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('AuthToken');
        Schema::dropIfExists('VoteRecord');
        Schema::dropIfExists('VotingResult');
    }
}
