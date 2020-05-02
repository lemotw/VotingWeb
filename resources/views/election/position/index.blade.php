@extends('election.layout')

@section('title', $title)
@section('content')
    <div class="maincontain">
        <div class="conbine">
            <div class="tool">
                <tr>
                    <td><a href="{{ route('position.add.page') }}">新增</a></td>
                    <td><a onclick="delete_position()">刪除</a></td>
                </tr>
            </div>
            <div class="table">
                <table id="tt">
                    <tr>
                        <th>id</th>
                        <th>名稱</th>
                        <th>單位</th>
                        <th id="selectAll">修改</th>
                    </tr>

                    @foreach($positions as $position)
                    <tr position="{{ $position->id }}">
                        <td>{{ $position->id }}</td>
                        <td>{{ $position->Name }}</td>
                        <td>{{ $position->Unit }}</td>
                        <td><a href="{{ route('position.modify.page', [ 'id'=>$position->id ]) }}">修改</a></td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@endsection

@section('script-include')
<script>

function delete_position() {
    var position_select = $('.tr-select');
    var id_list = [];

    if(!confirm('您確定要刪除這些 職位 嗎？'))
        return;

    for(var i=0; i<position_select.length ;++i)
        id_list.push($(position_select[i]).attr('position'));

    $.ajax({
        type: 'POST',
        async: true,
        url: "{{ route('position.delete.post') }}",
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