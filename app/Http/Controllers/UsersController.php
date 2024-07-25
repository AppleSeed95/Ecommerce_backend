<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsersController extends Controller
{
    
    public function __construct(){
        $this->middleware('auth');
    }

    /**
     * Index method
     *
     * @return Illuminate\Http\Request|null
     */
    public function index(Request $request){
        $title = $pageTitle = 'All Users';
        $breadcrumb[] = ['title'=>'Dashboard', 'link'=> route('dashboard') ];
        $breadcrumb[]= ['title'=>'Users', 'link'=>''];
        return view('users.user', compact(['title', 'pageTitle', 'breadcrumb']));
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return Illuminate\Http\Request|null
     * 
     */
    public function view(Request $request){
        $title = 'View User';

        
    }

    /**
     * Add method
     *
     * @return Illuminate\Http\Request|null Redirects on successful add, renders view otherwise.
     */
    public function add(Request $request){
        $title = 'Add User';

        
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return Illuminate\Http\Request|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit(Request $request){
        $title = 'Edit Users';

        
    }

    /**
     * Store method 
     * 
     * 
     * 
     **/
    public function store(Request $request){
        // 


    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return Illuminate\Http\Request|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete(Request $request){
        
    }

    
    /**
     * Status method
     *
     * @param string|null $id Nsp User id.
     * @return Illuminate\Http\Request|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function status(Request $request){
        $this->set(compact('title', 'active'));
        $this->checkPermissions('admin_user', 'view');
        $this->request->allowMethod(['post', 'get']);
        $Users = $this->Users->get($id);
        
        $Users->status = ($Users->status == 0 )?1:0;
        if ($this->Users->save($Users)) {
            $this->Flash->success(__('The user status is changed.'));
        } else {
            $this->Flash->error(__('The user status could not changed. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

}
