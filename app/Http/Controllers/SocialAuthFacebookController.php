<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Services\SocialFacebookAccountService;
use App\SocialFacebookAccount;

class SocialAuthFacebookController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function callback(SocialFacebookAccountService $service)
    {
        $user = $service->createOrGetUser(Socialite::driver('facebook')->user());
        auth()->login($user);
        return redirect()->to('/home');
    }

    public function user(Request $request)
    {
        $id = $request->id;
        $user = SocialFacebookAccount::where('user_id', $id)->get();
        if(count($user) > 0){
            return 'has account';
        }else{
            return 'No FB';
        }
    }
}
