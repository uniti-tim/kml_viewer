<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ZipCodes;

class EditorController extends Controller
{
    # Handle submission of single row edit
    public static function submitSingle(Request $request){
      $data = $request->input('inputs');
      $uid = $request->input('uid');

      $mod_attr = ZipCodes::mod_attributes();
      $record = ZipCodes::where('uid',"$uid")->get()[0];
      $record_data = json_decode($record->data);

      foreach($data as $key => $value){
        if( !in_array($key, array_keys($mod_attr)) ){continue;}
        $value = EditorController::fixType($value,$mod_attr[$key]);
        $record_data->$key = $value;
      }
        $res = ZipCodes::where('uid',"$uid")->update(['data' => json_encode($record_data)]);

      return( json_encode($res) );
    }









    private static function fixType($value,$type){
        switch ($type) {
          case 'integer':
            return (integer)$value;
            break;
          case 'float':
            return (float)$value;
            break;
          case 'string':
            return (string)$value;
            break;
        }
    }
}
