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
            <input type="hidden" name="id" value="{{ isset($position)?$position->id:NULL }}">
            @csrf
            <div class="row">
                <label>職位名稱:</label> 
                <input style="width:200px;" type="text" name="Name"
                value="{{ isset($position)?$position->Name:NULL }}">
            </div>

            <div class="row">
                <label>單位名稱:</label> 
                <input style="width:200px;" type="text" name="Unit"
                value="{{ isset($position)?$position->Unit:NULL }}">
            </div>

            <div class="row">
                <label>職位條件:</label> 
                <textarea name="QualifyRegex" style="resize:none" width="200px">
                    @if(isset($position))
                        {{ $position->QualifyRegex }}
                    @endif
                </textarea>
                <!-- <input style="width:200px;" type="text" name="QualifyRegex" value=""> -->
            </div>

            <div class="row">
                <input style="width:100px;" type="submit" value="送出">
            </div>
        </form>
    </div>
</div>

@endsection 