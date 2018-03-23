<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Zipcodes extends Model
{
    protected $table = 'cost_lookup';
    protected $fillable = ['data'];

    public static function mod_attributes(){
      #List of attributes of model data record that are able to be edited
      #Name must match key in DB and type is correct
      #Available Types: integer,float,string
      return ['CPF'=>"float"];
    }
}
