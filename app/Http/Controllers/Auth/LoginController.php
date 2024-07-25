<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use Illuminate\Support\Facades\AuthenticateWithBasicAuth;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class LoginController extends Controller
{

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct(){
        // $this->middleware('guest')->except('logout');
        $this->middleware('guest')->except([
            'logout'
        ]);
    }

    /**
     * Display a registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function register()
    {
        return view('auth.register');
    }

    /**
     * Store a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:250',
            'email' => 'required|email|max:250|unique:users',
            'password' => 'required|min:8|confirmed'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $credentials = $request->only('email', 'password');
        Auth::attempt($credentials);
        $request->session()->regenerate();
        return redirect()->route('dashboard')
        ->withSuccess('You have successfully registered & logged in!');
    }

    //
    public function index(){

        return view('auth/login');

    }

    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function authenticate(Request $request)
    {
        // dump($request->input());exit;
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
 
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    protected function credentials(Request $request){
        return [
            'email'=> $request->email,
            'password'=> $request->password,
            'status'=>1,
            'is_deleted'=>0
        ];
    }

    protected function authenticated( Request $request)
    {
        $user = User::findOrFail(Auth::id());
        $user->last_session = Session::getId();
        $user->api_token = Str::random(60);
        $user->timestamps= false;
        $user->save();
    }

    /**
     * Log out the user from application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $user = User::findOrFail(Auth::id());
        $user->last_session = Str::random(20);
        $user->api_token = Str::random(60);
        $user->save();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();



        return redirect()->route('login')
            ->withSuccess('You have logged out successfully!');
        // return $request->wantsJson() ?
        //     ? new JsonResponse([], 204)
        //     : redirect()->route('login')->withSuccess('You have logged out successfully!');
    }
}
