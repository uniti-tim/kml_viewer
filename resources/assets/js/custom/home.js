$(function(){if(Window.Page === 'dash'){

  //prevent being refresh on accident when selection list is not empty
  window.onbeforeunload = function() {
   if ( $('tbody[data-selections]').children().length > 0  ) {
     return "Your selection list will not be saved if you refresh."
   } else {
      return;
   }
  };

  var kml = $("meta[name='kml']").attr('content');
  var current_kml = $('.kml-picker').val();

  if( kml.length === 0 ){
    $('.selection-container').hide();
    $('.slideout-menu-toggle').click();
  }

  $('.kml-picker').change(function(){
    if(current_kml !== $(this).val() ){
      window.location = '?kml='+$(this).val();
    }
  })

}})
