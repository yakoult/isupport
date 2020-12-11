<?php
namespace App\Services;
use App\SocialFacebookAccount;
use App\User;
use Laravel\Socialite\Contracts\User as ProviderUser;
use PhpOption\None;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SocialFacebookAccountService
{
    public function createOrGetUser(ProviderUser $providerUser)
    {
        $account = SocialFacebookAccount::whereProvider('facebook')
            ->whereProviderUserId($providerUser->getId())
            ->first();
        if ($account) {
            return $account->user;
        } else {
            if(Auth::check()){
                $acc = new SocialFacebookAccount([
                    'provider_user_id' => $providerUser->getId(),
                    'provider' => 'facebook',
                    'user_id' => Auth::user()->id
                ]);
                $acc->user()->associate(Auth::user());
                $acc->save();
                return $acc->user;
            }else{
                $account = new SocialFacebookAccount([
                    'provider_user_id' => $providerUser->getId(),
                    'provider' => 'facebook'
                ]);
                $user = User::whereEmail($providerUser->getEmail())->first();
                if (!$user) {
                    $user = User::create([
                        'email' => $providerUser->getEmail(),
                        'name' => $providerUser->getName(),
                        'mobile' => '',
                        'lrn' => '',
                        'type' => '1',
                        'password' => Hash::make('password'),
                    ]);
                }
                $account->user()->associate($user);
                $account->save();
                return $user;
            }
        }
    }
}