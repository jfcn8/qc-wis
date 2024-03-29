<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TempRis;
use Illuminate\Support\Facades\Session;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        if (Auth()->user()->isActive == 0) {
            Auth::logout();
            Session::flush();
            return redirect()->back()->withErrors('You are temporary blocked. please contact to admin'); 
        }

        $request->session()->regenerate();

        $tempris = TempRis::where('user_id', Auth()->user()->id);
        $tempris->delete();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        activity()->disableLogging();
        $tempris = TempRis::where('user_id', Auth()->user()->id);
        $tempris->delete();

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        activity()->enableLogging();

        return redirect('/');
    }
}
