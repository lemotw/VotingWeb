@extends('election.layout')
@inject('user_role_cast', 'App\Service\Formatter\UserRoleFormatter')

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
        <form action="{{ route('admin.user.add.post') }}" method="POST" id='form_main'>

            @csrf

            <div class="row">
                <label>Email:</label> 
                <input style="width:200px;" type="text" name="email"/>
            </div>

            <div class="row">
                <label>姓名:</label> 
                <input style="width:200px;" type="text" name="name"/>
            </div>

            <div class="row">
                <label>管理員角色:</label>
                <select name="role" id="role" style="width:200px;">
                    @foreach($user_role_cast->role_list() as $key => $role)
                        <option value="{{ $key }}">{{ $role }}</option>
                    @endforeach
                </select>
            </div>

            <div class="row">
                <input style="width:100px;" type="submit" value="送出">
            </div>
        </form>
    </div>
</div>

@endsection 