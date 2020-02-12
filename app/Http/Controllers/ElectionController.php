<?php

namespace App\Http\Controllers;

use App;
use Session;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Service\Election\ElectionService;
use App\Service\Election\CandidateService;

class ElectionController extends Controller
{

    /**
     * Election Service.
     * 
     * @var ElectionService
     */
    protected $electionService;

    /**
     * Create a new ElectionController.
     * 
     * @return void
     */
    public function __construct()
    {
        $this->electionService = new ElectionService();
    }

    /**
     * Index of all elections page.
     */
    public function index()
    {

    }

    /**
     * Election Add page.
     */
    public function ElectionAdd_Page(Request $request)
    {

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

        return $this->electionService->ElectionAdd($request->only([
            'Name', 'StartTime', 'EndTime',
            'RegisterStart', 'RegisterEnd',
            'VoteStart', 'VoteEnd', 'ElectionPosition'
        ]))->toJson();
    }

    /**
     * Election modify page.
     */
    public function ElectionModify_Page(Request $request)
    {

    }

    /**
     * Election modify post.
     */
    public function ElectionModify_Post(Request $request)
    {
        return $this->electionService->ElectionModify($request->only([
            'id','Name', 'StartTime', 'EndTime',
            'RegisterStart', 'RegisterEnd',
            'VoteStart', 'VoteEnd'
        ]))->toJson();
    }

    /**
     * Election delete post.
     */
    public function ElectionDelete_Post(Request $request)
    {
        return $this->electionService->ElectionDelete($request->input('id'));
    }
}