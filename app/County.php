<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class County extends Model
{
  protected $connection = 'pgsql_ct';
  protected $table = 'counties';
  protected $fillable = ['data'];

  public static function mod_attributes(){
    #List of attributes of model data record that are able to be edited
    #Name must match key in DB and type is correct
    #Available Types: integer,float,string
    return [
      'SUB_100'=>"float",
      'SUB_500'=>"float",
      'SUB_1000'=>"float",
      'SUB_2500'=>"float",
      'SUB_10000'=>"float"
    ];
  }
}
