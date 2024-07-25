<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Redirect;
use DB;
use Session;
use Carbon\Carbon;

use App\Models\User;

class SessionExpired
{
    protected $session;
    protected $timeout = 60;
    protected $timeoutAttempts = 1;

    public function __construct(Store $session){
        $this->session = $session;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // if user is not loged in 
        if( !Auth::check()){
            return $next($request);
        }

        $user = Auth::guard()->user();
        // prd($user);
        $now = Carbon::now('Australia/Melbourne');
        $lastSeen = Carbon::parse($user->last_seen_at ?? $now);
        $isLoggedIn = $request->path() != 'login';

        // pr($this->session->get('lastActivityTime'));
        // if( !session('lastActivityTime')){
        //     $this->session->put('lastActivityTime', $lastSeen);
        //     $_SESSION['lastActivityTime'] = $lastSeen;
        // }else{
        //     if( session('lastActivityTime')){
        //         $absence = $now->diffInMinutes($this->session->get('lastActivityTime'));
        //     }elseif( !empty($_SESSION['lastActivityTime']) ){
        //         $absence = $now->diffInMinutes($_SESSION['lastActivityTime']);
        //     }

        //     if( $absence > $this->timeout ){
        //         $this->session->forget('lastActivityTime');
        //         User::find(Auth::id())->update(['api_token'=>Str::random(60)]);
        //         Aut::guard()->logout();
        //         Auth::logout();
        //         $request->session()->invalidate();
        //         return redirect()::to('login');
        //     }

        // pr(session::get('all'));
        //     if( Auth::user()->last_session != Session::getId() ){
        //         User::find(Auth::id())->update(['api_token'=>Str::random(60)]);
        //         Auth::logout();
        //         $request->session()->invalidate();
        //         return redirect::to('login');
        //     }
        // }
        // $isLoggedIn ? $this->session->put('lastActivityTime', $lastSeen): $this->session->forget('lastActivityTime');

        return $next($request);
    }
}
