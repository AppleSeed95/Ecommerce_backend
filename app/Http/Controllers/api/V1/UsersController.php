<?php

namespace App\Http\Controllers\api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\User;
use Hash;
use DB;

class UsersController extends ApiController
{
    //
    public function listUsers(Request $request){
        
        $query = User::select('id', 'name', 'email', 'status', 'created_at', 'updated_at')
        ->where(['users.is_deleted'=>0]);

        return DataTables::of($query)
        ->addColumn('action', function($row){
            $btn = '<div class="d-flex align-items-center">';
            // $btn .= '<button onClick="fillModelBoxFrmData('.$row.')" title="Edit User" class="btn btn-primary btn-xs mx-1"><i class="fas fa-pen"></i></button>';
            $btn .="<button onClick='fillModelBoxFrmData(".$row.")' title='Edit User' class='btn btn-primary btn-xs mx-1'><i class='fas fa-pen'></i></button>";
            $btn .= "<button onClick='destroyRecord(".$row->id.",". json_encode(route('api.destroyUser')) .")' title='Delete User' class='btn btn-danger btn-xs mx-1'><i class='fas fa-trash'></i></button>";
            $btn .= '</div>';
            return $btn;
        })
        ->orderColumns(['users.created_at', 'users.updated_at'], 'desc')
        ->rawColumns(['action'])
        ->make(true);
    }


    public function getBanner(Request $request){
        // DB::connection()->enableQueryLog();
        $query = Banner::select(
            'banners.id',
            'banners.banner_name',
            'banners.banner_image',
            'banners.banner_html',
            'banners.banner_group',
            'banners.banner_type',
            'banners.banner_link',
            'banners.sequence',
            'banners.status',
            'banners.is_deleted',
            'banners.created_at',
            'banners.updated_at',
            'cteated.name AS created_by',
            'updated.name AS update_by'
        )
        ->leftJoin('users AS cteated', 'cteated.id', '=', 'banners.created_by')
        ->leftJoin('users AS updated', 'updated.id', '=', 'banners.update_by')
        ->where(['banners.is_deleted'=>0, 'banners.status'=>1]);
        // pr($request->all());
        if(!empty($request->bannerGroup)){
            $query->where('banners.banner_group', $request->bannerGroup);
        }if(!empty($request->bannerType)){
            $query->where('banners.banner_type', $request->bannerType);
        }
        $query->orderBy('banners.sequence', 'ASC');
        $banners = $query->get()->toArray();
        // $queries = DB::getQueryLog();
        foreach($banners as $k=>$banner){
            $banners[$k]['banner_image'] = asset(Storage::url($banner['banner_image']));
        }
        // $banners['sql'] = $queries;
        $response=[];
        // pr($banner);
        if(!empty($banners)){
            $response = $this->sendResponse($banners, 'Banners.');
        }else{
            $response = $this->sendError('No banner found', 200);
        }
        return $response;
    }

    public function save(Request $request){
        // code...
        // dump($request->all());
        $msg='User saved succesfully!';
        $validationRules = [
            'name'=>'required|max:175',
            'email'=>'required|email|unique',
            'password'=>'required_with:password_confirm|same:password_confirm',
            'password_confirm'=>'min:8|max:64',
        ];
        if( !empty($request->id)){
            $validationRules['password'] = 'sometimes|required_with:password_confirm|same:password_confirm';
            $validationRules['password_confirm']='sometimes|min:8|max:64';
        }

        // validation messages
        $validator = Validator::make($request->all(), $validationRules, [
            'name.required'=>'User name is required.',
            'name.max'=>'User name can not be greater than 175 chars.',
            'email.required'=>'User Email is required.',
            'email.unique'=>'Use other Email id.',
            'email.email'=>'Please enter valide email id.'
        ]);

        if( $validator->fails() ){
            return $this->sendError('Validation Error', 206, $validator->errors());
        }
        if (!empty($request->post('id'))) {
            if(!(Hash::check($request->post('password'), Auth::user()->password))){
                // The passwords matches
                return $this->sendError('Validation Error', 206, "Your current password does not matches with the password.");
            }
        }
        $user = new User;
        if( $request->has('id') && !empty($request->id)){
            $msg='User updated succesfully!';
            $user = User::findOrFail($request->id);
            $user->updated_by = Auth::id();
            $user->id = $request->id;
        }else{
            $user->created_by = Auth::id();
        }
        $user->name = $request->name;
        $user->email = $request->email;
        
        if(!empty($request->password) && !empty($request->password_confirm) && $request->password === $request->password_confirm ){
            $user->password = Hash::make($request->post('password'));
            // $user->password = bcrypt($request->post('password'));
        }
        $user->status = $request->status;
        // dd($user);
        if($user->save()){
            $response = $this->sendResponse($user, $msg);
        }else{
            $response = $this->sendError($response);
        }
        return $response;exit;
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

        $record = User::findOrFail($request->menuId);
        $record->is_deleted = 1;
        if($response = $record->save()){
            $response = $this->sendResponse($record, 'Selected user is deleted.');
        }else{
            $response = $this->sendError($response, 203);
        }
        return $response;
    }

}


    