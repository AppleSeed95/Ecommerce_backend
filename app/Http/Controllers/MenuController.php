<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Config;

use App\Models\Menu;
use App\Models\Category;
use App\Models\Product;
use App\Models\Page;

class MenuController extends Controller
{
    //
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        // if( Auth::user()->can(getMenuPermission())){
            $pageTitle = $title = 'Menu List';
            $menuModel = new Menu;
            // get the menues
            $menuLists = $menuModel->getMenusList();

            $breadcrumb[] = ['title'=>'Dashboard', 'link'=> route('dashboard') ];
            $breadcrumb[]= ['title'=>'Menus', 'link'=>''];

            // get the routes 
            $routeCollection = Route::getRoutes();
            // dd($routeCollection);
            // get the category listing 
            $categoryModel = new Category;
            $productModel = new Product;
            $pageModel = new Page;

            $availableRoutes = [];

            $availableRoutes['categories'] = $categoryModel->getCategoriesMenus();
            $availableRoutes['products'] = $productModel->getProductMenus();
            $availableRoutes['pages'] = $pageModel->getPageMenus();

            foreach($routeCollection as $value){
                // dump($value->getName());
                // dump($value->methods());
                // dump($value->uri());
                if( $value->getName() 
                    // && (Str::contains($value->getName(), 'api.')) 
                    // && (!Str::contains($value->getName(), 'api/')) 
                    && (!Str::contains($value->uri(),'_ignition')) 
                    && ($value->methods()[0] == 'GET') 
                    && (!Str::contains($value->uri(), '/{'))  // to remove the get method parameters form the url
                ){
                    $availableRoutes[] = $value->getName();
                }
            }
            // pr($availableRoutes);exit;
            asort($availableRoutes);
            return view('menus.index', compact('pageTitle', 'title', 'availableRoutes', 'menuLists', 'breadcrumb'));
        // }
    }



}
