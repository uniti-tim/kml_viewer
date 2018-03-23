$(function(){if(Window.Page === 'editor'){

  //prevent being refresh on accident when selection list is not empty
  window.onbeforeunload = function() {
     return "Your selection list will not be saved if you refresh.";
  };

  $('[data-submit-edits]').click(function(e){
    submitEdits(e, $(e.target).data('submit-edits'),$(e.target).data('uid') );
  })

}});

function submitEdits(e,model,uid){
  let $inputs = $(e.target).parents().closest('.modal-content').children().closest('.modal-body').children().find('input');
  var data = {}

  $inputs.each(function(i,el){
    let name = $(el).data('name'),
        value = $(el).val()
    data[name] = value;
  });

  $.ajax({
    url: "editor/submit",
    headers: {
      'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
    },
    type: "POST",
    data: {inputs : data, uid: uid},
    success: function(response){
      pRes = JSON.parse(response);
      if(response){
        window.onbeforeunload = null;
        location.reload();
      }
    }
  });
}
