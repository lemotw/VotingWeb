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
Route::get('/', function () {
    return view('welcome');
});

Route::get('/res', 'ElectionController@ElectionPositionResult_Page')->name('election.position.result.page');
Route::get('/es', 'ElectionController@ElectionResult_Page');
Route::get('/ele/res', 'ElectionController@ElectionResultIndex_Page')->name('election.result.index.page');

use App\Models\Election\ElectionPosition;
use App\Service\Vote\VoteService;
use App\Service\Election\ElectionService;
use App\Models\Vote\VoteResult;
use App\Models\Election\CandidateElectionPosition;

Route::get('test', function() {

    $cand = CandidateElectionPosition::find(12);
    dd($cand->CandidateEntity->image_path());
    dd($cand->CandidateImage);

    $es = new ElectionService();

    return $es->CandidateElectionPositionDownload(9);
    dd(CandidateElectionPosition::find(1)->created_at->format('Y-m-d_H-i-s'));

    dd(App\Contracts\Utility\CandidateStatus::remedy_file);
    dd(CandidateElectionPosition::find(9)->CandidateSet);
    $vs = new VoteService();

    $token = 'kkkkkkkk123qqq';
    dd($vs->GetVotes($token));

    return view('election.result.election_position');
    // dd($vs->CalculateVoteResult(8));
    $e = ElectionPosition::find(8);
    $vs_list = VoteResult::where('ElectionPosition', $e->UID)->get();
    dd($vs_list[0]);

});

Route::Group(['prefix' => 'admin'], function() {

    Route::get('/', 'ElectionController@welcome')->name('admin.welcome.page');
    Route::get('/login', 'AdminController@AdminLogin_Page')->name('admin.login.page');
    Route::post('/login', 'AdminController@AdminLogin_Post')->name('admin.login.post');
    Route::get('/logout', 'AdminController@AdminLogout')->name('admin.logout');

    Route::get('/user/modify', 'AdminController@UserModify_Page')->name('admin.user.modify.page');
    Route::post('/user/modify', 'AdminController@UserModify_Post')->name('admin.user.modify.post');

    Route::Group(['middleware' => ['admin']], function() {
        Route::get('/users', 'AdminController@UserList_Page')->name('admin.users.page');
        Route::get('/user/add', 'AdminController@UserAdd_Page')->name('admin.user.add.page');
        Route::post('/user/add', 'AdminController@UserAdd_Post')->name('admin.user.add.post');
        Route::post('/user/delete', 'AdminController@UserDelete_Post')->name('admin.user.delete.post');
        Route::post('/user_role/change', 'AdminController@UserRoleChange_Post')->name('admin.user_role.change.post');
        Route::get('/reset_password', 'AdminController@UserResetPassword')->name('admin.user.reset_password');
    });

    Route::Group(['prefix' => 'election', 'middleware' => ['maintainer']], function() {
        Route::get('/', 'ElectionController@index')->name('election.index.page');
        Route::get('add', 'ElectionController@ElectionAdd_Page')->name('election.add.page');
        Route::post('add', 'ElectionController@ElectionAdd_Post')->name('election.add.post');
        Route::get('modify', 'ElectionController@ElectionModify_Page')->name('election.modify.page');
        Route::post('modify', 'ElectionController@ElectionModify_Post')->name('election.modify.post');
        Route::post('delete', 'ElectionController@ElectionDelete_Post')->name('election.delete.post');

        Route::Group(['prefix' => 'candidate'], function() {
            Route::get('check', 'ElectionController@CandidateCheckIndex_Page')->name('election.candidate.check.page');
            Route::get('change_status', 'ElectionController@CandidateStatus_Change')->name('election.candidate.status_change.post');
            Route::get('download', 'ElectionController@CandidateFile_Download')->name('election.candidate.download');
        });

        Route::Group(['prefix' => 'position'], function() {
            Route::get('/', 'ElectionController@ElectionPositionIndex_Page')->name('election.position.index.page');
            Route::get('add', 'ElectionController@ElectionPositionAdd_Page')->name('election.position.add.page');
            Route::post('add', 'ElectionController@ElectionPositionAdd_Post')->name('election.position.add.post');
            Route::get('modify', 'ElectionController@ElectionPositionModify_Page')->name('election.position.modify.page');
            Route::post('modify', 'ElectionController@ElectionPositionModify_Post')->name('election.position.modify.post');
            Route::post('delete', 'ElectionController@ElectionPositionDelete_Post')->name('election.position.delete.post');
        });
    });

    Route::Group(['prefix' => 'position', 'middleware' => ['maintainer']], function() {
        Route::get('/', 'ElectionController@PositionIndex_Page')->name('position.index.page');
        Route::get('add', 'ElectionController@PositionAdd_Page')->name('position.add.page');
        Route::post('add', 'ElectionController@PositionAdd_Post')->name('position.add.post');
        Route::get('modify', 'ElectionController@PositionModify_Page')->name('position.modify.page');
        Route::post('modify', 'ElectionController@PositionModify_Post')->name('position.modify.post');
        Route::post('delete', 'ElectionController@PositionDelete_Post')->name('position.delete.post');
    });

    Route::Group(['prefix' => 'authtable', 'middleware' => ['auth_table']], function() {
        Route::get('/', 'VoteController@AuthtableIndex_Page')->name('authtable.index.page');
        Route::get('table', 'VoteController@Authtable_Page')->name('authtable.table.page');
        Route::post('auth', 'VoteController@AuthToken')->name('autable.auth.post');
    });

    Route::Group(['prefix' => 'votetable', 'middle' => ['']], function() {
        Route::get('table', 'VoteController@Votetable_Page')->name('votetable.table.page');
        Route::post('getVotes', 'VoteController@GetVotes')->name('votetable.getVotes.post');
        Route::post('vote', 'VoteController@vote')->name('votetable.vote.post');
    });

});

