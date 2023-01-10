$(document).ready(function()
{

   /*
   $("#date_input").on("change", function () 
   {
      $(this).css("color", "rgba(0,0,0,0)").siblings(".datepicker_label").css
      ({ "text-align":"center", position: "absolute",left: "10px", top:"14px",width:$(this).width()}).
      text($(this).val().length == 0 ? "" : ($.datepicker.formatDate($(this).attr("dateformat"), new Date($(this).val()))));
   });
   */
   
   /*
   $("#date_input").on("change", function () 
   {
      $(this).css("color", "rgba(0,0,0,0)").siblings(".datepicker_label").css
      ({width:$(this).width()}).
      text($(this).val().length == 0 ? "" : ($.datepicker.formatDate($(this).attr("dateformat"), new Date($(this).val()))));
   });
   */
   
   $('#nsDate').on('focusin',function()
  {
    $(this).siblings('#nsName').css({
      'z-index'   :'1',
      'background':'rgba(0,0,0,.1)',
    });
     $(this).css({
      'z-index' : '2',
      'background' : '#fff',
      });
  });
  
   $('#nsName').on('focusin',function(){
    $(this).siblings('#nsDate').css({
      'z-index'   :'1',
      'background':'rgba(0,0,0,.1)'
    });
    $(this).css({
      'z-index' : '2',
      'background' : '#fff'
     });
  });
  
  
  $('#nsDate').on('focusout',function(){
    $(this).siblings('#nsName').css({
      'z-index'   :'1',
      'background':'rgba(0,0,0,.1)'
    });
    $(this).css({
      'z-index' : '2',
      'background' : '#fff'
    });
  });
  
   $('#nsName').on('focusout',function(){
    $(this).siblings('#nsDate').css({
      'z-index'   :'1',
      'background':'rgba(0,0,0,.1)'
    });
    $(this).css({
      'z-index' : '2',
      'background' : '#fff'
     });
  });
})
