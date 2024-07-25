<?php
use App\Http\Controllers\MenuController;
use App\Models\Menu;

if( !function_exists('getMenus') ){
	function getMenus($orderBy = 'updated_at'){
		$menuModel = new Menu;
		$data = $menuModel->getMenus($orderBy);
		return $data;
	}
}


function pr($data=''){
	echo '<pre>';
	print_r($data);
	echo '</pre>';
}

function prd($data=''){
	echo '<pre>';
	print_r($data);
	echo '</pre>';
	die;
}

