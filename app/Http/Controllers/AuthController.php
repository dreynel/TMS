<?php

namespace App\Http\Controllers;

use App\Contracts\UserRepositoryInterface;
use App\Enums\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct(protected UserRepositoryInterface $userRepo) {}

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'id_number' => 'required|string|unique:users',
            'department_course' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = $this->userRepo->createUser([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => UserRole::BORROWER->value,
            'department_course' => $validated['department_course'],
            'id_number' => $validated['id_number'],
            'phone' => $validated['phone'] ?? null,
            'is_approved' => false, // Requires custodian approval
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Account registered! Your borrower profile is currently pending approval by BIND-Tech Tool Custodians.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
