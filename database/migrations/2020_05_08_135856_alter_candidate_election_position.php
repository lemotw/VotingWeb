<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCandidateElectionPosition extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('CandidateElectionPosition', function (Blueprint $table) {
            $table->integer('CandidateStatus')->default(0)->after('CandidateSet');
            $table->boolean('fixed_flag')->default(false)->after('exp');
            $table->dropColumn('CandidateSet');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
