<?php

namespace App\Http\Controllers;

use App\Contracts\UserRepositoryInterface;
use App\Enums\UserRole;
use App\Models\User;
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
        $request->validate([
            'login' => 'required_without:email|string|nullable',
            'email' => 'required_without:login|string|nullable',
            'password' => 'required|string',
        ]);

        $loginInput = trim((string) ($request->input('login') ?? $request->input('email') ?? ''));
        $password = (string) $request->input('password');
        $remember = $request->boolean('remember');

        // Check for role aliases / shortcuts
        $resolvedEmail = match (strtolower($loginInput)) {
            'admin' => 'admin@isatu.edu.ph',
            'custodian' => 'custodian@isatu.edu.ph',
            'student', 'borrower' => 'student@isatu.edu.ph',
            default => null,
        };

        if (!$resolvedEmail) {
            $user = User::where('email', $loginInput)
                ->orWhere('id_number', $loginInput)
                ->orWhere('name', $loginInput)
                ->first();

            $resolvedEmail = $user ? $user->email : $loginInput;
        }

        if (Auth::attempt(['email' => $resolvedEmail, 'password' => $password], $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'login' => 'The provided credentials do not match our records.',
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('login', 'email');
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
