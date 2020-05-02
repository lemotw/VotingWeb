@inject('candidateService', 'App\Service\Election\CandidateService')

@php
    $candidate = $candidateService->Candidate();
    if($candidate != NULL) {
        $name = $candidate->Name;
        $imgPath = $candidate->image_path();
    }
@endphp

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>@yield('title')</title>
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <link rel="stylesheet" href="/vendor/js/jquery-ui-1.12.1/jquery-ui.css">
        <script src="/vendor/js/jquery-ui-1.12.1/jquery-ui.js"></script>
        <script src="/js/vote/vote.js"></script>
        <link rel="stylesheet" href="/css/vote/vote.css">

        <script src="https://unpkg.com/gijgo@1.9.11/js/gijgo.min.js" type="text/javascript"></script>
        <link href="https://unpkg.com/gijgo@1.9.11/css/gijgo.min.css" rel="stylesheet" type="text/css" />
        <script src="https://unpkg.com/ionicons@5.0.0/dist/ionicons.js"></script>

        <!-- bootstrap -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <!-- bootstrap growl -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-growl/1.0.0/jquery.bootstrap-growl.min.js"></script>

        <style>
            #profile {
                display: flex;
                flex-direction: row;
                align-items: center;
                margin: 10px;
            }

            #profile-img {
                width: 40px;
                height: 40px;
                margin-right: 10px;
                position:relative;
                overflow:hidden;
                border-radius:50%;
            }

            #profile img {
                width: 40px;
            }

            #profile label {
                font-weight: bold;
                color: white;
                margin: 0;
            }
        </style>

        @yield('include_asset')
    </head>

    <body>
        <div class="box">
        
            <div class="leftmenu">
                <ul>
                    <h4>Dashbord</h4>
                </ul>
                <ul style="height:auto">
                    @if($candidate != NULL)
                    <div id="profile">
                        <div id="profile-img"><img src="{{ $imgPath }}"></div>
                        <label>{{ $name }}</label>
                    </div>
                    @endif
                    <a href="{{ route('candidate.modify.page') }}"><li>個人資訊維護</li></a>
                    <a href="{{ route('candidate.election_position.index.page') }}"><li>參選職位</li></a>
                </ul>
                @if($candidate != NULL)
                <ol>QUICK</ol>
                <ul style="height:auto">
                    <a href="{{ route('candidate.logout') }}"><li>Logout</li></a>
                </ul>
                @endif
            </div>

            <div class="rightmenu">
                <div class="topmenu">
                    <h4 style="margin-left: 10px;">@yield('title')</h4>
                    <a href="#" onclick="history.go(-1);return false;"><ion-icon name="arrow-back-outline"></ion-icon></a>
                </div>
                
                <div class="maincontain">
                    @yield('content')
                </div>
            </div>

       </div>
    </body>

    <script>
        $(document).ready(function() {
            @if($errors)
            var error_message = {!! $errors->toJson() !!};
            if(error_message != null)
            {
                for(key in error_message) {
                    for(i in error_message[key]) {
                        $.bootstrapGrowl(error_message[key][i], {
                            type: 'danger',
                            align: 'center',
                            width: 'auto',
                            allow_dismiss: false
                        });
                    }
            }
            }
            @endif

            @if(session('msg'))
            var msg_message = "{!! session('msg') !!}";
            if(msg_message != "")
            {
                $.bootstrapGrowl(msg_message, {
                    type: 'success',
                    align: 'center',
                    width: 'auto',
                    allow_dismiss: false
                });
            }
            @endif

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });
    </script>

</html>
