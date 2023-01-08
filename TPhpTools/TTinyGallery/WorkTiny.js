$(document).ready(function()
{
   $('.pwd').on('focusin',function()
  {
    $(this).siblings('.user').css({
      'z-index'   :'1',
      'background':'rgba(0,0,0,.1)',
    });
     $(this).css({
      'z-index' : '2',
      'background' : '#fff',
      });
  });
  
   $('.user').on('focusin',function(){
    $(this).siblings('.pwd').css({
      'z-index'   :'1',
      'background':'rgba(0,0,0,.1)'
    });
    $(this).css({
      'z-index' : '2',
      'background' : '#fff'
     });
  });
  
  
  $('.pwd').on('focusout',function(){
    $(this).siblings('.user').css({
      'z-index'   :'1',
      'background':'rgba(0,0,0,.1)'
    });
    $(this).css({
      'z-index' : '2',
      'background' : '#fff'
    });
  });
  
   $('.user').on('focusout',function(){
    $(this).siblings('.pwd').css({
      'z-index'   :'1',
      'background':'rgba(0,0,0,.1)'
    });
    $(this).css({
      'z-index' : '2',
      'background' : '#fff'
     });
  });
})
