<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wireline extends Model
{
  protected $connection = 'pgsql_ct';
  protected $table = 'wirelines';
  protected $fillable = ['data'];

  public static function mod_attributes(){
    #List of attributes of model data record that are able to be edited
    #Name must match key in DB and type is correct
    #Available Types: integer,float,string
    return ['INTERCONNECT'=>"integer"];
  }

  public static function friendly_attribute($name, $type = 'name'){
    #This will map out raw attribute names to a more friendly and readable
    #text that is easier for user to understand. Also can map out helper text
    #on the form input
    $friendly_data = [
      'INTERCONNECT'=>[
        'name' => 'INTERCONNECT',
        'helper_text' => "This boolean value (1 or 0) determines if voice products are available or ready for this ILEC."
        ],
    ];

    if( array_key_exists($name,$friendly_data) ){
      return $friendly_data[$name][$type];
    }else{
      return $name;
    }
  }

}
