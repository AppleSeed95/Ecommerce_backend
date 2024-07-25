<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menus';

    public $menus = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'parent_id',
        'name',
        'route_name',
        'icon',
        'sort_order',
        'updated_at',
        'created_at',
        'status',
        'expansion',
        'menu_type',
        'seo_url'
    ];

    public function getMenusList(){
        $menus = self::select(
            'menus.id',
            'menus.parent_id',
            'menus.name',
            'menus.route_name',
            'menus.icon',
            'menus.sort_order',
            'menus.updated_at',
            'menus.created_at',
            'menus.status',
            'menus.expansion',
            'parent.display_name as name',
            'menus.menu_type'
        )
        ->where(['menus.is_deleted'=>0])  // 'menus.status'=>1
        ->leftJoin('menus as parent', 'menus.parent_id', '=', 'parent.parent_id')
        ->get();
        return $menus;
    }

    /**
     * Children ralationship
     * */
    public function children(){
        return $this->hasMany(self::class, 'parent_id')
            ->where([
                // 'status'=>1,
                'is_deleted'=>0
            ])
            ->orderBy('sort_order');
    }

    public function getMenus($orderBy){
        $order = ($orderBy == 'updated_at')?'desc':'asc';
        $allMenus = Menu::with('children')
            // ->whereNull('parent_id')
            ->where([
                'is_deleted'=>0
            ])
            ->orderBy($orderBy, $order)
            ->get();
        return $allMenus;
    }

    /**
     * 
     **/
    public function getSlug($str){
        $rawSlug = Str::slug($str);
        // pr($rawSlug);
        $sql = Menu::select('id')->where('seo_url', 'LIKE', $rawSlug.'%')->get()->count();
        if($sql > 0){
            return $rawSlug.'-'.$sql;
        }
    }


    

    public function getMenu($parent, $menuGroup )
    {
        return Menu::select('menus.id', 'menus.parent_id', 'menus.name', 'menus.route_name', 'menus.sort_order', 'menus.status', 'menus.seo_url', 'menus.menu_type')
        ->where(['is_deleted'=>0, 'status'=>1, 'parent_id'=>$parent, 'menu_type'=>$menuGroup])
        ->orderBy('sort_order')
        ->get()->toArray();
    }

    public function getMenuGroups(){
        $menuGroup = Menu::select('menus.menu_type')
        ->where(['is_deleted'=>0, 'status'=>1])
        ->groupBy('menu_type')
        ->orderBy('menu_type', 'ASC')
        ->get()->toArray();

        return array_column( $menuGroup, 'menu_type' );

    }


    public function getMenuTree($parent=0, $menuArray=[], $menuGr = 'Front-end-home')
    {
        $menuArray1 = $this->getMenu($parent, $menuGr);
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
