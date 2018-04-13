$(function(){if(Window.Page === 'editor'){

  //prevent being refresh on accident when selection list is not empty
  window.onbeforeunload = function() {
     return "Your selection list will not be saved if you refresh.";
  };

  $('[data-submit-edits]').click(function(e){
    submitEdits(e, $(e.target).data('submit-edits'),$(e.target).data('uid'),'single' );
  })

  $('[data-bulk-edit]').click(function(e){
    submitEdits(e, $(e.target).data('bulk-edit'),null,'bulk' );
  })

}});

//submit single edit submissions
function submitEdits(e,model,uid,type){
  let $inputs = $(e.target).parents().closest('.modal-content').children().closest('.modal-body').children().find('input'),
  url = (type === 'single')? "editor/submit":"editor/bulk/submit",
  fieldVals = {},
  bulkSelection = (type === 'bulk')? parseSelection('data'):null;

  $inputs.each(function(i,el){
    let name = $(el).data('name'),
        value = $(el).val()
    fieldVals[name] = value;
  });

  $.ajax({
    url: url,
    headers: {
      'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
    },
    type: "POST",
    data: {inputs : fieldVals, uid: uid, bulk_selection: bulkSelection, model: model},
    success: function(response){
      pRes = JSON.parse(response);
      if(response){
        window.onbeforeunload = null;
        location.reload();
      }
    }
  });
}

function parseSelection(name){
  let uid_collection = [];
  let data;
  let url_params =  new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);

  if( url_params == null ){
    return null;
  }else{
    data = JSON.parse(decodeURI(url_params[1])) || 0;
  }

  data.forEach(function(el,i){
      uid_collection.push(el[0]);
  })

  return uid_collection;
}
