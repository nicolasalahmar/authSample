<?php

namespace App\Http\Controllers\PassportAuth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Laravel\Passport\Client as OClient;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function sendLoginResponse(Request $request)
    {
        $this->clearLoginAttempts($request);

        if ($response = $this->authenticated($request, $this->guard()->user())) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect()->intended($this->redirectPath());
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        return User::where('email',$request->email)->exists()?
            response()->json(['message'=>'false password.']):
            response()->json(['message'=>'false credentials.']);
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    public function logout(Request $request)
    {
        $token = $request->user()->token();

        $token->revoke();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('/');
    }

    protected function loggedOut(Request $request)
    {
        return response()->json(['message'=>'logged out successfuly.']);
    }

    protected function authenticated(Request $request, $user)
    {
        $oClient = OClient::where('password_client', 1)->first();

        $response = Http::asForm()->post('http://localhost:8001/oauth/token', [  //todo i am using this port because serve is not a real server which requires  me to run two servers at all times but this should probably be changed in production
            'grant_type' => 'password',
            'client_id' => $oClient->id,
            'client_secret' => $oClient->secret,
            'username' => $request->email,
            'password' => $request->password,
            'scope' => '*',
        ]);
        return json_decode((string) $response->getBody(), true);
    }

    protected function refreshToken(Request $request)
    {
        $request->validate([
            'refresh_token'=>'required',
        ]);

        //todo we may need to revoke all past tokens in here or we need a really good job to automate the revoking process

        $oClient = OClient::where('password_client', 1)->first();

        $response = Http::asForm()->post('http://localhost:8001/oauth/token', [
            'grant_type' => 'refresh_token',
            'refresh_token' => $request->refresh_token,
            'client_id' => $oClient->id,
            'client_secret' => $oClient->secret,
            'scope' => '*',
        ]);

        return $response->json();
    }
}
