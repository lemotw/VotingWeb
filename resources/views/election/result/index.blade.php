@extends('election.layout')

@section('title', $title)
@section('content')
<div class="maincontain">
    <div class="conbine">
        <div class="tool">
            <tr>
                <td><a href="">該選舉所有結果</a></td>
            </tr>
        </div>
    <div class="table">

<table id="tt">
    <tr>
        <th>id</th>
        <th>職位名稱</th>
        <th id="selectAll">結果</th>
    </tr>

    @foreach($positions as $position)
    <tr election_position="{{ $position->id }}">
        <td>{{ $position->id }}</td>
        <td>{{ $position->Name }}</td>
        <td>
            <a href="{{ route('election.position.result.page', ['id'=>$position->id]) }}">職位結果</a>
        </td>
    </tr>
    @endforeach
</table>

@endsection