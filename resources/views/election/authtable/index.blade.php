@extends('election.layout')

@section('title', $title)
@section('content')
    <div class="conbine">
        <div class="table">
            <table  id="tt">
                
                <tr>
                    <th>名稱</th>
                    <th>註冊時間</th>
                    <th>投票時間</th>
                    <th>驗票台</th>
                </tr>

                @foreach($elections as $election)
                <tr>
                    <td>{{ $election->Name }}</td>
                    <td>
                        {{ date('Y/m/d H:i', strtotime($election->RegisterStart)) }}
                        <br>
                        {{ date('Y/m/d H:i', strtotime($election->RegisterEnd)) }}
                    </td>
                    <td>
                        {{ date('Y/m/d H:i', strtotime($election->VoteStart)) }}
                        <br>
                        {{ date('Y/m/d H:i', strtotime($election->VoteEnd)) }}
                    </td>
                    <td>
                        <a href="{{ route('authtable.table.page', [ 'id'=>$election->id ]) }}">前往驗票台</a>
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection