let anime = function () {
  var elem = document.getElementById("animate");
  $('.anime_fade').css('display', 'flex');
  // initial degree
  var deg = 0;
  // timer per round
  var id = setInterval(frame, 5);
  function frame() {
    if (deg > 160) {
      // time up
      clearInterval(id);
      $('.anime_fade').css('display', 'none');
    } else {
      deg++;
      elem.style.transform = 'rotate(' + deg + 'deg)';
    }
  }
}

let reshowTokenInput = function() {
  $('#user').val('');
  $('.sign_fade').css('display', 'flex')
  $('#user').focus();
}

let reshowSidInput = function() {
  $('#sid').val('')
  $('.sid_fade').css('display', 'flex')
  $('#sid').focus();
}

let getToken = function () {
  $("#user").keydown(function (event) {
    // Detect Enter key
    if (event.which == 13) {
      // hide token input page
      $('.sign_fade').css('display', 'none')
      // set to localStorage
      localStorage.setItem('token', $(this).val())

      // reset sid input
      reshowSidInput()
    }
  });
}

let getSid = function() {
  $("#sid").keydown(function (event) {
    // Detect Enter key
    if (event.which == 13) {
      event.preventDefault();

      // hide token input page
      $('.sid_fade').css('display', 'none')
      // set to localStorage
      localStorage.setItem('sid', $(this).val())

      swal({
        title: "確定學生證是否正確？",
        html: "學號為"+$(this).val(),
        type: "question",
        showCancelButton: true//顯示取消按鈕
      }).then(function (){
            // animation
            anime()
            // Auth Token
            AuthToken()
            reshowTokenInput();
      }, function(dismiss) {
        if(dismiss === 'cancel') {
            console.log(dismiss);
            reshowTokenInput();
        }
      }); 

      // var dialogHTML = `<p>請確認學號是否為 ${ $(this).val() } ！！</p>`
      // $("#dialog").html(dialogHTML)
      // $("#dialog").dialog("open")
    }
  });
}

let AuthToken = function() {
  
  var payload = {
    election: $('meta[name=election]').attr('content'),
    sid: localStorage.getItem('sid'),
    token: localStorage.getItem('token'),
  }
  console.log(payload)

  // Get encrypt key and encrypt payload
  var encryptKey = localStorage.getItem('encryptKey')
  if(encryptKey == null)
    return false;
  
  var encryptedStr = encrypt(serialize(JSON.stringify(payload)), encryptKey)

  // Get Votes information
  $.ajax({
    type: 'POST',
    url: '/admin/authtable/auth',
    cache: false,
    data: {
      str: encryptedStr,
      _token: $('meta[name=csrf-token]').attr('content')
    },
    success: function(data){
      console.log(data)
    },
    error: function(requestObject, error, errorThrown){
      // error Handler
    },
  })
}

let setKey = function(){
  // Get encryptKey
  var verifyCode = prompt('請輸入信箱驗證碼！！')
  var encryptKey = decrypt($('meta[name=key]').attr('content'), verifyCode)
  localStorage.setItem('encryptKey', JSON.parse(unserialize(encryptKey)).key)
}

$(document).ready(function () {
  // initialize
  localStorage.clear()

  // Clear vote page content
  $('.wrap').css('display', 'none')

  $("#dialog").dialog({
    autoOpen: false,
    show: {
      effect: "highligh",
      duration: 1000
    },
    hide: {
      effect: "highligh",
      duration: 1000
    },

    buttons: {
      "確定": function () {
        // animation
        anime()
        // Auth Token
        AuthToken()
        // Close dialog
        $(this).dialog("close");
        // Hide current vote page
        $('.wrap').css('display', 'none')
      },

      Cancel: function () {
        // Select Cancel button
        $(this).dialog("close");
      }
    }
  });

  getToken()
  getSid()
});