@extends('candidate.layout')

<style>

.back-inner{
    display: flex;
    align-items: center;
    justify-content: center;
}

</style>

@section('title', $title)
@section('content')

<div class="maincontain">
<div class="back-inner">
    <div class="manage">
        <form action="{{route('candidate.login.post')}}" method="POST">
            @csrf
            <h1>Login</h1>
            <input type="text" name="account" placeholder="帳號">
            <input style="margin-bottom: 40px;" type="password" name="password" placeholder="密碼">
            <input style="width:50%" type="submit" value="登入">
        </form>
    </div>
</div>
</div>
@endsection