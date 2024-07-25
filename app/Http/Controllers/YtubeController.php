<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class YtubeController extends Controller
{
    //
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        // if( Auth::user()->can(getMenuPermission())){
            $pageTitle = $title = 'Youtube Video List';

            $breadcrumb[] = ['title'=>'Dashboard', 'link'=> route('dashboard') ];
            $breadcrumb[]= ['title'=>'Youtube Video', 'link'=>''];
            
            return view('ytvideos.index', compact('pageTitle', 'title', 'breadcrumb'));
        // }
    }
}
