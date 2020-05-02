@extends('election.layout')

@section('title', $title)
@section('content')
    <div class="conbine">
        <div class="tool">
            <tr>
                <td><a href="{{ route('election.add.page') }}">新增</a></td>
                <td><a onclick='delete_election()'>刪除</a></td>
            </tr>
        </div>
        <div class="table">
            <table  id="tt">
                
                <tr>
                    <th>名稱</th>
                    <th>註冊時間</th>
                    <th>投票時間</th>
                    <th>管理</th>
                    <th id="selectAll">修改</th>
                </tr>

                @foreach($elections as $election)
                <tr election="{{ $election->id }}">
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
                        <a href="{{ route('election.position.index.page', [ 'id'=>$election->id ]) }}">職位管理</a>
                    </td>
                    <td><a href="{{ route('election.modify.page', [ 'id'=>$election->id ]) }}">修改</a></td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection

@section('script-include')
<script>

function delete_election() {
    var election_select = $('.tr-select');
    var id_list = [];

    if(!confirm('您確定要刪除這些 選舉 嗎？'))
        return;

    for(var i=0; i<election_select.length ;++i)
        id_list.push($(election_select[i]).attr('election'));

    $.ajax({
        type: 'POST',
        async: true,
        url: "{{ route('election.delete.post') }}",
        data: {
            id: id_list
        },
        success: function() {
            location.reload();
        },
        error: function(e) {
            console.log(e);
        }
    })
}

</script>
@endsection