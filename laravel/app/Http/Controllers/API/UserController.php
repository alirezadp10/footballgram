<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\Client;

class UserController extends Controller
{
    public function register()
    {
        $validator = Validator::make(request()->all(), [
            'name'     => 'required',
            'email'    => 'required|email|unique:users',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            $response['errors'] = $validator->errors();
            return response()->json($response, 401);
        }
        User::create(array_merge(request()->all(), ['password' => bcrypt(request('password'))]));
        $response['message'] = 'registration successful';
        return response()->json($response, 200);
    }

    public function login()
    {
        $validator = Validator::make(request()->all(), [
            'email'    => 'required|email',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            $response['errors'] = $validator->errors();
            return response()->json($response,401);
        }
        $clientOauthData = Client::where('password_client', 1)->where('name','android')->first();
        $http = new \GuzzleHttp\Client;
        $response = $http->post(env('APP_URL') . '/oauth/token', [
            'form_params' => [
                'grant_type'    => 'password',
                'client_id'     => $clientOauthData->id,
                'client_secret' => $clientOauthData->secret,
                'username'      => request('email'),
                'password'      => request('password'),
                'scope'         => '*',
            ],
        ]);
        $response = json_decode((string) $response->getBody(), true);
        return response()->json($response,200);
    }

    public function logout()
    {
        request()->user()->token()->revoke();
        $response['message'] = "you are log out";
        return response()->json($response,200);
    }

    public function refreshToken()
    {
        $validator = Validator::make(request()->all(), [
            'refreshToken' => 'required',
        ]);
        if ($validator->fails()) {
            $response['errors'] = $validator->errors();
            return response()->json($response,401);
        }
        $clientOauthData = Client::where('password_client', 1)->where('name','android')->first();
        $http = new \GuzzleHttp\Client;
        $response = $http->post(env('APP_URL') . '/oauth/token', [
            'form_params' => [
                'grant_type'    => 'refresh_token',
                'refresh_token' => request('refreshToken'),
                'client_id'     => $clientOauthData->id,
                'client_secret' => $clientOauthData->secret,
                'scope'         => '*',
            ],
        ]);
        $response = json_decode((string) $response->getBody(), true);
        return response()->json($response,200);
    }

    public function details()
    {
        $response = Auth::user();
        return response()->json($response,200);
    }
}