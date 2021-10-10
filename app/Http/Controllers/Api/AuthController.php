<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Token;
use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
class AuthController extends Controller
{

    public function login(Request $req)
    {
        $data = $req->validate([
            'email' => ['required', 'email'],
        ]);
        $user = User::whereEmail($data['email'])->first();
        if (!$user) {
            $user = User::create([
                'email' => $data['email']
            ]);
        }
        $user->sendLoginLink($user->createToken('token'));
        return response(['message' => 'Login link has sent to your email address.'], 200);
    }

    public function verifyLogin(Request $request, $token)
    {
        $personalAccessToken = PersonalAccessToken::findToken($token);
        if($personalAccessToken){
            $user = $personalAccessToken->tokenable;
            if($personalAccessToken->last_used_at){
                return response([
                    'message' => 'Link already used',
                ], 401);
            }
            $expiry = Carbon::parse($personalAccessToken->created_at)->addMinutes(env('LINK_EXPIRE_AFTER'));
            if(Carbon::now()->greaterThanOrEqualTo($expiry)) {
                 return response([
                    'message' => 'Link has expired',
                ], 401);
            }
            $personalAccessToken->last_used_at = now();
            $personalAccessToken->save();
            return response([
                'message' => 'Logged In Successfully',
                'data' => [
                    'user' => $user,
                    'token' =>  $token
                ]
            ], 200);
        }
        else{
            return response([
                'message' => 'Invalid Token.',
            ], 401);
        }

    }
}
