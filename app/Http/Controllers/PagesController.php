<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function home(Request $request){
      return view('home',[
        'name' => 'home',
        'title' => 'Home',
        'kml' => $request->kml
      ]);
    }

    public function editor(Request $request){
      return view('editor',[
        'name' => 'editor',
        'title' => 'Editor'
      ]);
    }
}
