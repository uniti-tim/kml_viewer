<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ZipCodes;

class PagesController extends Controller
{
    public function home(Request $request){

      return view('home',[
        'name' => 'home',
        'title' => 'Home',
        'kml' => $request->kml,
        'model' => $this->determineModel($request->kml)
      ]);
    }

    public function editor(Request $request){
      $dataset = $this->getModel($request);

      return view('editor',[
        'name' => 'editor',
        'title' => 'Editor',
        'model' => $dataset[0],
        'data' => $dataset[1],
        'error' => $dataset[2]
      ]);
    }

    private static function getModel($request){
      switch (request()->model) {

        case 'ZipCode':
          $_model = new ZipCodes;
          $data = json_decode(request()->data);
          $error = null;
          break;

        default:
          $_model = null;
          $data = null;
          $error = "This Shapefile/Model has not been configured yet for editing.";
          break;
      }
      return [$_model,$data,$error];
    }

    private static function determineModel($kml_name){
      if( strpos(strtolower($kml_name),"zip") !== false ){
        return "ZipCode";
      }
    }
}
