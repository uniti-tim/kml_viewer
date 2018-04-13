<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Redirect;

class KMLManager extends Controller
{
    public static function upload(Request $request){
      $file = $request->file('uploadKML');
      if( strpos(strtolower($file->getClientOriginalName()),'kml') || strpos(strtolower($file->getClientOriginalName()),'kmz') ){
        Storage::disk('local')->putFileAs('public',$file,$file->getClientOriginalName() );
        return Redirect::to("/?kml=".$file->getClientOriginalName());
      }else{
        return Redirect::to('/');
      }
    }
}
