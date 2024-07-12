<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Album;
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
        $credentials = $request->only('username', 'password');
        if (Auth::attempt($credentials)) {
            if (Auth::user()->role == 1) {
                return redirect()->route('user');
            } else
                return redirect()->route('gallery');
        } else {
            return redirect()->route('login')->with('error', 'Invalid username or password');
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
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:4|confirmed',
        ]);

        if ($validator->fails()) {
            // return "XXXX" . $validator->errors();
            return redirect()->route('register')->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => 0,
        ]);


        // Auth::login($user);
        return redirect()->route('login')->with('success', 'Registration successful. Please login');
        // return redirect()->route('home');
    }

    public function showProfileForm()
    {
        return view('auth.profile');
    }

    public function showAlbum()
    {
        $albums = Album::where('user_id', Auth::user()->id)->get();
        return view('auth.album', compact('albums'));
    }

    public function manageAlbum($id)
    {
        $album = Album::find($id);
        return view('auth.manageAlbum', compact('album'));
    }

    public function storeAlbum(Request $request)
    {
        $album = Album::create([
            'title' => $request->title,
            'status' => $request->status,
            'user_id' => Auth::user()->id,
        ]);
        return true;
    }

    public function showGallery()
    {
        return view('auth.gallery');
    }

    public function showUser()
    {
        $users = User::all();
        return view('auth.user', compact('users'));
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
