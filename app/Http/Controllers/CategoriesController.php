<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Config;

use App\Models\Category;

class CategoriesController extends Controller
{
    //
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        // if( Auth::user()->can(getCategoryPermission())){
            $pageTitle = $title = 'Category List';
            $menuModel = new Category;
            // get the menues
            // $categoryList = $menuModel->getCategorysList();

            $breadcrumb[] = ['title'=>'Dashboard', 'link'=> route('dashboard') ];
            $breadcrumb[]= ['title'=>'Categories', 'link'=>''];

            return view('categories.index', compact('pageTitle', 'title', 'breadcrumb'));
        // }
    }

}
