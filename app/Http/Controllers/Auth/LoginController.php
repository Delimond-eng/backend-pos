<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    protected function authenticated(Request $request, $user)
    {
        if ($user->role === 'admin') {
            return redirect()->route('home');
        }

        if ($user->role === 'vendor') {
            return redirect()->route('sale.portal');
        }
        // Valeur par défaut
        return redirect('/');
    }


    protected function credentials(Request $request)
    {
        return [
            'name' => $request->get('name'),
            'password' => $request->get('password'),
        ];
    }


     /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        // Ajouter la validation ici
        $request->validate([
            'name' => 'required|string',
            'password' => 'required|string',
        ]);

        // Tenter la connexion avec les credentials
        if (Auth::attempt(['name' => $request->name, 'password' => $request->password])) {
            // Si la connexion réussit, rediriger vers l'URL d'accueil
            $user = Auth::user();
            if ($user->role === 'admin') {
                return redirect()->route('home');
            }
    
            if ($user->role === 'vendor') {
                return redirect()->route('sale.portal');
            }
            else{
                Auth::logout();
                throw ValidationException::withMessages([
                    'name' => [trans('auth.failed')],
                ]);
            }
        }

        // Si la connexion échoue, renvoyer un message d'erreur
        throw ValidationException::withMessages([
            'name' => [trans('auth.failed')],
        ]);
    }
}