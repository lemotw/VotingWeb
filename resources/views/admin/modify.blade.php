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

    #password_here {
        display: none;
    }

    #password_change {
        color: white;
        width: 50%;
    }

</style>

@section('title', $title)
@section('content')
<div class="back-inner">
    <h4>{{ $subtitle }}</h4>
        <form action="{{ route('admin.user.modify.post') }}" method="POST" id='form_main'>

            @csrf

            <div class="row">
                <label>姓名:</label> 
                <input style="width:200px;" type="text" name="name" value="{{ $user->name }}"/>
            </div>

            <a id="password_change" class="btn btn-primary">更改密碼</a>
            <div id="password_here" class="row">
            </div>

            <div class="row">
                <input style="width:100px;" type="submit" value="送出">
            </div>
        </form>
    </div>
</div>

<script>

    $(document).ready(function () {
        $('#password_change').click(function () {
            var password_html = `
                <label>密碼:</label> 
                <input style="width:200px;" type="password" name="password"/>
            `;

            $('#password_here').html(password_html);
            $('#password_here').css('display', 'flex');
            $('#password_change').css('display', 'none');
        });
    });

</script>

@endsection 