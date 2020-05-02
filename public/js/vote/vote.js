$(document).ready(function(){
     
     $(function() {
    $( "#dialog" ).dialog({
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
        "確定": function() {
          $( this ).dialog( "close" );
        },
        Cancel: function() {
          $( this ).dialog( "close" );
        }
      }
    });
 
    $( ".content .item" ).click(function() {
      $( "#dialog" ).dialog( "open" );
    });
         
    $("#login").click(function(){
        $(".login").css("display","block");
        $(".fade").css("display","block");
    });
    
    $(".fade").click(function(){
        $(".login").css("display","none");
        $(".fade").css("display","none");
    });
         
    $("#tt tr").click(function(){
        if($(this).hasClass("tr-select")) {
          $(this).removeClass("tr-select");
        } else{
          $(this).addClass("tr-select");
        }
    });
         
    $("#selectAll").click(function(){
        if($(this).hasClass("tr-select")) {
          $(this).removeClass("tr-select");
        } else{
          $(this).addClass("tr-select");
        }
    });    
  });
    
    
});