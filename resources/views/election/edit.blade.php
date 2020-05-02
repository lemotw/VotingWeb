@extends('election.layout')

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://unpkg.com/gijgo@1.9.11/js/gijgo.min.js" type="text/javascript"></script>
<link href="https://unpkg.com/gijgo@1.9.11/css/gijgo.min.css" rel="stylesheet" type="text/css" />
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
        flex-direction: row;
        align-items:center;
        margin: 10px;
    }
    
    .row > input{
        width:200px;
    }
    .row > label{
        margin:10px;
        font-size: 1.3em;
    }

</style>

@section('title', $title)
@section('content')
<div class="back-inner">
    <h4>{{ $subtitle }}</h4>
        <form action="{{ isset($postURL)?$postURL:NULL }}" method="POST" id='form_main'>

            @csrf
            @if(isset($election))
            <input type="hidden" name="id" value="{{ $election->id }}">
            @endif

            <div class="row">
                <label>選舉名稱:</label> 
                <input style="width:200px;" type="text" name="Name"
                value="{{ isset($election)?$election->Name:NULL }}">
            </div>

            <div class="row">
                <label>開始時間:</label>
                <input style="width:200px;" name="StartTime" id="StartTime" 
                value="{{ isset($election)?$election->StartTime:NULL }}"/>
            </div>

            <div class="row">
                <label>結束時間:</label>
                <input style="width:200px;" name="EndTime" id="EndTime"
                value="{{ isset($election)?$election->EndTime:NULL }}"/>
            </div>

            <div class="row">
                <label>登記開始:</label>
                <input style="width:200px;" name="RegisterStart" id="RegisterStart"
                value="{{ isset($election)?$election->RegisterStart:NULL }}"/>
            </div>

            <div class="row">
                <label>登記結束:</label>
                <input style="width:200px;" name="RegisterEnd" id="RegisterEnd"
                value="{{ isset($election)?$election->RegisterEnd:NULL }}"/>
            </div>

            <div class="row">
                <label>投票開始:</label>
                <input style="width:200px;" name="VoteStart" id="VoteStart"
                value="{{ isset($election)?$election->VoteStart:NULL }}"/>
            </div>

            <div class="row">
                <label>投票結束:</label>
                <input style="width:200px;" name="VoteEnd" id="VoteEnd"
                value="{{ isset($election)?$election->VoteEnd:NULL }}"/>
            </div>

            <div class="row">
                <input style="width:100px;" type="submit" value="送出">
            </div>
        </form>
    </div>
</div>

<script>
    $('#StartTime').datetimepicker({ footer: true, format: 'yyyy-mm-dd HH:MM' }); 
    $('#EndTime').datetimepicker({ footer: true, format: 'yyyy-mm-dd HH:MM' }); 
    $('#RegisterStart').datetimepicker({ footer: true, format: 'yyyy-mm-dd HH:MM' }); 
    $('#RegisterEnd').datetimepicker({ footer: true, format: 'yyyy-mm-dd HH:MM' }); 
    $('#VoteStart').datetimepicker({ footer: true, format: 'yyyy-mm-dd HH:MM' }); 
    $('#VoteEnd').datetimepicker({ footer: true, format: 'yyyy-mm-dd HH:MM' }); 
</script>
@endsection 