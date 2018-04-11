<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ZipCodes;

class EditorController extends Controller
{
    # Handle submission of single row edit
    public static function submitSingle(Request $request){
      return  ZipCodes::updateSingleAttr($request);
    }

    #handle bulk updates of fields
    public static function submitBulk(Request $request){
      return  ZipCodes::updateBulkAttr($request);
    }

}
