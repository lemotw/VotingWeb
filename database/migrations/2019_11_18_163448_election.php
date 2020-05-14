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
            $table->text('RequireDocument')->nullable();
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

        Schema::create('CandidateElectionPosition', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('Candidate', 64);
            $table->Integer('ElectionPosition');
            $table->string('path', 256);
            $table->string('exp', 768);
            $table->boolean('CandidateSet')->default(false);
            // path to local file
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('Candidate', function (Blueprint $table) {
            $table->string('Candidate', 64);
            $table->string('Name', 32);
            $table->string('image', 256)->nullable();
            $table->string('account', 128)->unique();
            $table->string('password', 256);
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
