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
<?php
  $data = json_decode(request()->data);
 ?>
<body class="hm-gradient">
  <style>
    .modal-backdrop{
      display: none;
    }
  </style>
  <main>
      <!--MDB Tables-->
      <div class="container mt-4">

          <div class="text-left darken-grey-text mb-4">
          </div>

          <div class="card col-xs-12" style="box-shadow: 0px 0px 10px; margin-top:5%">
              <div class="card-body">
                  <!-- Grid row -->
                  <div class="row">
                      <!-- Grid column -->
                      <div class="col-md-12">
                          <h2 class="pt-3 pb-4 text-center font-bold font-up deep-purple-text">Search within table</h2>
                          <div class="input-group md-form form-sm form-2 pl-0">
                              <input class="form-control my-0 py-1 pl-3 purple-border" type="text" placeholder="Search something here..." aria-label="Search">
                              <span class="input-group-addon waves-effect purple lighten-2" id="basic-addon1"><a><i class="fa fa-search white-text" aria-hidden="true"></i></a></span>
                          </div>
                      </div>
                      <!-- Grid column -->
                  </div>
                  <!-- Grid row -->

                  <table class="table table-striped">
                      <thead>
                          <tr>
                              <th class='text-center'>GIS ID</th>
                              <th class='text-center'>Readable Name</th>
                              <th class='text-center'>Attribute Values</th>
                              <th class='text-center'>Edit Attributes</th>
                          </tr>
                      </thead>
                      <tbody>
                        <?php //dd($data); ?>
                          @if(count($data) > 0 )
                            @foreach($data as $record)
                            <tr>
                                <th class='text-center'>{{$record[0]}}</th>
                                <td class='text-center'>{{$record[1]}}</td>
                                <td class='text-center'>
                                  <div class="btn btn-default" data-toggle="modal" data-target="#{{$record[0]}}Attributes">View Attributes</div> <!-- Make Modal to show attributes -->
                                </td>
                                <td class='text-center'>
                                  <div class="btn btn-primary">Edit Attributes</div><!-- Go -->
                                </td>


                                <!-- Modal -->
                                <div class="modal fade" style="margin-top:5%" id="{{$record[0]}}Attributes" tabindex="-1" role="dialog" aria-hidden="true">
                                  <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h3 class="modal-title text-center">Attributes For {{$record[1]}}</h3>
                                      </div>
                                      <div class="modal-body">
                                        <?php $attr = json_decode(App\Zipcodes::where('uid',$record[0])->get()[0]->data); ?>
                                        <ul style="list-style:none">
                                          @foreach($attr as $key => $value)
                                          <?php  $value = is_array($value)? implode(",",$value):(string)$value; ?>
                                            <li>
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

              </div>
          </div>
      </div>

  </main>

</body>
@endsection
