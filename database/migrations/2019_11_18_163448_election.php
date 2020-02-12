<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Election extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Election', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('Name', 32);
            $table->dateTime('StartTime');
            $table->dateTime('EndTime');
            $table->dateTime('RegisterStart');
            $table->dateTime('RegisterEnd');
            $table->dateTime('VoteStart');
            $table->dateTime('VoteEnd');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('Position', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('Name', 32);
            $table->string('Unit', 32)->nullable();
            $table->string('QualifyRegex', 128);
            $table->text('RequireDocument');
            $table->timestamps();
            $table->softDeletes();
        });

        //Relationship with election and position
        Schema::create('ElectionPosition', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('UID', 64);
            $table->string('Name', 32);
            $table->integer('Election');
            $table->integer('Position');
            $table->integer('ElectionType');
            //Multiple choice or YN choice
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('CandidateRegister', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('Name', 32);
            $table->string('account', 128);
            $table->string('password', 256);
            $table->integer('ElectionPosition');
            $table->string('token', 128);
            //relation to ElectionPosition
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('Candidate', function (Blueprint $table) {
            $table->string('Candidate', 64);
            $table->string('Name', 32);
            $table->integer('ElectionPosition');
            $table->integer('CandidateRegister');
            $table->timestamps();
            $table->softDeletes();

            $table->primary('Candidate');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Candidate');
    }
}
