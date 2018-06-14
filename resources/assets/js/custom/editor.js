$(function(){if(Window.Page === 'editor'){

  $('[data-submit-edits]').click(function(e){
    submitEdits(e, $(e.target).data('submit-edits'),$(e.target).data('uid'),'single' );
  })

  $('[data-bulk-edit]').click(function(e){
    submitEdits(e, $(e.target).data('bulk-edit'),null,'bulk' );
  })

  $('[data-edit-json]').click(function(e){
    modifyJSON($(e.target), $(e.target).data('edit-json') );
  })

  $('[data-edit-json-bulk]').click(function(e){
    bulkModifyJSON($(e.target), $(e.target).data('edit-json-bulk') );
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

function modifyJSON(target, modalName){
  var data = {}; //init empty data object to place the form key->values in
  var formFields = $(`[data-edit-attr='${modalName}']`);

  //This iterates over fields and assigns them to a string(key) and an int(value) pair
  formFields.each(function(i,el){
    let inputKey = '' + $(el).data('name');
    let value = +$(el).val();
    data[inputKey] = value;
  });

  //Stringify data object and replace the hidden field that gets submitted with the form.
  //and close the overlay modal.
  $(`[data-json-record-name="${modalName}"]`).val( JSON.stringify(data) );
  $(`#${modalName}`).modal('hide');

  $(`[data-target="#${modalName}"]`).children().remove();
  $(`[data-target="#${modalName}"]`).append(`<i class="fas fa-edit" style="color:#ee8703"></i>`)
}

function bulkModifyJSON(target, modalName){
  var data = {}; //init empty data object to place the form key->values in
  var formFields = $(`[data-edit-attr='${modalName}']`);

  //This iterates over fields and assigns them to a string(key) and an int(value) pair
  formFields.each(function(i,el){
    let inputKey = '' + $(el).data('name');
    let value = +$(el).val();
    data[inputKey] = value;
  });

  //Stringify data object and replace the hidden field that gets submitted with the form.
  //and close the overlay modal.
  $(`[data-json-record-name-bulk="${modalName}"]`).val( JSON.stringify(data) );
  $(`#${modalName}`).modal('hide');

  $(`[data-target="#${modalName}"]`).children().remove();
  $(`[data-target="#${modalName}"]`).append(`<i class="fas fa-edit" style="color:#ee8703"></i>`)
}
