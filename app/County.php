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

  public static function friendly_attribute($name, $type = 'name'){
    #This will map out raw attribute names to a more friendly and readable
    #text that is easier for user to understand. Also can map out helper text
    #on the form input
    $friendly_data = [
      'SUB_100'=>[
        'name' =>"CPF 0'- 100'",
        'helper_text' => "This is the cost per foot for fiber distances 0 to 100 feet. Exclude $ sign."
        ],
      'SUB_500'=>[
        'name' =>"CPF 100+'- 500'",
        'helper_text' => "This is the cost per foot for fiber distances 100+ to 500 feet. Exclude $ sign."
        ],
      'SUB_1000'=>[
        'name' =>"CPF 500+'- 1000'",
        'helper_text' => "This is the cost per foot for fiber distances 500+ to 1000 feet. Exclude $ sign."
        ],
      'SUB_2500'=>[
        'name' =>"CPF 1000+'- 2500'",
        'helper_text' => "This is the cost per foot for fiber distances 1000+ to 2500 feet. Exclude $ sign."
        ],
      'SUB_10000'=>[
        'name' =>"CPF 2500+'",
        'helper_text' => "This is the cost per foot for fiber distances greater than 2500 feet. Exclude $ sign."
        ],
    ];

    if( array_key_exists($name,$friendly_data) ){
      return $friendly_data[$name][$type];
    }else{
      return $name;
    }
  }
  
}
