<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Zipcodes;
use StreamedResponse;
use Excel;


class GeneratorController extends Controller
{
    public static function excel(Request $request){
      switch ($request->format) {
        case 'table':
          return GeneratorController::makeExportTable($request);
          break;

        case 'tabledata':
          return GeneratorController::makeExportTableWithData($request);
          break;

        default:
          return "Not an option..";
          break;
      }
      if($request->format == 'table'){
      }
    }

    private static function makeExportTable($request){
      $data = json_decode($request->data);
      $name = explode('.',$request->name)[0];

      Excel::create("$name Selection Export", function($excel) use($data,$name) {
        $excel->sheet("Selection", function($sheet) use($data,$name) {
          $sheet->mergeCells('A1:B1');
          $sheet->row(1, [$name,null]);
          $sheet->row(2, ["GIS ID", "NAME"]);
          $sheet->fromArray($data,null,'A3',false,false);
        });
      })->download('xlsx');
    }


    private static function makeExportTableWithData($request){
      $id_data = json_decode($request->data);
      $name = explode('.',$request->name)[0];
      $json_data = json_decode(Zipcodes::find(1)->data);
      $headers = ["GIS ID", "NAME"];
      foreach($json_data as $key => $val){
          $headers[] = $key;
      }

      $uids = [];
      foreach($id_data as $val){
          $uids[] = $val[0]; //get just the UID from the input array
      }

      $items = ZipCodes::whereIn('uid',$uids)->get();
      if( count($items) > 0 ){
        $data = [];
        foreach( $items as $item){
          $item_information = [$item->uid,$item->name];
          $item_json_data = json_decode($item->data);
          foreach( $item_json_data as $key => $val ){
            $val = is_array($val)? implode(",",$val):(string)$val;
            $item_information[] = $val;
          }
          $data[] = $item_information;
        }
      }else{
        return true;
      }

      Excel::create("$name Selection Export", function($excel) use($data,$name,$headers) {
        $excel->sheet("Selection", function($sheet) use($data,$name,$headers) {
          $sheet->mergeCells('A1:B1');
          $sheet->row(1, [$name,null]);
          $sheet->row(2, $headers);
          $sheet->fromArray($data,null,'A3',false,false);
        });
      })->download('xlsx');
    }
}
