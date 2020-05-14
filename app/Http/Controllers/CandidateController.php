<?php

namespace App\Http\Controllers;

use Session;
use Carbon\Carbon;

use Exception;
use RuntimeException;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Service\Election\CandidateService;

class CandidateController extends Controller
{

    /**
     * Candidate Service.
     * 
     * @var CandidateService
     */
    protected $candidateService;

    /**
     * Create a new ElectionController.
     * 
     * @return void
     */
    public function __construct()
    {
        $this->candidateService = new CandidateService();
    }

    /**
     * Index of all candidate page.
     */
    public function index()
    {
        $title = '根頁面';
        return view('candidate.index', compact('title'));
    }

    /**
     * Candidate Login page.
     * 
     */
    public function CandidateLogin_Page()
    {
        $candidate = $this->candidateService->Candidate();
        if($candidate != NULL)
            return redirect()->route('candidate.index.page')->with('msg', '您已經登入!');

        $title = '候選人登入';

        return view('candidate.login', compact('title'));
    }

    /**
     * Candidate Login post.
     * 
     * @param Request $request
     */
    public function CandidateLogin_Post(Request $request)
    {
        $this->candidateService->CandidateLogin($request->only([
            'account', 'password'
        ]));

        return redirect()->route('candidate.index.page')->with('msg', '登入成功');
    }

    /**
     * Candidate Logout.
     */
    public function CandidateLogout(Request $request)
    {
        $this->candidateService->CandidateLogout();
        return redirect()->route('candidate.login.page')->with('msg', '登出成功!');
    }

    /**
     * Candidate Register page.
     */
    public function CandidateRegister_Page(Request $request)
    {
        $title = 'Candidate 註冊';
        $subtitle = '註冊';
        $positions = $this->candidateService->CandidatePositionSelection();
        return view('candidate.register', compact('title', 'subtitle', 'positions'));
    }

    /**
     * Candidate Register post.
     */
    public function CandidateRegister_Post(Request $request)
    {
        /*
            {
                "Name": "董冠彰",
                "account": "lemotw1024@gmail.com",
                "password": "lemotw2781272",
                "ElectionPosition": 1
            }
        */

        try{
            $candidate = $this->candidateService->CandidateRegister($request->only([
                'Name', 'account', 'password', 'ElectionPosition'
            ]));
        } catch(RuntimeException $e) {
            return back()->withErrors('看來輸入資料不符合規格喔～');
        }

        return redirect()->route('candidate.login.page')->with('msg', '候選人註冊成功!');
    }

    public function cu()
    {
        $can = $this->candidateService->Candidate();
        return $can?$can->toJson():NULL;
    }

    /**
     * CandidateRegister modify page.
     */
    public function CandidateModify_Page(Request $request)
    {
        if(!$candidate = $this->candidateService->Candidate())
            return response(404);

        $title = '候選人資訊編輯';
        $subtitle = '編輯';
        $postURL = route('candidate.modify.post');
        $candidate->update();
        return view('candidate.edit', compact('title', 'subtitle', 'postURL', 'candidate'));
    }

    /**
     * Candidate modify post.
     */
    public function CandidateModify_Post(Request $request)
    {
        try {
            if($request->file('file') != NULL)
                $this->candidateService->CandidateImageUpload($request->file('file'));

            $this->candidateService->CandidateModify($request->only([
                'Candidate', 'Name', 'account'
            ]));
        } catch(RuntimeException $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }

        return redirect()->route('candidate.modify.page')->with('msg', '修改成功');
    }

    /**
     * Show CandidateElectionPosition index page.
     */
    public function CandidateElectionPositionIndex_Page(Request $request)
    {
        if(!$candidate = $this->candidateService->Candidate())
            return response(404);

        $title = '候選人應徵職位列表';
        return view('candidate.election_position.index', compact('title', 'candidate'));
    }

    /**
     * View for CandidateElectionPosition page.
     */
    public function CandidateElectionPositionView_Page(Request $request)
    {
        // Check Ownership
        $candidate = $this->candidateService->Candidate();
        $position = $this->candidateService->CandidateElectionPositionGet($request->input('id'));
        if($candidate->Candidate != $position->Candidate)
            return redirect()->back()->withErrors(['您無權訪問他人資料!']);

        $title = '選舉職位檢視';
        $subtitle = '檢視';

        return view('candidate.election_position.view', compact('title', 'subtitle', 'position'));
    }

