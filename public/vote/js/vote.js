
let renderElectionPosition = function (ElectionPosition) {
  // Generate html
  var html = ''
  if (ElectionPosition._type == "multiple") {
    // multiple choice build per candidate card
    for (var i=0; i<ElectionPosition._candidate.length; ++i) {
      var expHTML = ''
      if(ElectionPosition._candidate[i].exp != null)
      {
        var splitExp = ElectionPosition._candidate[i].exp.split(';')
        for(var j=0; j<splitExp.length ;++j)
          expHTML += `<li>${splitExp[j]}</li>`
      }

      html += `
      <div class="item">
        <img src="${ElectionPosition._candidate[i].CandidateImage}"/>
        <div class="txt">
          <h2>${ElectionPosition._candidate[i].name}</h2>
          <input type="hidden" class="UID" value="${ElectionPosition._candidate[i].Candidate}">
          <input type="hidden" class="ElectionPositionUID" value="${ElectionPosition.UID}">
          <ul>
            <li><h1><b>經歷</b></h1></li>
            ${expHTML}
          </ul>
        </div>
      </div>
      `
   }
  } else if (ElectionPosition._type == "single") {
    // single choice build candidate card
    var expHTML = ''
    var splitExp = ElectionPosition._candidate.exp.split(';')
    for(var i=0; i<splitExp.length ;++i)
      expHTML += `<li>${splitExp[i]}</li>`

    html += `
      <div class="chitem">
        <img src="${ElectionPosition._candidate.CandidateImage}">
        <div class="txt">
          <h2>${ElectionPosition._candidate.name}</h2>
          <input type="hidden" class="UID" value="${ElectionPosition._candidate.Candidate}">
          <input type="hidden" class="ElectionPositionUID" value="${ElectionPosition.UID}">
          <div class="choice">
            <div class="chtxt">
              <ul>
                <li><h1><b>經歷</b></h1></li>
                ${expHTML}
              </ul>
            </div>
          </div>
        </div>
                  
        <div class="cbut">
            <input id='approve' type="button" value="贊成">
            <input id='against' type="button" value="不贊成">
        </div>
      </div>
      `
  }

  html = `
  <div class="wrap" id="vote1">
    <h1>${ElectionPosition.Name}</h1>
    ${html}
  </div>
  `
  $('.content').html(html)

  // Sign up click event
  if(ElectionPosition._type == "multiple")
  {
    // Sign up click event
    $(".content .item").click(function () {
      var candidate = $('h2', this).text()
      var UID = $('.UID', this).val();
      var ElectionPositionUID = $('.ElectionPositionUID', this).val();

      $("#dialog").attr("SelectCandidate", UID)
      $("#dialog").attr("ElectionPositionUID", ElectionPositionUID)
      $("#dialog").dialog("open")

      var dialogHTML = `<p>你確定要頭給 ${candidate} 他嗎？</p>`
      $("#dialog").html(dialogHTML)
    });

  } else if(ElectionPosition._type == "single") {
    // Approve button click event
    $("#approve").click(function(){
      var pp = $(this).parent().parent();
      var candidate= $('.txt > h2', pp).text();
      var UID = $('.UID', pp).val();
      var ElectionPositionUID = $('.ElectionPositionUID', pp).val();

      $("#dialog").attr("vote", 1)
      $("#dialog").attr("SelectCandidate", UID)
      $("#dialog").attr("ElectionPositionUID", ElectionPositionUID)
      $("#dialog").dialog("open")

      var dialogHTML = `<p>你確定您贊成 ${candidate} 選上嗎？</p>`
      $("#dialog").html(dialogHTML)
    })
    // Against button click event
    $("#against").click(function(){
      var pp = $(this).parent().parent();
      var candidate= $('.txt > h2', pp).text();
      var UID = $('.UID', pp).val();
      var ElectionPositionUID = $('.ElectionPositionUID', pp).val();

      $("#dialog").attr("vote", 0)
      $("#dialog").attr("SelectCandidate", UID)
      $("#dialog").attr("ElectionPositionUID", ElectionPositionUID)
      $("#dialog").dialog("open")

      var dialogHTML = `<p>你確定您反對 ${candidate} 選上嗎？</p>`
      $("#dialog").html(dialogHTML)
    })
  }
}

