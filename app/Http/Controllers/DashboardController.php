<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function index(Request $request){
        $pageTitle = 'Dashboard';
        $breadcrumb[] = ['link'=>'', 'title'=>'Home'];
        
        return view('dashboard.dashboard', compact('pageTitle', 'breadcrumb'));
    }
}
