$(function(){if(Window.Page === 'home'){
  var kml = $("meta[name='kml']").attr('content');
  var current_kml = $('.kml-picker').val();

  if( kml.length === 0 ){
    $('.slideout-menu-toggle').click();
  }

  $('.kml-picker').change(function(){
    if(current_kml !== $(this).val() ){
      window.location = '?kml='+$(this).val();
    }
  })

}})
