@extends('candidate.layout')

<style>
    #form_main{
        padding: 20px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .row{
        display: flex;
        width: 100%;
        margin: 10px;
        flex-direction: row;
        justify-content: space-between;
    }
    
    .row > input{
        width:200px;
    }
    .row > label{
        margin:20px;
        font-size: 1.3em;
    }

    .exp-group{
        display: flex;
        flex-direction: column;
        margin:20px;
    }
</style>

@section('title', $title)
@section('content')
<div class="back-inner">
    <h4>{{ $subtitle }}</h4>

        <div class="row">
            <label>職位名稱:</label> 
            <label>{{ $position->ElectionPositionEntity->Name }}</label> 
        </div>

        <div class="row">
            <label>檔案下載:</label>
            <label><a href="{{ route('candidate.election_position.download', ['id' => $position->id]) }}">download</a></label> 
        </div>

        <div class="row">
            <label>經歷: </label>

            <div class="exp-group">
                @foreach(explode(';', $position->exp) as $exp)
                    <p>{{ $exp }}</p>
                @endforeach
            </div>
        </div>

    </div>
</div>

@endsection 