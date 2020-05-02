@extends('election.layout')

@section('include_asset')
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
<style>
.back-inner {
    display: flex;
    align-items: center;
    justify-content: center;
}

.alert {
    margin-top: 10px;
    font-size: 1pt;
    padding:10px;
}
</style>
@stop

@section('title', $title)
@section('content')

<div class="back-inner">
    <div class="manage">
        <form action="{{route('admin.login.post')}}" method="POST">
            @csrf
            <h1>Login</h1>
            <input type="text" name="email" placeholder="帳號">
            <input style="margin-bottom: 40px;" type="password" name="password" placeholder="密碼">
            <input style="width:50%" type="submit" value="登入">
        </form>
    </div>
</div>
@endsection