<?php

namespace Tsung\NovaUserManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Laravel\Passport\Client;

class PassportController extends Controller
{
    // https://dev.to/azibom/create-api-rest-with-laravel-7-x-passport-authentication-and-implement-refresh-token-part-1-43ja
    private $userModel;

    public function __construct()
    {
        $this->userModel = config('auth.providers.users.model');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|email',
            'password' => 'required',
        ]);

        $user = $this->userModel::where('email', $request->username)->first();

        if ($user
            && $user->is_active
            && $user->can('api')
            && Auth::attempt(['email' => $request->username, 'password' => $request->password])) {

            return $this->getTokenAndRefreshToken($request->username, $request->password);

        }

        return response()->json([
            'success' => false,
            'message' => 'Unauthenticated.',
        ], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function refreshToken(Request $request)
    {
        $request->validate([
            'refresh_token' => 'required',
        ]);

        $passwordClient = Client::where("password_client", true)->first();

        $response = Http::send('POST', env('APP_URL') . '/oauth/token',  [
            'form_params' => [
                'grant_type' => 'refresh_token',
                'refresh_token' => $request->refresh_token,
                'client_id' => $passwordClient->id,
                'client_secret' => $passwordClient->secret,
                'scope' => '*',
            ],
        ]);

        return json_decode((string) $response->getBody(), true);

    }

    public function getTokenAndRefreshToken($username, $password)
    {
        $passwordClient = Client::where("password_client", true)->first();

        $response = Http::send('POST', env('APP_URL') . '/oauth/token',  [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => $passwordClient->id,
                'client_secret' => $passwordClient->secret,
                'username' => $username,
                'password' => $password,
                'scope' => '*',
            ]
        ]);

        $result = json_decode($response->getBody(), true);

        return response()->json([
            'success' => true,
            'data' => $result,
        ], 200);
    }

}