    /**
     * Show CandidateElectionPosition add page.
     */
    public function CandidateElectionPositionAdd_Page(Request $request)
    {
        $title = '登記選舉職位';
        $subtitle = '登記';

        $postURL = route('candidate.election_position.add.post');
        $positions = $this->candidateService->CandidatePositionSelection();

        return view('candidate.election_position.edit', compact('title', 'subtitle', 'postURL', 'positions'));
    }

    /**
     * CandidateElectionPosition add post.
     */
    public function CandidateElectionPositionAdd_Post(Request $request)
    {
        try{
            $this->candidateService->CandidateElectionPositionAdd($request->only([
                'ElectionPosition', 'exp'
            ]));
        } catch(RuntimeException $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }

        return redirect()->route('candidate.election_position.index.page')->with('msg', '職位登記成功!');
    }

    /**
     * Show CandidateElectionPosition modify page.
     */
    public function CandidateElectionPositionModify_Page(Request $request)
    {
        // Check ownship
        $candidate = $this->candidateService->Candidate();
        $position = $this->candidateService->CandidateElectionPositionGet($request->input('id'));
        if($position->Candidate != $candidate->Candidate)
            return redirect()->back()->withErrors(['您無權更改或訪問他人資料!']);

        $title = '選舉職位資訊編輯';
        $subtitle = '編輯';

        $postURL = route('candidate.election_position.modify.post');
        $positions = $this->candidateService->CandidatePositionSelection();

        return view('candidate.election_position.edit', compact('title', 'subtitle', 'postURL', 'position', 'positions'));
    }

    /**
     * CandidateElectionPosition modify post.
     */
    public function CandidateElectionPositionModify_Post(Request $request)
    {
        try{
            $this->candidateService->CandidateElectionPositionModify($request->only([
                'id', 'ElectionPosition', 'exp'
            ]));
        } catch(RuntimeException $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }

        return redirect()->route('candidate.election_position.index.page')->with('msg', '登記職位資訊編輯成功!');
    }

    /**
     * Provide candidate to upload file.
     */
    public function CandidateElectionPositionFileUpload_Page(Request $request) {

        $candidate_ep = $this->candidateService->CandidateElectionPositionGet($request->input('id'));

        $title = $candidate_ep->ElectionPositionEntity->Name.' 職位檔案上傳';
        $subtitle = '檔案上傳';

        return view('candidate.election_position.file_upload', compact('title', 'subtitle', 'candidate_ep'));
    }

    /**
     * Deal with candidate file.
     */
    public function CandidateElectionPositionFileUpload_Post(Request $request) {

        try{
            $candidate_ep = $this->candidateService->CandidateElectionPositionGet($request->input('id'));

            $election = $candidate_ep->ElectionPositionEntity->ElectionEntity;
            $candidate = $candidate_ep->CandidateEntity;

            $now = Carbon::now();
            if($now > $election->RegisterEnd) {
                if(!$candidate_ep->fixed_flag) {
                    return back()->withErrors('已操過繳交時間');
                }
            }

            $this->candidateService->CandidateFileUpload($request->file('file'), $candidate_ep);
        } catch (Exception $e) {
            return back()->withErrors('看來輸入資料不符合規格喔～');
        }

        return redirect()->route('candidate.election_position.index.page')->with('msg', '檔案上傳成功');
    }

    /**
     * Election delete post.
     */
    public function CandidateDelete_Post(Request $request)
    {
    }

    /**
     * Candidate image upload page.
     */
    public function CandidateImageUpload_Page()
    {
        $title = '候選人頭貼';
        $subtitle = '頭貼上傳';
        $postURL = route('candidate.image.upload.post');

        return view('candidate.imageUpload', compact('title', 'subtitle', 'postURL'));
    }

    /**
     * Candidate image upload post request.
     */
    public function CandidateImageUpload_Post(Request $request)
    {
        try{
            return $this->candidateService->CandidateImageUpload($request->file('file'));
        } catch(RuntimeException $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    /**
     * CandidateElectionPosition File download.
     */
    public function CandidateElectionPosition_Download(Request $request)
    {
        try {
            $election_position = $this->candidateService->CandidateElectionPositionGet($request->input('id'));
            $candidate = $this->candidateService->Candidate();

            if(!$election_position || $election_position->Candidate != $candidate->Candidate)
                return redirect()->back()->withErrors(['您沒有權限訪問其他候選人資料!']);

            return $this->candidateService->CandidateElectionPositionFileDownload($election_position);
        } catch(Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }
}