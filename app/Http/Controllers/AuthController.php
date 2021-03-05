<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use validator,Redirect,Response;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Session;

class AuthController extends Controller
{
    //
    public function index()
    {
        return view('login');
    }
    public function register()
    {
        return view('register');
    }

    public function postLogin(Request $request)
    {
        request()->validate([
            'email' => 'required',
            'password' => 'required',
            ]);
            $credentials = $request->only('email', 'password');
            if (Auth::attempt($credentials)) {
                // Authentication passed...
                return redirect()->intended('dashboard');
                
            }
            return Redirect::to("login")->withSuccess('Oppes! You have entered invalid credentials');
        
    }

    public function postRegister(Request $request)
    {
        request()->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            ]);
             
            $data = $request->all();

            $create_n = User::Create([
              'name' => $request->name,
              'email' => $request->email,
              'password' => bcrypt("Group.on!@#")
            ]);
     
           // $check = $this->create($data);
           
            return Redirect::to("/")->with('message','Great! You have Successfully loggedin here is your password: Group.on!@#');
    }

    public function dashboard()
    {
 
      if(Auth::check()){
        return view('dashboard');
      }
       return Redirect::to("login")->with('message','Opps! You do not have access');
    }

    public function create(array $data)
    {
      return User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password'])
      ]);
    }

    public function logout() {
        Session::flush();
        Auth::logout();
        return Redirect('login');
    }


}
