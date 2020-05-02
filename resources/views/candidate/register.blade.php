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
        width: 100%;
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
        <form id="form_main" action="{{ route('candidate.register.post') }}" method="POST">
            @csrf
            <div class="row">
                <label>名稱:</label> 
                <input style="width:200px;" type="text" name="Name" placeholder="名稱">
            </div>

            <div class="row">
                <label>帳號:</label>
                <input style="width:200px;" type="text" name="account" placeholder="帳號">
            </div>

            <div class="row">
                <label>密碼:</label>
                <input style="width:200px;" type="password" name="password" placeholder="密碼">
            </div>

            <div clas="row">
                <input style="width:100px;" type="submit" value="送出">
            </div>
        </form>
    </div>
</div>
@endsection