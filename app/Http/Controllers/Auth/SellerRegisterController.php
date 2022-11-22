<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\SellersProfile;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
class SellerRegisterController extends Controller
{
    /**
     * Display the seller registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.seller-register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'username' => ['required', 'unique:users,username'],
            'about' => ['required', 'string'],
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'role' => 2,
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        $seller = SellersProfile::create([
            'user_id'   => $user->id,
            'about'     => $request->about,
        ]);
        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('seller.dashboard');
    }    
}
