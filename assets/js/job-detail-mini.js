(function($){
  //   $( ".job-block" ).accordion({
  //     header: "table.summary",
  //     collapsible: true,
  //     active:false
  // });
  $(".show-all").on('click', function(){
    $(this).hide();
    $(this).parents('table').next().slideDown();
  });
  $(".hide-detail").on('click', function(){
    // $(this).hide();
    $(this).parents('table').prev().find('.show-all').show();
    $(this).parents('table').slideUp();
  });
  // $('.job-block').hide();
}(jQuery));
