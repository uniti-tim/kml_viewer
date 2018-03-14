$(function(){if(Window.Page === 'editor'){

  //prevent being refresh on accident when selection list is not empty
  window.onbeforeunload = function() {
     return "Your selection list will not be saved if you refresh."
  };


}})
