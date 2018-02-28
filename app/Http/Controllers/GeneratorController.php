<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use StreamedResponse;
use Excel;

class GeneratorController extends Controller
{
    public static function excel(Request $request){
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
}
