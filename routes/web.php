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
use Illuminate\Support\Facades\Auth;
use Illuminate\Cookie\CookieJar;

use App\Models\Election\Election;
use App\Models\Election\Candidate;
use App\Models\Election\ElectionPosition;
use App\Models\Vote\VoteRecord;
use App\Service\Election\CR;
use App\Repository\Election\PositionRepository;
use App\Repository\Election\ElectionPositionRepository;
use App\Repository\Election\ElectionRepository;
use App\Service\Election\ElectionService;
use App\Service\Election\CandidateRegisterGuard;
use App\Repository\Election\CandidateRepository;
use App\Repository\Election\CandidateRegisterRepository;
use App\Repository\Vote\VoteResultRepository;
use App\Service\Election\CandidateService;

Route::post('ElectionAdd', 'ElectionController@ElectionAdd_Post');
Route::post('ElectionModify', 'ElectionController@ElectionModify_Post');
Route::post('ElectionDelete', 'ElectionController@ElectionDelete_Post');