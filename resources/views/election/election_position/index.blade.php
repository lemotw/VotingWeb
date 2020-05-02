

@extends('election.layout')

@section('title', $title)
@section('content')
<div class="maincontain">
    <div class="conbine">
        <div class="tool">
            <tr>
                <td><a href="{{ route('election.position.add.page', ['ElectionId' => $ElectionId]) }}">新增</a></td>
                <td><a onclick="delete_election_position()">刪除</a></td>
            </tr>
        </div>
    <div class="table">

<table id="tt">
    <tr>
        <th>id</th>
        <th>職位名稱</th>
        <th id="selectAll">管理</th>
    </tr>

    @foreach($positions as $position)
    <tr election_position="{{ $position->id }}">
        <td>{{ $position->id }}</td>
        <td>{{ $position->Name }}</td>
        <td>
            <a href="{{ route('election.position.modify.page', ['id' => $position->id]) }}">資訊修改</a>
            <a href="{{ route('election.candidate.check.page', ['id' => $position->id]) }}">候選人管理</a>
        </td>
    </tr>
    @endforeach
</table>

@endsection

@section('script-include')
<script>

function delete_election_position() {
    var election_position_select = $('.tr-select');
    var id_list = [];

    if(!confirm('您確定要刪除這些 選舉職位 嗎？'))
        return;

    for(var i=0; i<election_position_select.length ;++i)
        id_list.push($(election_position_select[i]).attr('election_position'));

    console.log(id_list);
    $.ajax({
        type: 'POST',
        async: true,
        url: "{{ route('election.position.delete.post') }}",
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