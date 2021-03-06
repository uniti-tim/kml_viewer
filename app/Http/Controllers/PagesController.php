<?php

namespace App\Http\Controllers;

use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use App\ZipCodes;
use App\Wireline;
use App\County;

class PagesController extends Controller
{
    public function dashboard(Request $request){

      return view('dash',[
        'name' => 'dash',
        'title' => 'Dashboard',
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
        'data' => $this->paginate($dataset[1],25),
        'error' => $dataset[2]
      ]);
    }


    private function paginate($items,$perPage){
    $pageStart = \Request::get('page', 1);
    $offSet = ($pageStart * $perPage) - $perPage;
    $itemsForCurrentPage = array_slice($items, $offSet, $perPage, true);
    return new LengthAwarePaginator($itemsForCurrentPage, count($items), $perPage,Paginator::resolveCurrentPage(), array('path' => Paginator::resolveCurrentPath()));
    }

    private static function getModel($request){
      switch (request()->model) {

        case 'ZipCodes':
          $_model = new ZipCodes;
          $data = json_decode(request()->data);
          $error = null;
          break;

        case 'Wireline':
          $_model = new Wireline;
          $data = json_decode(request()->data);
          $error = null;
          break;

        case 'County':
          $_model = new County;
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
        return "ZipCodes";
      }else if(strpos(strtolower($kml_name),"ilec") !== false){
        return "Wireline";
      }else if(strpos(strtolower($kml_name),"counties") !== false){
        return "County";
      }
    }
}
