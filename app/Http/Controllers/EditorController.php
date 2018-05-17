<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;

class EditorController extends Controller
{
    # Handle submission of single row edit
    public static function submitSingle(Request $request){
      $model = app("App\\".$request->input('model'));
      $data = $request->input('inputs');
      $uid = $request->input('uid');
      $mod_attr = $model::mod_attributes();
      $record = $model::where('uid',"$uid")->get()[0];
      $record_data = json_decode($record->data);

      foreach($data as $key => $value){
        if( !in_array($key, array_keys($mod_attr)) ){continue;}
        if( is_null($value) ){continue;}
        $value = EditorController::fixType($value,$mod_attr[$key]);
        $record_data->$key = $value;
      }

      $res = $model::where('uid',"$uid")->update(['data' => json_encode($record_data)]);
      Session::flash('success',"".$request->input('model')." $uid was updated.");
      return( json_encode($res) );
    }

    #handle bulk updates of fields
    public static function submitBulk(Request $request){
      $model = app("App\\".$request->input('model'));
      $data = $request->input('inputs');
      $selection = $request->input('bulk_selection');
      $mod_attr = $model::mod_attributes();
      $records = $model::whereIn('uid',$selection)->get();

      foreach($records as $record){
        $record_data = json_decode($record->data);
        foreach($data as $key => $value){
          if( !in_array($key, array_keys($mod_attr)) ){continue;}
          if( is_null($value) ){continue;}
          $value = EditorController::fixType($value,$mod_attr[$key]);
          $record_data->$key = $value;
        }
        $record->update(['data' => json_encode($record_data)]);
      }

      Session::flash('success',"All ".$request->input('model')." in selection were updated.");
      return json_encode("Success");
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
          default:
            return (string)$value;
            break;
        }
    }

}
