<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Bandwidth;

class County extends Model
{
  protected $connection = 'pgsql_ct';
  protected $table = 'counties';
  protected $fillable = ['data'];

  public static function mod_attributes(){
    #List of attributes of model data record that are able to be edited
    #Name must match key in DB and type is correct
    #Available Types: integer,float,string,json
    return [
      'SUB_100'=>"float",
      'SUB_500'=>"float",
      'SUB_1000'=>"float",
      'SUB_2500'=>"float",
      'SUB_10000'=>"float",
      'BAND_LIST'=>"json",
    ];
  }

  public static function friendly_attribute($name, $type = 'name'){
    #This will map out raw attribute names to a more friendly and readable
    #text that is easier for user to understand. Also can map out helper text
    #on the form input as well as define model for the attribute if needed.
    $friendly_data = [
      'SUB_100'=>[
        'name' =>"CPF 0'- 100'",
        'helper_text' => "This is the cost per foot for fiber distances 0 to 100 feet. Exclude $ sign. No value will use the company default value."
        ],
      'SUB_500'=>[
        'name' =>"CPF 100+'- 500'",
        'helper_text' => "This is the cost per foot for fiber distances 100+ to 500 feet. Exclude $ sign. No value will use the company default value."
        ],
      'SUB_1000'=>[
        'name' =>"CPF 500+'- 1000'",
        'helper_text' => "This is the cost per foot for fiber distances 500+ to 1000 feet. Exclude $ sign. No value will use the company default value."
        ],
      'SUB_2500'=>[
        'name' =>"CPF 1000+'- 2500'",
        'helper_text' => "This is the cost per foot for fiber distances 1000+ to 2500 feet. Exclude $ sign. No value will use the company default value."
        ],
      'SUB_10000'=>[
        'name' =>"CPF 2500+'",
        'helper_text' => "This is the cost per foot for fiber distances greater than 2500 feet. Exclude $ sign. No value will use the company default value."
        ],
      'BAND_LIST'=>[
        'name' => "Transports",
        'helper_text' => "This shows a list of what transport products are available and if they are enabled.",
        'model' => new Bandwidth
      ]
    ];

    if( array_key_exists($name,$friendly_data) ){
      return $friendly_data[$name][$type];
    }else{
      return $name;
    }
  }

}
