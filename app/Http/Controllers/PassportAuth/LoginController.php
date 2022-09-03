<?php

namespace App\Http\Controllers\PassportAuth;

use Laravel\Passport\TokenRepository;
use Laravel\Passport\RefreshTokenRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function logout(Request $request)
    {
        $tokenId = $request->user()->token()->id;

        app(TokenRepository::class)->revokeAccessToken($tokenId);
        app(RefreshTokenRepository::class)->revokeRefreshTokensByAccessTokenId($tokenId);

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
        //
    }
}
