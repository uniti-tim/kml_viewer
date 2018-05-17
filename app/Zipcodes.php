<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Request;
use Session;

class Zipcodes extends Model
{
    protected $connection = 'pgsql_ct';
    protected $table = 'zipcodes';
    protected $fillable = ['data'];

    public static function mod_attributes(){
      #List of attributes of model data record that are able to be edited
      #Name must match key in DB and type is correct
      #Available Types: integer,float,string
      return [
        'CPF'=>"float",
        'PROD_LIST'=>"json",
      ];
    }

    public static function friendly_attribute($name, $type = 'name'){
      #This will map out raw attribute names to a more friendly and readable
      #text that is easier for user to understand. Also can map out helper text
      #on the form input
      $friendly_data = [
        'CPF'=>[
          'name' => 'CPF',
          'helper_text' => "This value determines the Cost per foot to build in this Zipcode. Exclude $ sign."
          ],
        'PROD_LIST'=>[
          'name' => "Products",
          'helper_text' => "This shows a list of what standard products are available and if they are enabled.",
          'model' => new Product
        ]
      ];

      if( array_key_exists($name,$friendly_data) ){
        return $friendly_data[$name][$type];
      }else{
        return $name;
      }
    }

}
