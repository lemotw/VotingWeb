@extends('election.layout')

@section('title', $title)
@section('content')
    <div class="maincontain">
        <div class="conbine">

            <div class="table">
                <table  id="tt">
                    <tr>
                        <th>名字</th>
                        <th>account</th>
                        <th>狀態</th>
                        <th>檔案</th>
                        <th>審核</th>
                    </tr>

                    @foreach($election_position->CandidateElectionPosition as $candidateElectionPosition)
                    <tr>
                        <td>{{ $candidateElectionPosition->CandidateEntity->Name }}</td>
                        <td>{{ $candidateElectionPosition->CandidateEntity->account}}</td>
                        <td>
                            @if($candidateElectionPosition->CandidateSet)
                                <label style="color:green">核可</label>
                            @else
                                <label style="color:gray">審核中</label>
                            @endif
                        </td>
                        <td><a href="https://www.google.com">檔案</a></td>
                        <td>
                            <a href="{{ route('election.candidate.check', ['id'=>$candidateElectionPosition->id]) }}">核可</a>
                            <a href="{{ route('election.candidate.uncheck', ['id'=>$candidateElectionPosition->id]) }}">移除核可</a>
                        </td>
                    </tr>
                    @endforeach
                </table>

            </div>
        </div>
    </div>
@endsection