Route::Group(['prefix' => 'candidate'], function() {
    Route::get('register', 'CandidateController@CandidateRegister_Page')->name('candidate.register.page');
    Route::post('register', 'CandidateController@CandidateRegister_Post')->name('candidate.register.post');

    Route::get('login', 'CandidateController@CandidateLogin_Page')->name('candidate.login.page');
    Route::post('login', 'CandidateController@CandidateLogin_Post')->name('candidate.login.post');
    Route::get('logout', 'CandidateController@CandidateLogout')->name('candidate.logout');
});

Route::Group(['prefix' => 'candidate', 'middleware' => ['candidate_auth']], function() {
    Route::get('/', 'CandidateController@index')->name('candidate.index.page');

    Route::get('modify', 'CandidateController@CandidateModify_Page')->name('candidate.modify.page');
    Route::post('modify', 'CandidateController@CandidateModify_Post')->name('candidate.modify.post');
    Route::post('delete', 'CandidateController@CandidateDelete_Post')->name('candidate.delete.post');
    Route::get('download', 'CandidateController@CandidateElectionPosition_Download')->name('candidate.election_position.download');

    Route::Group(['prefix' => 'image'], function() {
        Route::get('upload', 'CandidateController@CandidateImageUpload_Page')->name('candidate.image.upload.page');
        Route::post('upload', 'CandidateController@CandidateImageUpload_Post')->name('candidate.image.upload.post');
        // Return to index
    });

    Route::Group(['prefix' => 'election_position'], function() {
        Route::get('/', 'CandidateController@CandidateElectionPositionIndex_Page')->name('candidate.election_position.index.page');
        Route::get('view', 'CandidateController@CandidateElectionPositionView_Page')->name('candidate.election_position.view.page');
        Route::get('add', 'CandidateController@CandidateElectionPositionAdd_Page')->name('candidate.election_position.add.page');
        Route::post('add', 'CandidateController@CandidateElectionPositionAdd_Post')->name('candidate.election_position.add.post');
        // Return to index
        Route::get('modify', 'CandidateController@CandidateElectionPositionModify_Page')->name('candidate.election_position.modify.page');
        Route::post('modify', 'CandidateController@CandidateElectionPositionModify_Post')->name('candidate.election_position.modify.post');
        // Return to index

        Route::get('file_upload', 'CandidateController@CandidateElectionPositionFileUpload_Page')->name('candidate.election_position.file_upload.page');
        Route::post('file_upload', 'CandidateController@CandidateElectionPositionFileUpload_Post')->name('candidate.election_position.file_upload.post');
    });
});