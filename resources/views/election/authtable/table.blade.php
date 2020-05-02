<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="election" content="{{ isset($election)?$election->id:NULL }}">
    <meta name="key" content="{{ isset($key)?$key:NULL }}">

    <link rel="stylesheet" href="/vote/css/auth.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <link rel="stylesheet" href="/vote/js/jquery-ui-1.12.1/jquery-ui.css">

    <script src="/vote/js/jquery-ui-1.12.1/jquery-ui.js"></script>
    <script src="/vote/js/auth.js"></script>
    <script src="/js/encryption.js"></script>
    <script src="/js/php_serialize.js"></script>
    <script src="https://unpkg.com/ionicons@5.0.0/dist/ionicons.js"></script>
    <script type="text/javascript" src="https://cdn.rawgit.com/ricmoo/aes-js/e27b99df/index.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/js-base64@2.5.1/base64.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.10.3/sweetalert2.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.10.3/sweetalert2.js" type="text/javascript"></script>


    <title>Auth</title>

</head>

<body>
    <div id="dialog" title="請確認你的資訊是否正確！">
    </div>
    <div class="top">
        <img src="img/2020.jpg">
    </div>

        <div class="content">
        </div>
    </div>

    <div class="footer">
        <h3>CONTACT</h3>
    </div>


    <div class="sign_fade">
        <div class="sign_content">
            <input id='user' type="text" autofocus>
        </div>
    </div>

    <div class="sid_fade" style="display: none">
        <div class="sid_content">
            請輸入學號：
            <input id='sid' type="text" autofocus>
        </div>
    </div>

    <div class='anime_fade'>
        <div id='animate'>
            LOADING
        </div>
    </div>
</body>

</html>