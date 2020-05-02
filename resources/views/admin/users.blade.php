@extends('election.layout')
@inject('user_role_cast', 'App\Service\Formatter\UserRoleFormatter')

@php
    $cu_user = Auth::user();

@endphp

@section('title', $title)
@section('content')
    <div class="maincontain">
        <div class="conbine">
            <div class="tool">
                <tr>
                    <td><a href="{{ route('admin.user.add.page') }}">新增</a></td>
                </tr>
            </div>
            <div class="table">
                <table  id="tt">
                    <tr>
                        <th>名稱</th>
                        <th>帳號</th>
                        <th>角色切換</th>
                        <th>操作</th>
                    </tr>
                    @foreach($users as $user)
                    @if($cu_user != NULL && $user->id == $cu_user->id)
                        @continue
                    @endif
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email}}</td>
                        <td>
                            <select class="role" user="{{ $user->id }}">
                                @foreach($user_role_cast->role_list() as $key => $role)
                                    <option value="{{ $key }}"
                                    @if($user->role == $key)
                                    selected
                                    @endif
                                    >{{ $role }}</option>
                                @endforeach
                            </select> 
                        </td>
                        <td>
                            <a href="{{ route('admin.user.reset_password', ['id'=>$user->id]) }}">重設密碼</a>
                            <a href="#" onclick="deleteUser(this)" user="{{ $user->id }}">刪除該使用者</a>
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>



    <script>
        function deleteUser(btn) {
            var user_id = $(btn).attr('user');
            var name = $($(btn).parent().parent().find('td')[0]).text();

            if(!confirm('您是否要刪除這位使用者: '+name))
                return;
            
            $.ajax({
                type: "POST",
                async: true,
                url: '{{ route('admin.user.delete.post') }}',
                data: {
                    'id': user_id
                },
                error: function (data) {
                    $.bootstrapGrowl('好像有一點怪怪的！', {
                        type: 'danger',
                        align: 'center',
                        width: 'auto',
                        allow_dismiss: false
                    });
                },
                success: function (data) {
                    $.bootstrapGrowl('角色刪除成功!', {
                        type: 'success',
                        align: 'center',
                        width: 'auto',
                        allow_dismiss: false
                    });
                    location.reload();
                }
            })
        }

        $(document).ready(function(){
            $('.role').change(function() {
                var url = '{{ route("admin.user_role.change.post") }}';
                var change_role = $(this).val();
                var user_id = $(this).attr('user');

                $.ajax({
                    type: "POST",
                    async: true,
                    url: url,
                    data: {
                        'id': user_id,
                        'role': change_role
                    },
                    error: function (data) {
                        $.bootstrapGrowl('好像有一點怪怪的！', {
                            type: 'danger',
                            align: 'center',
                            width: 'auto',
                            allow_dismiss: false
                        });
                    },
                    success: function (data) {
                        $.bootstrapGrowl('更改角色成功!', {
                            type: 'success',
                            align: 'center',
                            width: 'auto',
                            allow_dismiss: false
                        });
                    }
                })
            });
        });
    </script>
@endsection