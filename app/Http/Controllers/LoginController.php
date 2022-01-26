<?php

namespace App\Http\Controllers;

use Agent;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules\Password as PasswordRule;
use App\Http\Resources\UserResource;

class LoginController extends Controller
{
    /**
     * Create a new user.
     *
     * @return json of User and token
     */
    public function register(Request $request)
    {
        $request->validate([
            // 'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'between:9,255', PasswordRule::min(9)->numbers()],
            'device_name' => ['nullable', 'string', 'max:255'],
        ]);

        $user = User::create([
            'name' => 'No name!',
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $device_name = $request->device_name;

        return response()
            ->json([
                'token' => $user->createToken($device_name)->plainTextToken,
                'user' =>   new UserResource($user)
                ]);
    }

    /**
     * Login a new user.
     *
     * @return token
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);
          
        $user = User::where('email', $request->email)->first();
          
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
              'email' => ['The provided credentials are incorrect.'],
            ]);
        }
          
        return $user->createToken($request->device_name)->plainTextToken;
    }
}
