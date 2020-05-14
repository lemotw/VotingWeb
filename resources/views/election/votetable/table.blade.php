<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="key" content="{{ isset($key)?$key:NULL }}">

    <link rel="stylesheet" href="/vote/css/vote.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <link rel="stylesheet" href="/vote/js/jquery-ui-1.12.1/jquery-ui.css">

    <script src="/vote/js/jquery-ui-1.12.1/jquery-ui.js"></script>
    <script src="/vote/js/vote.js"></script>
    <script src="/js/encryption.js"></script>
    <script src="/js/php_serialize.js"></script>
    <script src="https://unpkg.com/ionicons@5.0.0/dist/ionicons.js"></script>
    <script type="text/javascript" src="https://cdn.rawgit.com/ricmoo/aes-js/e27b99df/index.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/js-base64@2.5.1/base64.min.js"></script>



    <title>Vote</title>

</head>

<body>
    <div id="dialog" title="請確認你的選項是否正確！">
        <p>你確定要投給他嗎?</p>
    </div>
    <div class="top">
        <img src="img/2020.jpg">
        <!--
    <div class="item">
        <a href="#">首頁</a>
        <a href="#">政黨票</a>
        <a href="#">個人政見</a>
        <a id="login" href="#">登入</a>           
    </div>
-->
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

    <div class="success_fade">
        <div id="success_message">
            投票成功，請離開投票庭
        </div>
    </div>

    <div class="timeout_fade">
        <div id="timeout_message">
            操過投票時間，請到服務台回報
        </div>
    </div>

    <div class="faild_fade">
        <div id="faild_message">
        </div>
    </div>

    <div class='anime_fade'>
        <div id='animate'>
            LOADING
        </div>
    </div>
</body>

</html>