<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\User;


class AuthController extends Controller
{
    public function login()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function register()
    {
        return view('auth.register');
    }

    public function handleLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $rememberMe = $request->remember_me;
        $remember = $rememberMe === 'on' ? TRUE : FALSE;

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->route('dashboard');
        }

        return back()->with([
            'status' => 'fail',
            'message' => 'Incorrect credentials.'
        ]);
    }

    public function registerHandle(Request $request)
    {
        $phone = $request->session()->get('phone');
        if ($request->has('otp') && $phone) {
            $existingUser = User::where('phone', $phone)->first();
            if (isset($existingUser)) {
                return back()->with([
                    'status' => 'fail',
                    'message' => "Phone number already registered."
                ]);
            }

            $user = new User;
            $user->phone = $phone;
            $user->save();

            $role = $request->session()->pull('register_role', 'employee');
            if ($role) {
                $user->assignRole($role);
            }

            Auth::login($user);

            return redirect()->route('profile.profession')->with([
                'status' => 'success',
                'message' => 'Registration successful. Please verify your account.'
            ]);
        }

        $validated = $request->validate([
            'phone' => ['required'],
            'role' => ['required', 'in:employer,employee'],
        ]);

        $request->session()->put('phone', $validated['phone']);
        $request->session()->put('register_role', $validated['role']);

        return redirect()->route('register-verify');
    }

    public function registerVerify(Request $request)
    {
        return view('auth.register-verify');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    public function forgotPassword()
    {
        return view('auth.forgot_password');
    }

}
