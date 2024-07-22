<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Album;
use App\Models\Photo;
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

    public function manageAlbum($id = 0)
    {
        if (\Request::isMethod('post'))
        {
            $post = \Request::post();
            Album::where('id', $post['id'])->update(['title' => $post['title'], 'status' => $post['status']]);
            $id = $post['id'];
        }
        $album = Album::find($id);
        $photos = Photo::where('album_id', $id)->get();
        return view('auth.manageAlbum', compact('album', 'photos'));
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

    public function gallery()
    {
        $albums = Album::where('status', 'public')->get();
        return view('auth.gallery', compact('albums'));
    }

    public function showGallery($id)
    {
        $photos = Photo::where('album_id', $id)->get();
        return view('auth.showGallery2', compact('photos'));
    }

    public function listPhoto($id)
    {
        $albums = Photo::where('album_id', $id)->get();
        return $albums;
        return response()->json(compact('token'))->header("Access-Control-Allow-Origin",  "*");

    }

    public function storePhoto(Request $request)
    {   $request->validate([
            'files' => 'required|array|min:1',
            'files.*' => 'required|file|max:2048' // Adjust validation rules as needed
        ]);
        if($request->hasFile('files'))
            $files = $request->file('files');
        $thumbPath = public_path('thumbnail');
        foreach ($files as $file) {
            $img = imagecreatefromstring(file_get_contents($file->getRealPath()));
            $width = imagesx($img);
            $height = imagesy($img);
            $originalFilename = $file->getClientOriginalName();
            $filename = time() . '-' . $originalFilename;
            if($width > 600 && $height > 600) {
                $tmpImg = imagecreatetruecolor(600, 600);
                imagecopyresampled($tmpImg, $img, 0, 0, 0, 0, 600, 600, $width, $height);
                imagejpeg($tmpImg, $thumbPath.'/'.$filename);
            }
            else
                $file->move($thumbPath, $filename);
            // $file->move(public_path('images'), $filename);
            Photo::create(['url' => $filename, 'album_id' => $request->album_id]);
        }
        // return $originalFilename;
        // return $request;
        // $request->validate([
        //     'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        // ]);
        // return true;// $request->album_id;
        // $imageName = time().'.'.$request->file->extension(); 
        // $request->file->move(public_path('images'), $imageName);
        // Photo::create(['url' => $imageName, 'album_id' => $request->album_id]);

        return response()->json(['success'=>'Image uploaded successfully.']);

        return $_FILES;
    }

    public function deletePhoto(Request $request)
    {
        // return public_path('images').'/'.Photo::find($request->id)->url;
        // unlink(public_path('images').'/'.Photo::find($request->id)->url);
        unlink(public_path('thumbnail').'/'.Photo::find($request->id)->url);
        Photo::find($request->id)->delete();

        return true;
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
