<?php

namespace App\Http\Controllers;

use App;
use Session;

use Exception;
use RuntimeException;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Service\Vote\VoteService;
use App\Service\Election\ElectionService;
use App\Service\Election\CandidateService;
use App\Service\Formatter\GrowlMessenger;

class ElectionController extends Controller
{

    /**
     * Election Service.
     * 
     * @var ElectionService
     */
    protected $electionService;

    /**
     * Vote Service
     */
    protected $voteService;

    /**
     * Create a new ElectionController.
     * 
     * @return void
     */
    public function __construct()
    {
        $this->electionService = new ElectionService();
        $this->voteService = new VoteService();
    }


    /**
     * welcome page.
     */
    public function welcome()
    {
        $title = '歡迎頁面';
        return view('election.welcome', compact('title'));
    }

    /**
     * Index of all elections page.
     */
    public function index()
    {
        $title = 'Election 管理頁面';
        $elections = $this->electionService->ElectionSearch();

        return view('election.index', compact('elections', 'title'));
    }

    /**
     * Election Add page.
     */
    public function ElectionAdd_Page()
    {
        $title = 'Election 新增';
        $subtitle = '新增';
        $postURL = route('election.add.post');
        return view('election.edit', compact('title', 'subtitle', 'postURL'));
    }

    /**
     * Election Add post.
     */
    public function ElectionAdd_Post(Request $request)
    {
        /*
            {
                "Name": "ElectionName",
                "StartTime": "2020/02/20",
                "EndTime": "2020/07/20",
                "RegisterStart": "2020/05/20",
                "RegisterEnd": "2020/07/20",
                "VoteStart": "2020/06/20",
                "VoteEnd": "2020/07/20",
                "ElectionPosition":[
                    {
                        "Name":"系學會長",
                        "Election":1,
                        "Position":2,
                    },
                    {
                        "Name":"學生會",
                        "Election":5,
                        "Position":2
                    }
                ]
            }
        */
        try{
            $election = $this->electionService->ElectionAdd($request->only([
                'Name', 'StartTime', 'EndTime',
                'RegisterStart', 'RegisterEnd',
                'VoteStart', 'VoteEnd', 'ElectionPosition'
            ]));
        } catch(RuntimeException $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }

        return redirect()->route('election.index.page')->with('msg', '選舉新增成功!!');
    }

    /**
     * Election modify page.
     */
    public function ElectionModify_Page(Request $request)
    {
        $title = 'Election 修改';
        $subtitle = '修改';
        $postURL = route('election.modify.post');

        // Check id
        $id = $request->input('id');
        if(!$id)
            return response(404);

        // Get and check election
        $election = $this->electionService->ElectionGet($id);
        if(!$election)
            return response(404);

        return view('election.edit', compact('title', 'subtitle', 'postURL', 'election'));
    }

    /**
     * Election modify post.
     */
    public function ElectionModify_Post(Request $request)
    {
        try{
            $this->electionService->ElectionModify($request->only([
                'id','Name', 'StartTime', 'EndTime',
                'RegisterStart', 'RegisterEnd',
                'VoteStart', 'VoteEnd'
            ]));
        } catch(RuntimeException $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }

        return redirect()->route('election.index.page')->with('msg', '選舉資訊修改成功!!');
    }

    /**
     * Election delete post.
     */
    public function ElectionDelete_Post(Request $request)
    {
        try{
            if(!$request->input('id'))
                return redirect()->back()->withErrors('ID未設定');

            if( is_array($request->input('id')) )
                foreach( $request->input('id') as $id)
                    $this->electionService->ElectionDelete($id);
            else
                $this->electionService->ElectionDelete($request->input('id'));
        } catch(RuntimeException $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }



        return response(200);
    }
    
    /**
     * Index of election's positions
     */
    public function ElectionPositionIndex_Page(Request $request)
    {
        if(!$request->input('id'))
            return response(404);

        $ElectionId = $request->input('id');
        $election = $this->electionService->ElectionGet($request->input('id'));
        $title = $election->Name.'Positions 管理頁面';
        $positions = $election->ElectionPositionEntity;

        return view('election.election_position.index', compact('title', 'positions', 'ElectionId'));
    }

    public function ElectionPositionAdd_Page(Request $request)
    {
        if(!$request->input('ElectionId'))
            return response(404);
        $election = $this->electionService->ElectionGet($request->input('ElectionId'));

        $title = 'Election Position 新增';
        $subtitle = '新增';

        $ElectionId = $request->input('ElectionId');
        $positions = $this->electionService->Position();
        
        return view('election.election_position.edit', compact('title', 'subtitle', 'ElectionId', 'positions'));
    }

    public function ElectionPositionAdd_Post(Request $request)
    {
        $ElectionId = 0;

        try{
            $this->electionService->ElectionPositionAdd($request->only([
                'Name', 'Position', 'Election'
            ]));
            $ElectionId = $request->input('Election');
        } catch(RuntimeException $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }

        return redirect()->route('election.position.index.page', ['id' => $ElectionId])->with('msg', '選舉職位新增成功!');
    }

    public function ElectionPositionModify_Page(Request $request)
    {
        if(!$request->input('id'))
            return response(404);

        $title = 'Election Position 修改';
        $subtitle = '修改';

        $position = $this->electionService->ElectionPositionGet($request->input('id'));
        $positions = $this->electionService->Position();

        return view('election.election_position.edit', compact('title', 'subtitle', 'position', 'positions'));
    }

    public function ElectionPositionModify_Post(Request $request)
    {
        $ElectionId = 0;

        try{
            $this->electionService->ElectionPositionModify($request->only([
                'id', 'Name', 'Election', 'Position'
            ]));
            $ElectionId = $request->input('Election');
        } catch(RuntimeException $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }


        return redirect()->route('election.position.index.page', ['id' => $ElectionId])->with('msg', '選舉職位修改成功!');
    }

