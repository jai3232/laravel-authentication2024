<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return redirect()->route('home');
        } else {
            return redirect()->route('login')->with('error', 'Invalid email or password');
        }
    }

    public function home()
    {
        return view('home');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }


    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:4|confirmed',
        ]);

        if ($validator->fails()) {
            // return "XXXX" . $validator->errors();
            return redirect()->route('register')->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);


        Auth::login($user);
        return redirect()->route('login')->with('success', 'Registration successful. Please login');
        // return redirect()->route('home');
    }

    public function showProfileForm()
    {
        return view('auth.profile');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
