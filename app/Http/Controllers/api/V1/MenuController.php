<?php

namespace App\Http\Controllers\api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Auth;

use App\Models\Menu;
use App\Models\Setting;

class MenuController extends ApiController
{
    //

    public function saveMenu(Request $request){
        // pr($request->all());
        $msg='Menu saved succesfully!';
        $validationRules = [
            'display_name'=>'required|string|max:75',
            'menu_type'=>'required|string',
            'route_name'=>'required|string|max:75',
        ];

        // validation messages
        $validator = Validator::make($request->all(), $validationRules, [
            'display_name.required'=>'Menu name is required.',
            'display_name.max'=>'Menu name can not be greater than 75 chars.',
            'menu_type.required'=>'Menu group is required.',
            'route_name.required'=>'Menu route is required.',
            'route_name.max'=>'Menu route can not exceeds than 75 chars.',
        ]);
        // dump($request->all());

        if( $validator->fails() ){
            return $this->sendError('Validation Error', 206, $validator->errors());
        }

        $menu = new Menu;
        if( $request->has('id') && !empty($request->id)){
            $msg='Menu updated succesfully!';
            $menu = Menu::findOrFail($request->id);
            $menu->updated_by = Auth::id();
            $menu->id = $request->id;
        }
        $menu->created_by = Auth::id();
        $menu->name = $request->display_name;
        $menu->display_name = $request->display_name;
        $menu->menu_type = $request->menu_type;
        $menu->route_name = $request->route_name;
        $menu->visibility = $request->visibilty;
        $menu->icon = $request->icon ?? '';

        // SEO url / slug
        if( !empty( $request->seo_url )){
            $slug = $request->link_type.'/'.$request->seo_url;
            // $slug = $menu->getSlug($slug);
        }
        // dump($request->name);

        $menu->seo_url = $slug ?? '';   
        $menu->sort_order = $request->sort_order ?? 0;
        $menu->expansion = 0;
        $menu->parent_id = $request->parent_id ?? 0;

        if($response = $menu->save()){
            $response = $this->sendResponse($menu, $msg);
        }else{
            $response = $this->sendError($response);
        }
        return $response;
    }

    public function listMenu(Request $request){
        $query = Menu::select(
            'menus.id',
            'menus.parent_id',
            'menus.name',
            'menus.route_name',
            'menus.menu_type',
            'menus.display_name',
            'menus.icon',
            'menus.seo_url',
            'menus.sort_order',
            'menus.updated_at',
            'menus.status',
            'menus.visibility',
            'parent.display_name as name'
        )
        ->leftJoin('menus as parent', 'menus.parent_id', '=', 'parent.id')
        ->where(['menus.is_deleted'=>0]);

        return DataTables::of($query)
        ->addColumn('action', function($row){
            $btn = '<div class="d-flex align-items-center">';
            $btn .= "<button onClick='fillModelBoxFrmData(".$row.")' title='Edit Menu' class='btn btn-primary btn-xs mx-1'><i class='fas fa-pen'></i></button>";
            $btn .= "<button onClick='destroyRecord(".$row->id.",". json_encode(route('api.destroyMenu')) .")' title='Delete Menu' class='btn btn-danger btn-xs mx-1'><i class='fas fa-trash'></i></button>";
            $btn .= '</div>';
            return $btn;
        })
        ->orderColumns(['menus.display_name', 'menus.sort_order'], 'desc')
        ->rawColumns(['action'])
        ->make(true);
    }

    public function distroy(Request $request){
        $menu = Menu::findOrFail($request->menuId);
        $menu->is_deleted = 1;
        if($response = $menu->save()){
            $response = $this->sendResponse($menu, 'Selected menu is deleted.');
        }else{
            $response = $this->sendError($response, 203);
        }
        return $response;
    }


    public function reactMenus(Request $request){
        // $response = Menu::select('menus.id',
        //     'menus.parent_id',
        //     'menus.name',
        //     'menus.route_name',
        //     'menus.sort_order',
        //     'menus.status',
        //     'menus.seo_url',
        //     'menus.menu_type')
        // ->where(['is_deleted'=>0, 'status'=>1, 'menu_type'=>'Front-end-home'])
        // ->get()->toArray();
        // $response1 = $this->buildTree($response);
        // $menuModel = new Menu;

        $setting = new Setting;
        $menuGroup = $setting->getConfig('header_menu_group');
        
        $menu1 = $this->getMenuTree(0, [], $menuGroup);
        // $menu = $this->getChilds($response);
        // pr($menu1);
        // dump($menuModel->menus);
        // exit;
        if($menu1){
            $response = $this->sendResponse($menu1, 'Selected menu is deleted.');
        }else{
            $response = $this->sendError([], 203);
        }
        return $response;
    }


    public function getChilds($menus, $parentId=0, $menuHierarchy=[] ){
        pr($parentId);
        foreach($menus as $i=>$menu){
            if( $menu['parent_id'] == 0){
                $menuHierarchy[$menu['id']] = $menu;
                $menuHierarchy[$menu['id']]['children'] = [];
                unset($menus[$i]);
                $parentId = $menu['id'];
            }else if( $menu['parent_id'] > 0 && $menu['parent_id'] == $parentId){
                $menuHierarchy[$parentId]['children'] = $menu;
                unset($menus[$i]);
            }
            // $menuHierarchy = $this->getChilds($menus, $parentId, $menuHierarchy );
            echo '<hr>';print_r($menus);
        }
        return $menuHierarchy;
    }


    public function getMenuTree($parent=0, $menuArray=[], $menuGr = 'Front-end-home')
    {
        $menuModel = new Menu;
        $menuArray1 = $menuModel->getMenu($parent, $menuGr);
        if(!empty($menuArray1)){
            foreach( $menuArray1 as $i=>$item ){
                if( $item['route_name'] !== '#'){
                    // $item['route_name'] = route($item['route_name']);
                    $item['route_name'] = ($item['route_name']);
                    // dd($item['route_name']);
                }
                $menuArray[$i] = $item;
                $menuArray[$i]['children'] = [];
                if( $parent == $item['parent_id']){
                    $menuArray1[$i]['children'] = $this->getMenuTree($item['id'], $menuArray1, $menuGr);
                }
            }
            return $menuArray1;
        }
    }



}
