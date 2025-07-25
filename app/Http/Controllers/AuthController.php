<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->with('categories')->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Session::put('user', $user); // เก็บ user ไว้ใน session

            if ($user->role === 'admin') {
                return redirect('/admin');
            } else {
                return redirect('/delivery');
            }
        }

        return back()->withErrors(['email' => 'อีเมลหรือรหัสผ่านไม่ถูกต้อง']);
    }

    public function loginUser(Request $request)
    {
        $request->validate([
            'tel' => 'required|string',
            'password' => 'required'
        ]);

        $user = User::where('tel', $request->tel)->with('categories')->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Session::put('user', $user); // เก็บ user ไว้ใน session

            if ($user->role === 'admin') {
                return redirect('/admin');
            } else {
                return redirect('/delivery');
            }
        }

        return back()->withErrors(['tel' => 'เบอร์โทรศัพท์หรือรหัสผ่านไม่ถูกต้อง']);
    }
    
    public function logout()
    {
        Session::forget('user');
        return redirect('/admin/login');
    }
}