<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use Illuminate\Support\Facades\Crypt;
use App\Service\Encryption\Encrypter;
use Illuminate\Support\Facades\Hash;
use App\Service\Token\TokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Election\Election;
use App\Models\Election\Candidate;
use App\Models\Election\ElectionPosition;
use App\Models\Vote\VoteRecord;
use App\User;
use App\Repository\Election\PositionRepository;
use App\Repository\Election\ElectionPositionRepository;
use App\Repository\Election\ElectionRepository;
use App\Service\Election\ElectionService;
use App\Repository\Election\CandidateRepository;
use App\Repository\Election\CandidateRegisterRepository;
use App\Repository\Vote\VoteResultRepository;


Route::get('/', function () {
    return view('welcome');
});

// app/Http/routes.php
Route::get('sendmail', function() {
    $service = new ElectionService();
    dd($service->ElectionDelete(3));
    dd($service->ElectionPositionModify([
        'UID' => 'e9357d17dec0250a785cfdf4a4f66b632f417508629cb688750d0483e70a0e7c',
        'Name' => '110級資工系學會長'
    ]));
    dd($service->ElectionModify([
        'id' => 7,
        'Name' => '119三合一選舉',
        'StartTime' => '2020/01/20',
        'EndTime' => '2020/06/20',
        'RegisterStart' => '2020/01/20',
        'RegisterEnd' => '2020/03/20',
        'VoteStart' => '2020/04/20',
        'VoteEnd' => '2020/06/20',
    ]));
});


Route::get('/test', function(){
    $voteRepo = new VoteResultRepository();
    dd($voteRepo->getByElectionPosition('test123')->count());
    dd($voteRepo->create([
        'ElectionPosition' => 'test12345678',
        'Candidate' => 'candidate12345678',
        'Yes' => 2,
        'No' => 3
    ]));
});

Route::get('/at', function(){
    $voteservice = new VoteService();
    dd($voteservice->CalculateVoteResult(ElectionPosition::find(1)->UID));
    dd($voteservice->CalculateMultipleResult(ElectionPosition::find(1)));
    $str='eyJpdiI6InlZbElldURsT3E1TkJzQVdHbmpVUFE9PSIsInZhbHVlIjoiOHlCTE0rOS93Tk9heVpqU0lBVWo2TVJWd3hhYXR2NmlXZ3l1R1Z4dDBzZStoemMrM09nYXJvV25qL0dkbm1kb2szL3l5ZDErNndIT1ZNNzQyZTFEWWlabGNBTGljb2V5TEVBd1RDL2loMmFwS0o1RHQ3ckdMcG9kVVZRclQ0RlJYeXRuSTlYUnZ5L0Zib2pWOWl1SGNRPT0iLCJtYWMiOiJkNjZlMjJmMzI0YmYzMzc0NjM5MjM3MzcyNDcwN2RiNDE5NzZhYzViODc1NmY4YmE2NTAzNzg5YzY2NGM1NDdmIn0=';
    try{
        $voteservice->vote($str, 'FyUUNWLaAiAPhbs0ZNHlRAd1I5WvXJ28B0A1UnX2ftc=');
    }
    catch(RuntimeException $exception)
    {
        dd($exception);
    }
});