let sentVote = function(ElectionPositionUID, CandidateUID, token, vote) {

  var payload = {
    token: token,
    ElectionPosition: ElectionPositionUID,
    Candidate: CandidateUID,
    vote: 1
  }

  if(vote != null)
    payload.vote = parseInt(vote)

  // return payload;
  console.log(payload)

  // Get encrypt key and encrypt payload
  var encryptKey = localStorage.getItem('encryptKey')
  if(encryptKey == null)
    return false;
  
  var encryptedStr = encrypt(serialize(JSON.stringify(payload)), encryptKey)
  $.ajax({
    type: 'POST',
    url: '/admin/votetable/vote',
    cache: false,
    data: {
      enStr: encryptedStr,
      _token: $('meta[name=csrf-token]').attr('content')
    },
    success: function(data){
      console.log(data)
    },
    error: function(requestObject, error, errorThrown){
      console.log(requestObject)
      console.log(error)
      console.log(errorThrown)
    },
  })
}

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

let faild_Ajax = function(msg) {
  console.log(msg.error);
  var html_code = `<h3>${msg.error._type}:</h3><h5>${msg.error._message}</h5>`
  $('#faild_message').html(html_code);
  console.log(html_code);

  $('.faild_fade').css('display', 'flex');
  $('.faild_fade').css('z-index', '2');

  window.setTimeout(function() {
    $('.faild_fade').css('z-index', '1');
    $('.faild_fade').css('display', 'none');
  }, 4000)
}

let finish = function() {
  $('.success_fade').css('display', 'flex');
  $('.success_fade').css('z-index', '2');

  window.setTimeout(function() {
    $('.success_fade').css('z-index', '1');
    $('.success_fade').css('display', 'none');
  }, 4000)
}

let reshowTokenInput = function() {
  $('#user').val('');
  $('.sign_fade').css('display', 'flex')
  $('#user').focus();
}

let getToken = function () {
  $("#user").keydown(function (event) {
    // Detect Enter key
    if (event.which == 13) {
      event.preventDefault();
      // hide token input page
      $('.sign_fade').css('display', 'none')
      // set to localStorage
      localStorage.setItem('token', $(this).val())
      GetVotes()
    }
  });
}

let GetVotes = function() {
  // Get Votes information
  $.ajax({
    type: 'POST',
    url: '/admin/votetable/getVotes',
    cache: false,
    data: {
      token: localStorage.getItem('token'),
      _token: $('meta[name=csrf-token]').attr('content')
    },
    success: function(data){
      localStorage.setItem('VoteCount', 0)
      localStorage.setItem('votes', data);
      json_data = JSON.parse(data);
      console.log(json_data);

      if(!json_data.success) {
        reshowTokenInput();
        faild_Ajax(JSON.parse(data));      
      } else {
        localStorage.setItem('votes', data);
        renderNextVote();
      }
    },
    error: function(data){
      // error Handler
      reshowTokenInput();
      faild_Ajax(JSON.parse(data));      
    },
  })
}

let renderNextVote = function() {
  var ajaxData = JSON.parse(localStorage.getItem('votes'))

  // Check votes data
  if(ajaxData == null)
    return null

  var ElectionPositionList = ajaxData.data
  var count = parseInt(localStorage.getItem('VoteCount'))

  if(count == -1)
  {
    reshowTokenInput()
    finish()
    return
  } else if(count >= ElectionPositionList.length) {
    // Out of range set to -1
    localStorage.setItem('VoteCount', -1)
    reshowTokenInput()
    finish()
    return
  }
  
  renderElectionPosition(ElectionPositionList[count])

  // Increase VoteCount
  localStorage.setItem('VoteCount', ++count)
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
      "確定": function (event) {
        event.preventDefault();
        // animation
        anime()
        // Sent vote
        var vote = $(this).attr('vote')
        var CandidateUID = $(this).attr('SelectCandidate')
        var ElectionPositionUID = $(this).attr('ElectionPositionUID')
        var token = localStorage.getItem('token')
        sentVote(ElectionPositionUID, CandidateUID, token, vote)

        // Close dialog
        $(this).dialog("close");
        // Hide current vote page
        $('.wrap').css('display', 'none')

        renderNextVote()
      },

      Cancel: function () {
        // Select Cancel button
        $(this).dialog("close");
      }
    }
  });

  getToken()
});