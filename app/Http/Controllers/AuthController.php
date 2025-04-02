<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);
        
        // Create token for the user
        $token = $user->createToken('auth_token')->plainTextToken;
        
        // Store token in session for demonstration purposes
        session(['auth_token' => $token]);
        
        return redirect()->route('dashboard');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $request->session()->regenerate();
            
            // Create token for the user
            $token = Auth::user()->createToken('auth_token')->plainTextToken;
            
            // Store token in session for demonstration purposes
            session(['auth_token' => $token]);
            
            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        // Revoke all tokens
        $request->user()->tokens()->delete();
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
    
    public function showLoginForm()
    {
        return view('auth.login');
    }
    
    public function showRegisterForm()
    {
        return view('auth.register');
    }
    
    public function dashboard()
    {
        return view('dashboard');
    }

    public function test()
    {
        return response()->json([
            'message' => 'API is working!',
            'timestamp' => now()->toDateTimeString()
        ]);
    }

    public function testWithTokens(Request $request)
    {
   
    $user = $request->user();
    
    // Get information about the current token
    $token = $request->user()->currentAccessToken();
    
    return response()->json([
        'message' => 'You have successfully accessed a protected endpoint!',
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ],
        'token_info' => [
            'name' => $token->name,
            'created_at' => $token->created_at->toDateTimeString(),
            'last_used_at' => $token->last_used_at ? $token->last_used_at->toDateTimeString() : null,
            'expires_at' => $token->expires_at ? $token->expires_at->toDateTimeString() : 'Never expires',
        ],
        'timestamp' => now()->toDateTimeString()
        ]);
    }
}