    public function ElectionPositionDelete_Post(Request $request)
    {
        try{
            if(!$request->input('id'))
                return redirect()->back()->withErrors('ID 未設定');

            if( is_array($request->input('id')) )
                foreach( $request->input('id') as $id)
                    $this->electionService->ElectionPositionDelete($id);
            else
                $this->electionService->ElectionPositionDelete($request->input('id'));

        } catch(RuntimeException $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }

        return response(200);
    }

    public function PositionIndex_Page()
    {
        $title = 'Position 管理頁面';
        $positions = $this->electionService->Position();

        return view('election.position.index', compact('title', 'positions'));
    }

    public function PositionAdd_Page(Request $request)
    {
        $title = 'Election Position 新增';
        $subtitle = '新增';
        $postURL = route('position.add.post');
        
        return view('election.position.edit', compact('title', 'subtitle'));
    }

    public function PositionAdd_Post(Request $request)
    {
        try{
            $this->electionService->PositionAdd($request->only([
                'Name', 'Unit', 'QualifyRegex'
            ]));
        } catch(RuntimeException $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }

        return redirect()->route('position.index.page')->with('msg', '職位新增成功!!');
    }

    public function PositionModify_Page(Request $request)
    {
        if(!$request->input('id'))
            return response(404);

        $title = 'Position 職位修改';
        $subtitle = '修改';
        $postURL = route('position.modify.post');
        $position = $this->electionService->PositionGet($request->input('id'));

        return view('election.position.edit', compact('title', 'subtitle', 'position'));
    }

    public function PositionModify_Post(Request $request)
    {
        try{
            $this->electionService->PositionModify($request->only([
                'id', 'Name', 'Unit', 'QualifyRegex'
            ]));
        } catch(RuntimeException $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }

        return redirect()->route('position.index.page')->with('msg', '職位修改成功!');
    }

    public function PositionDelete_Post(Request $request)
    {
        try{

            if(!$request->input('id'))
                return redirect()->back()->withErrors('ID 未設定');

            if( is_array($request->input('id')) )
                foreach( $request->input('id') as $id)
                    $this->electionService->PositionDelete($id);
            else
                $this->electionService->PositionDelete($request->input('id'));

        } catch(RuntimeException $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }

        return response(200);
    }

    /**
     * List of election result.
     * 
     * @param Request $request
     */
    public function ElectionResultIndex_Page(Request $request) {
        if(!$request->input('id'))
            return response(404);

        $ElectionId = $request->input('id');
        $election = $this->electionService->ElectionGet($request->input('id'));
        $title = $election->Name.' 結果頁面';
        $positions = $election->ElectionPositionEntity;

        return view('election.result.index', compact('title', 'positions', 'ElectionId'));
    }

    /**
     * Get single Election Position Vote result.
     * 
     * @param Request $request
     */
    public function ElectionPositionResult_Page(Request $request) {

        $election_position = $this->electionService->ElectionPositionGet($request->input('id'));

        $vote_count = 263;
        $title = $election_position->Name . ' 選舉結果';

        $vote_result = $this->voteService->CalculateVoteResult($election_position->id);

        $broken = 0;
        $vote_sum = 0;
        foreach($vote_result as $result) {
            $vote_sum += $result->VoteCount;

            if($result->Candidate == 'broken')
                $broken = $result->VoteCount;
        }

        if($vote_result->count() > 1) {
            return view('election.result.election_position', compact('title', 'vote_result', 'broken', 'vote_count', 'vote_sum', 'election_position'));
        } else {
            $vote_result = $vote_result[0];
            return view('election.result.election_position_yn', compact('title', 'vote_result', 'vote_count'));
        }
    }

    /**
     * Get single Election Position Vote result.
     * 
     * @param Request $request
     */
    public function ElectionResult_Page(Request $request) {
        if(!$request->input('id'))
            return response(404);

        $election = $this->electionService->ElectionGet($request->input('id'));
        $title = $election->Name . ' 選舉結果';
        $positions = $election->ElectionPositionEntity;

        $vote_result_list = [];

        foreach($positions as $position) {
            $vote_count = 263;
            $vote_result = $this->voteService->CalculateVoteResult($position->id);

            $broken = 0;
            $vote_sum = 0;
            foreach($vote_result as $result) {
                $vote_sum += $result->VoteCount;

                if($result->Candidate == 'broken')
                    $broken = $result->VoteCount;
            }

            array_push($vote_result_list, [
                'vote_result' => $vote_result,
                'vote_count' => $vote_count,
                'vote_sum' => $vote_sum,
                'broken' => $broken
            ]);
        }


        return view('election.result.election', compact('title', 'vote_result_list'));
    }


    /**
     * Manage Candidate about Election Position.
     */
    public function CandidateCheckIndex_Page(Request $request)
    {
        if(!$request->input('id'))
            return response(404);

        $election_position = $this->electionService->ElectionPositionGet($request->input('id'));
        $title = $election_position->Name.'候選人管理';

        return view('election.candidate.election_position.index', compact('title', 'election_position'));
    }

    public function CandidateStatus_Change(Request $request) {
        $id = $request->input('id');
        $status = $request->input('status');

        $candidate_ep = $this->electionService->CandidateElectionPositionGet($id);
        $candidate_ep->CandidateStatus = $status;
        $candidate_ep->save();

        return back()->with('msg', '更改成功');
    }

    public function CandidateFile_Download(Request $request) {
        try{
            return $this->electionService->CandidateElectionPositionDownload($request->input('id'));
        } catch (Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }
}