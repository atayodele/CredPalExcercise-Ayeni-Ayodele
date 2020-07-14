<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Role;

class AuthController extends Controller
{
    public $loginAfterSignUp = true;

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fullname' => 'required|string|min:3|max:30',
            'email' => 'required|string|email|max:200|unique:users',
            'password' => 'required|min:5'
        ], [
            'fullname.required' => 'Fullname is required',
            'email.required' => 'Email is required',
            'password.required' => 'Password is required'
        ]);
        if ($validator->fails())
        {
            return response()->json(['error'=> $validator->messages()->first()], 400);
        }
        $user = User::create([
            'name' => $request->fullname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $role = Role::select('id')->where('name', 'customer')->first();

        $user->roles()->attach($role);

        $token = auth()->login($user);

        return $this->jwtToken($token);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);
        if ($validator->fails())
        {
            return response()->json(['error'=> $validator->messages()->first()], 400);
        }
        $user = User::where('email', $request->email)->first();
        if($user != null){
            if ($token = auth()->attempt(['email' => $request->email, 'password' => $request->password])) {
                return $this->jwtToken($token);
            }else
                return response()->json(['error' => 'Unauthorized'], 401);
        }else
            return response()->json(['error' => 'Invalid Credentials'], 400);
    }

    public function profile(Request $request)
    {
        return response()->json(auth()->user());
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(['message'=>'Successfully logged out']);
    }

    public function refresh()
    {
        return $this->jwtToken(auth()->refresh());
    }

    protected function jwtToken($token)
    {
      return response()->json([
        'access_token' => $token,
        'token_type' => 'bearer',
        'expires_in' => auth()->factory()->getTTL() * 60,
        'email' => auth()->user()->email,
        'role' => auth()->user()->roles()->first()->name
      ]);
    }
}
