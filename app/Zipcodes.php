<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Zipcodes extends Model
{
    protected $table = 'zipcodes';
    protected $fillable = ['data'];

    public static function mod_attributes(){
      #List of attributes of model data record that are able to be edited
      #Name must match key in DB and type is correct
      #Available Types: integer,float,string
      return ['CPF'=>"float"];
    }

    public static function updateSingleAttr($request){
      $data = $request->input('inputs');
      $uid = $request->input('uid');

      $mod_attr = ZipCodes::mod_attributes();
      $record = ZipCodes::where('uid',"$uid")->get()[0];
      $record_data = json_decode($record->data);

      foreach($data as $key => $value){
        if( !in_array($key, array_keys($mod_attr)) ){continue;}
        $value = ZipCodes::fixType($value,$mod_attr[$key]);
        $record_data->$key = $value;
      }
        $res = ZipCodes::where('uid',"$uid")->update(['data' => json_encode($record_data)]);

      return( json_encode($res) );
    }

    public static function updateBulkAttr($request){
      // $data = $request->input('inputs');
      // $uid = $request->input('uid');

      dd($request);

      $mod_attr = ZipCodes::mod_attributes();
      $record = ZipCodes::where('uid',"$uid")->get()[0];
      $record_data = json_decode($record->data);

      foreach($data as $key => $value){
        if( !in_array($key, array_keys($mod_attr)) ){continue;}
        $value = ZipCodes::fixType($value,$mod_attr[$key]);
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
