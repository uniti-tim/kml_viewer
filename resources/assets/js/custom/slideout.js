$(function(){
  var slideout = new Slideout({
      'panel': document.getElementById('panel'),
      'menu': document.getElementById('menu'),
      'padding': 300,
      'tolerance': 70
    });

  $('.slideout-menu-toggle').click(function(){
    $(this).toggleClass('open');
    slideout.toggle();
  })
})
