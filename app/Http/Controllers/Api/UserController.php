<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Get the authenticated user's details
     */
    public function me(Request $request)
    {
        return response()->json([
            'user' => $request->user(),
        ]);
    }

    /**
     * Get all users (admin function)
     */
    public function index()
    {
        return response()->json([
            'users' => User::select('id', 'name', 'email', 'created_at')->get()
        ]);
    }

    /**
     * Get a specific user by ID
     */
    public function show($id)
    {
        $user = User::find($id);
        
        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }
        
        return response()->json([
            'user' => $user->only(['id', 'name', 'email', 'created_at'])
        ]);
    }
}