<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wireline extends Model
{
  protected $table = 'wirelines';
  protected $fillable = ['data'];

  public static function mod_attributes(){
    #List of attributes of model data record that are able to be edited
    #Name must match key in DB and type is correct
    #Available Types: integer,float,string
    return ['INTERCONNECT'=>"integer"];
  }
}
