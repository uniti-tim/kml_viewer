@extends('layouts.main')


@section('menu')
<div class="col-xs-12">
  <h2><u>Attribute Editor</u></h2>
  <p>
    On this page you can mass assign values to attributes or view and modify indivdual zone attributes. Some speciality attributes are not modifiable, but will be visible.
    <br><br>
    Once your changes have been saved you may close this window. If you would like to check if your selected zone modifications were saved - you
    can export the Selection + data and confirm the changes were pushed or you may click the 'View Attributes' button in the table.
  </p>

  <div onclick='window.close()' class="btn btn-default">Close Editor</div>
</div>
@endsection

@section('content')
<body class="hm-gradient">
  <style>
    .modal-backdrop{
      display: none;
    }
  </style>
  <main>
      <!--MDB Tables-->
      <div class="container mt-4">

        @if( !is_null($error) )
          <div class="alert alert-danger text-center" style="margin-top:2%" role="alert">
            <strong>Error!</strong> {{$error}} <br>
            <a href="#" onClick="window.close()">Close Editor </a>
          </div>
        @endif

        @if( session()->has('success') )
          <div class="alert alert-success text-center" style="margin-top:2%" role="alert">
            <strong>Success!</strong> {{session('success')}} <br>
          </div>
        @endif

          <div class="card col-xs-12" style="box-shadow: 0px 0px 10px; margin-top:5%">
              <div class="card-body">
                  <!-- Grid row -->
                  <div class="row">
                    <h2 class="pt-3 pb-4 text-center font-bold font-up deep-purple-text">Editing {{ !empty(class_basename($model)) ? str_plural(class_basename($model)):str_plural(ucwords(request()->model)) }}</h2>
                      <!-- Grid column -->
                      <!-- <div class="col-md-12">
                          <div class="input-group md-form form-sm form-2 pl-0">
                              <input class="form-control my-0 py-1 pl-3 purple-border" type="text" placeholder="Search something here..." aria-label="Search">
                              <span class="input-group-addon waves-effect purple lighten-2" id="basic-addon1"><a><i class="fa fa-search white-text" aria-hidden="true"></i></a></span>
                          </div>
                      </div> -->
                      <!-- Grid column -->
                      <div class="col-xs-4">
                        <div style="margin:5px 10px 0px 10px" class="btn btn-default col-xs-12" data-toggle='modal' data-target='#bulkUpdateModal'>Update All Selected Records </div>
                        <!-- <div style="margin:5px 10px 0px 10px" class="btn btn-default col-xs-12">Add new fields to selection</div> -->
                      </div>

                  </div>
                  <!-- Grid row -->

                  <table class="table table-striped">
                      <thead>
                          <tr>
                              <th class='text-center'>GIS ID</th>
                              <th class='text-center'>Entity Name</th>
                              <th class='text-center'>Attribute Values</th>
                              <th class='text-center'>Edit</th>
                          </tr>
                      </thead>
                      <tbody>
                          @if(count($data) > 0 )
                            @foreach($data as $record)
                            <tr>
                                <th class='text-center'>{{$record[0]}}</th>
                                <td class='text-center'>{{$record[1]}}</td>
                                <td class='text-center'>
                                  <div class="btn btn-default" data-toggle="modal" data-target="#{{$record[0]}}Attributes">View Attributes</div> <!-- Make Modal to show attributes -->
                                </td>
                                <td class='text-center'>
                                  <div class="btn btn-primary" data-toggle="modal" data-target="#{{$record[0]}}Editor">Edit Record</div><!-- Go -->
                                </td>


                                <!-- Viewing Modal -->
                                <div class="modal fade" style="margin-top:5%" id="{{$record[0]}}Attributes" tabindex="-1" role="dialog" aria-hidden="true">
                                  <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h3 class="modal-title text-center">Attributes For {{$record[1]}}</h3>
                                      </div>
                                      <div class="modal-body">
                                        <?php $attr = json_decode($model::where('uid',$record[0])->get()[0]->data); ?>
                                        <ul style="list-style:none">
                                          @foreach($attr as $key => $value)
                                          <?php
                                          $modifiable_attributes = $model::mod_attributes();
                                          if( !in_array($key, array_keys($modifiable_attributes)) ){continue;}
                                          $value = is_array($value)? implode(",",$value):(string)$value;
                                          ?>
                                            <li style="width:100%;word-break:break-word">
                                              <b>{{$key}}</b>: {{$value}}
                                            </li>
                                          @endforeach
                                        </ul>

                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Dismiss</button>
                                      </div>
                                    </div>
                                  </div>
                                </div>

                                <!-- Modal -->
                                <div class="modal fade" style="margin-top:5%" id="{{$record[0]}}Editor" tabindex="-1" role="dialog" aria-hidden="true">
                                  <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h3 class="modal-title text-center">Editing Attributes For {{$record[1]}}</h3>
                                      </div>
                                      <div class="modal-body">
                                        <?php
                                        $attr = json_decode($model::where('uid',$record[0])->get()[0]->data);
                                        $mod_attr = $model::mod_attributes();
                                        ?>
                                        <ul style="list-style:none">
                                          @foreach($attr as $key => $value)
                                            <?php
                                            #skip if value is not modifiable or is an array type
                                            if( !in_array($key,array_keys($mod_attr)) || is_array($value) ){continue;}
                                            ?>
                                            <div class="form-group row">

                                              <label class="col-sm-4 text-right" style="line-height: 32px;font-size: 15px;">
                                                {{ $model::friendly_attribute($key, 'name') }}

                                                @if( !is_null( $model::friendly_attribute($key, 'helper_text') )  )
                                                  <i class='fa-fw fas fa-info-circle'
                                                  data-toggle="tooltip"
                                                  data-html="true"
                                                  title="{{ $model::friendly_attribute($key, 'helper_text') }}"
                                                  ></i>
                                                @endif

                                              </label>

                                              <div class="col-sm-6">
                                                @if( $mod_attr[$key] === 'json')

                                                  <div class="btn btn-default"  data-toggle="modal" data-target="#{{$record[0]}}_{{$key}}">
                                                    Enabled/Disable {{$model::friendly_attribute($key, 'name')}}
                                                  </div>
                                                  <input type='hidden'
                                                  data-edit-attr
                                                  data-name='{{$key}}'
                                                  data-json-record-name ='{{$record[0]}}_{{$key}}'
                                                  value='{{$value}}'
                                                  />


                                                  <!-- MODAL FOR JSON INPUTS  -->
                                                  <div class="modal fade" style="margin-top:5%" id="{{$record[0]}}_{{$key}}" tabindex="-1" role="dialog" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                      <div class="modal-content">
                                                        <div class="modal-header">
                                                          <h3 class="modal-title text-center">Update Fields for {{$model::friendly_attribute($key, 'name')}}</h3>
                                                        </div>
                                                        <div class="modal-body">
                                                          <div class="form-group row">

                                                            <?php
                                                            $decoded_data = json_decode($value);
                                                            $data_model = $model::friendly_attribute($key,'model'); //gets model for JSON type field
                                                            ?>
                                                            <!-- Iterate over data in JSON object and display it -->
                                                            @foreach($decoded_data as $_key => $_value)
                                                              <?php $model_item = $data_model::find($_key);
                                                              //check if record even exists; if so find the name.
                                                              if( is_null($model_item) ){
                                                                continue;
                                                              }
                                                              ?>
                                                            <div class="col-sm-12">
                                                              <label class="col-xs-6 text-right" style="line-height: 32px;font-size: 15px;">
                                                                {{$model_item->name}}
                                                              </label>

                                                              <div class="col-xs-6">
                                                                  <select
                                                                  data-edit-attr='{{$record[0]}}_{{$key}}'
                                                                  data-name='{{$_key}}'
                                                                  class="form-control"
                                                                  value={{$_value}}
                                                                  placeholder="value"
                                                                  >
                                                                    <option {{$_value? "selected":''}} value="1">Enabled</option>
                                                                    <option {{!$_value? "selected":''}} value="0">Disabled</option>
                                                                  </select>
                                                              </div>
                                                            </div>
                                                            @endforeach

                                                          </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                          <button type="button" class="btn btn-secondary" onclick="$('#{{$record[0]}}_{{$key}}').modal('hide')" >Dismiss</button>
                                                          <button type="button" class="btn btn-success"
                                                          data-edit-json="{{$record[0]}}_{{$key}}"
                                                          >Done</button>
                                                        </div>
                                                      </div>
                                                    </div>
                                                  </div>
                                                  <!--END MODAL FOR JSON INPUTS  -->


                                                @else
                                                  <input
                                                  data-edit-attr
                                                  data-name='{{$key}}'
                                                  type="text"
                                                  class="form-control"
                                                  @if( $value ==0 )
                                                    value=""
                                                  @else
                                                    value="{{$value}}"
                                                  @endif
                                                    placeholder="Use Company Default" />
                                                @endif
                                              </div>



                                            </div>
                                          @endforeach
                                        </ul>

                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Dismiss</button>
                                        <button type="button"
                                        data-submit-edits='{{class_basename($model)}}'
                                        data-uid='{{$record[0]}}'
                                        class="btn btn-success" data-dismiss="modal">Submit</button>
                                      </div>
                                    </div>
                                  </div>
                                </div>

                            </tr>
                            @endforeach
                          @else
                          <tr>
                              <th class="text-center" colspan="10">No Data Selected to Edit</th>
                          </tr>
                          @endif
                      </tbody>
                      <!--Table body-->
                  </table>
                  <!--Table-->
                  {!! $data->appends(['data'=> request()->data,'model'=>class_basename($model) ])->render() !!}
              </div>
          </div>
      </div>


      <!-- Bulk Update Modal -->
      <div class="modal fade" style="margin-top:5%" id="bulkUpdateModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h3 class="modal-title text-center">Bulk Update Fields for {{class_basename($model)}}</h3>
            </div>
            <div class="modal-body">
              <p class=" alert alert-danger"><strong>NOTE:</strong> This will overwrite the attribute with your input in the field to ALL areas you selected. To make a field empty, just add a single space in the input field. If you dont want to edit field than leave it empty.</p>

              <?php $mod_attr = $model::mod_attributes(); ?>
              <ul style="list-style:none">
                @foreach($mod_attr as $key => $value)
                <div class="form-group row">

                  <label class="col-sm-4 text-right" style="line-height: 32px;font-size: 15px;">
                    {{ $model::friendly_attribute($key, 'name') }}
                    @if( !is_null( $model::friendly_attribute($key, 'helper_text') )  )
                      <i class='fa-fw fas fa-info-circle'
                      data-toggle="tooltip"
                      data-html="true"
                      title="{{ $model::friendly_attribute($key, 'helper_text') }}"
                      ></i>
                    @endif
                  </label>

                  <div class="col-sm-6">
                    @if( $mod_attr[$key] === 'json')

                      <div class="btn btn-default"  data-toggle="modal" data-target="#bulk_{{$key}}">
                        Configure {{$model::friendly_attribute($key, 'name')}}
                      </div>
                      <input type='hidden'
                      data-edit-attr
                      data-name='{{$key}}'
                      data-json-record-name-bulk ='bulk_{{$key}}'
                      />


                      <!-- MODAL FOR JSON INPUTS  -->
                      <div class="modal fade" style="margin-top:5%" id="bulk_{{$key}}" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h3 class="modal-title text-center">Update Fields for {{$model::friendly_attribute($key, 'name')}}</h3>
                            </div>
                            <div class="modal-body">
                              <div class="form-group row">

                                <?php
                                $data_model = $model::friendly_attribute($key,'model'); //gets model for JSON type field
                                //Since we dont have any data to map on for builk edits we have to first grab
                                //an example of what a this json object looks like. To do this we use the base model eg. County
                                //and decode its data column AND decode the json field we are looking for ($key). Now we have
                                //a template we can use to apply bulk edits.
                                $decoded_data = json_decode(json_decode($model::first()->data)->$key)
                                ?>
                                <!-- Iterate over data in JSON object and display it -->
                                @foreach($decoded_data as $_key => $_value)
                                  <?php $model_item = $data_model::find($_key);
                                  //check if record even exists for JSON object; if so find the name.
                                  if( is_null($model_item) ){
                                    continue;
                                  }
                                  ?>
                                <div class="col-sm-12">
                                  <label class="col-xs-6 text-right" style="line-height: 32px;font-size: 15px;">
                                    {{$model_item->name}}
                                  </label>

                                  <div class="col-xs-6">
                                    <select
                                    data-edit-attr='bulk_{{$key}}'
                                    data-name='{{$_key}}'
                                    class="form-control"
                                    value="0"
                                    >
                                      <option value="1">Enabled</option>
                                      <option selected value="0">Disabled</option>
                                    </select>
                                  </div>
                                </div>
                                @endforeach

                              </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" onclick="$('#bulk_{{$key}}').modal('hide')" >Dismiss</button>
                              <button type="button" class="btn btn-success"
                              data-edit-json-bulk="bulk_{{$key}}"
                              >Done</button>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!--END MODAL FOR JSON INPUTS  -->


                    @else
                      <input
                      data-name='{{$key}}'
                      type="text"
                      class="form-control"
                      <?php switch ($value) {
                             case 'float':
                              $ex = "1.35";
                              break;
                             case 'string':
                              $ex = "text here";
                              break;
                             case 'integer':
                              $ex = "2";
                              break;
                      }?>
                       placeholder="This should be a {{$value}} eg: {{$ex}}" />
                    @endif
                  </div>

                </div>
                @endforeach
              </ul>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Dismiss</button>
              <button type="button" data-bulk-edit='{{class_basename($model)}}' data-selection={{request()->data}} class="btn btn-success" data-dismiss="modal">Submit</button>
            </div>
          </div>
        </div>
      </div>

  </main>

</body>
@endsection
