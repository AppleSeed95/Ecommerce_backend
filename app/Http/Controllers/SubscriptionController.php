<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function contactUs()
    {
        // if( Auth::user()->can(getMenuPermission())){
            $pageTitle = $title = 'Contact Us List';

            $breadcrumb[] = ['title'=>'Dashboard', 'link'=> route('dashboard') ];
            $breadcrumb[] = ['title'=>'Contact Us', 'link'=>''];
            // dump([session('')]);exit;
            return view('pages.contact', compact('pageTitle', 'title', 'breadcrumb'));
        // }
    }

    public function subscriptions(Request $request){
        // if( Auth::user()->can(getMenuPermission())){
            $pageTitle = $title = 'Subscriptions';

            $breadcrumb[] = ['title'=>'Dashboard', 'link'=> route('dashboard') ];
            $breadcrumb[] = ['title'=>'Subscriptions', 'link'=>''];
            // dump([session('')]);exit;
            return view('pages.subscriptions', compact('pageTitle', 'title', 'breadcrumb'));
        // }

    }



}
