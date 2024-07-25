<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Config;

use App\Models\Collection;

class CollectionController extends Controller
{
    //
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        // if( Auth::user()->can(getCollectionPermission())){
            $pageTitle = $title = 'Collection List';
            $menuModel = new Collection;
            // get the menues
            // $categoryList = $menuModel->getCategorysList();

            $breadcrumb[] = ['title'=>'Dashboard', 'link'=> route('dashboard') ];
            $breadcrumb[]= ['title'=>'Collections', 'link'=>''];

            return view('collection.index', compact('pageTitle', 'title', 'breadcrumb'));
        // }
    }

}
