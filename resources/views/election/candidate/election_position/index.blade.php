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
                    @if($candidateElectionPosition->CandidateEntity == NULL)
                    @continue
                    @endif
                    <tr>
                        <td>{{ $candidateElectionPosition->CandidateEntity->Name }}</td>
                        <td>{{ $candidateElectionPosition->CandidateEntity->account}}</td>
                        <td>
                            @switch($candidateElectionPosition->CandidateStatus)
                                @case(App\Contracts\Utility\CandidateStatus::uncheck)
                                    <label style="color:gray">尚未核可</label>
                                    @break
                                @case(App\Contracts\Utility\CandidateStatus::check)
                                    <label style="color:green">核可</label>
                                    @break
                                @case(App\Contracts\Utility\CandidateStatus::remedy_file)
                                    <label style="color:#EFBB24">補繳文件</label>
                                    @break
                                @default
                                    <label style="color:gray">尚未核可</label>
                                    @break
                            @endswitch
 
                        </td>
                        <td><a href="{{ route('election.candidate.download', ['id'=>$candidateElectionPosition->id]) }}">檔案</a></td>
                        <td>
                            <a href="{{ route('election.candidate.status_change.post', ['id'=>$candidateElectionPosition->id, 'status' => App\Contracts\Utility\CandidateStatus::check]) }}">核可</a>
                            <a href="{{ route('election.candidate.status_change.post', ['id'=>$candidateElectionPosition->id, 'status' => App\Contracts\Utility\CandidateStatus::remedy_file]) }}">補件</a>
                            <a href="{{ route('election.candidate.status_change.post', ['id'=>$candidateElectionPosition->id, 'status' => App\Contracts\Utility\CandidateStatus::uncheck]) }}">尚未核可</a>
                        </td>
                    </tr>
                    @endforeach
                </table>

            </div>
        </div>
    </div>
@endsection