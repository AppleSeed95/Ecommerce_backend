<?php

namespace App\Http\Controllers\api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

use App\Models\Subscription;
use App\Models\ContactUs;

class SubscriptionController extends ApiController
{
    //
    public function save(Request $request){
        
        $inputs = json_decode( file_get_contents('php://input'), 1);
        
        $subscription = new Subscription;
        $subscription->email = $inputs['email'];
        $subscription->save();

        if( !empty($subscription->id) ){
            return $this->sendResponse($subscription, 'Thank you for your submissions!.');
        }
    }


    public function saveContactUs(Request $request){
        
        // var_dump($request->all());
        $contact = new ContactUs;
        $contact->contact_name = $request->name;
        $contact->contact_phone = $request->phone;
        $contact->contact_email = $request->email;
        $contact->contact_install = $request->installation1;
        $contact->contact_install = $request->installation1;
        $contact->zip = $request->postcode;
        $contact->product_name = $request->product;
        $contact->message = $request->message;
        if($contact->save()){
            $response = $this->sendResponse($contact, 'Thank you! The form was submitted successfully.');
        }else{
            $response = $this->sendError('There is some problem! pleasetry again', 203);
        }
        return $response;

    }


    public function contactUs(){
        $query = ContactUs::select()->where(['is_deleted'=>0]);

        return DataTables::of($query)
        ->addColumn('action', function($row){
            $btn = '<div class="d-flex align-items-center">';
            $btn .= '<a href="'.route('bannerFrm', $row->id).'" title="Edit banner" class="btn btn-primary btn-xs mx-1"><i class="fas fa-pen"></i></a>';
            $btn .= "<button onClick='destroyRecord(".$row->id.",". json_encode(route('api.destroyMenu')) .")' title='Delete Banner' class='btn btn-danger btn-xs mx-1'><i class='fas fa-trash'></i></button>";
            $btn .= '</div>';
            return $btn;
        })
        ->orderColumns(['contact_us.created_at', 'contact_us.updated_at'], 'desc')
        ->rawColumns(['action'])
        ->make(true);
    }

    public function subscriptions(Request $request){
        $query = Subscription::select()->where(['status'=>1]);

        return DataTables::of($query)
        ->addColumn('action', function($row){
            $btn = '<div class="d-flex align-items-center">';
            $btn .= '<a href="'.route('bannerFrm', $row->id).'" title="Edit banner" class="btn btn-primary btn-xs mx-1"><i class="fas fa-pen"></i></a>';
            $btn .= "<button onClick='destroyRecord(".$row->id.",". json_encode(route('api.destroyMenu')) .")' title='Delete Banner' class='btn btn-danger btn-xs mx-1'><i class='fas fa-trash'></i></button>";
            $btn .= '</div>';
            return $btn;
        })
        ->orderColumns(['contact_us.created_at', 'contact_us.updated_at'], 'desc')
        ->rawColumns(['action'])
        ->make(true);
    }


}
