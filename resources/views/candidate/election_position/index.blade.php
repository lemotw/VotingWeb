@extends('candidate.layout')

@section('title', $title)
@section('content')
    <div class="maincontain">
        <div class="conbine">
            <div class="tool">
                <tr>
                    <td><a href="{{ route('candidate.election_position.add.page') }}">新增</a></td>
                    <td><a href="">刪除</a></td>
                </tr>
            </div>
            <div class="table">

                <table  id="tt">
                    
                    <tr>
                        <th>職位名稱</th>
                        <th>職位狀態</th>
                        <th>職位檢視</th>
                        <th>職位管理</th>
                    </tr>

                    @foreach($candidate->CandidateElectionPositions as $position)
                    @if($position->ElectionPositionEntity == NULL)
                    @continue
                    @endif
                    <tr>
                        <td>{{ $position->ElectionPositionEntity->Name }}</td>
                        <td>
                            @switch($position->CandidateStatus)
                                @case(App\Contracts\Utility\CandidateStatus::uncheck)
                                    <label style="color:gray">尚未核可</label>
                                    @break
                                @case(App\Contracts\Utility\CandidateStatus::check)
                                    <label style="color:green">核可</label>
                                    @break
                                @case(App\Contracts\Utility\CandidateStatus::remedy_file)
                                    <label style="color:#EFBB24">補繳文件</label>
                                    @break
                            @endswitch
                        </td>
                        <td><a href="{{ route('candidate.election_position.view.page', ['id'=>$position->id]) }}">檢視</a></td>
                        <td>
                            <a href="{{ route('candidate.election_position.modify.page', ['id'=>$position->id]) }}">編輯</a>
                            <a href="{{ route('candidate.election_position.file_upload.page', ['id'=>$position->id]) }}">檔案上傳</a>
                        </td>
                    </tr>
                    @endforeach
                </table>

            </div>
        </div>
    </div>
@endsection