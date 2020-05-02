<?php

namespace App\Http\Controllers;

use RuntimeException;
use App\User;
use App\Service\Formatter\JsonResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Service\Vote\VoteService;
use App\Service\Token\TokenService;
use App\Service\Election\ElectionService;

class VoteController extends Controller
{
    /**
     * Vote Service.
     * 
     * @var VoteService
     */
    protected $voteService;

    /**
     * Token Service
     * 
     * @var TokenService
     */
    protected $tokenService;

    /**
     * Create a new ElectionController.
     * 
     * @return void
     */ 
    public function __construct()
    {
        $this->voteService = new VoteService();
        $this->tokenService = new TokenService();
        $this->electionService = new ElectionService();
    }

    /**
     * Select Election to enter auth table.
     * 
     * @param Request $request
     */
    public function AuthtableIndex_Page()
    {
        $title = '驗票台選擇頁面';
        $elections = $this->electionService->ElectionSearch();
        return view('election.authtable.index', compact('title', 'elections'));
    }

    /**
     * Provide Auth table.
     * 
     * @param Request $request
     */
    public function Authtable_Page(Request $request)
    {
        if($request->input('id') == NULL)
            return redirect()->back()->withErrors(['您未選擇選舉場次!']);

        $election = $this->electionService->ElectionGet($request->input('id'));
        $key = $this->tokenService->getTableKey(Auth::user());

        return view('election.authtable.table', compact('key', 'election'));
    }

    /**
     * Provide Vote table.
     * 
     * @param Request $request
     */
    public function Votetable_Page(Request $request)
    {
        $key = $this->tokenService->getTableKey(Auth::user());

        return view('election.votetable.table', compact('key'));
    }

    /**
     * Get Votes.
     * 
     * @param Request $request
     * @return string
     */
    public function GetVotes(Request $request)
    {
        if($request->input('token') == NULL)
            return response(404);

        try{
            $vote_result = $this->voteService->GetVotes($request->input('token'));
        } catch(RuntimeException $e) {
            return JsonResponser::Response(false, [], 'error', $e->getMessage());
        }

        return JsonResponser::Response(true, $vote_result);
    }

    /**
     * Vote
     * 
     * @param Request $request
     * @return string
     */
    public function Vote(Request $request)
    {
        return $this->voteService->vote($request->input('enStr'), User::find(1)->table_key);
    }

    /**
     * Auth Token.
     * 
     * @param Request $request
     * @return string
     */
    public function AuthToken(Request $request)
    {
        return $this->tokenService->authVoter($request->input('str'), User::find(1)->table_key);
    }
}