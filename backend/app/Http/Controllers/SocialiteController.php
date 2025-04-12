<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller 
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function redirectToFacebook() {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $nameParts = explode(' ', $googleUser->getName(), 2);
        $firstname = $nameParts[0] ?? 'Google';
        $lastname = $nameParts[1] ?? 'User';

        $user = User::firstOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'firstname' => $firstname,
                'lastname' => $lastname,
                'password' => bcrypt(uniqid()),
                'status' => 'active',
            ]
        );

        Auth::login($user);

        return redirect('/discover');
    }


    public function handleFacebookCallback()
    {
        $facebookUser = Socialite::driver('facebook')->stateless()->user();

        $nameParts = explode(' ', $facebookUser->getName(), 2);
        $firstname = $nameParts[0] ?? 'Facebook';
        $lastname = $nameParts[1] ?? 'User';

        $user = User::updateOrCreate(
            ['email' => $facebookUser->getEmail()],
            [
                'firstname' => $firstname,
                'lastname' => $lastname,
                'provider_id' => $facebookUser->getId(),
                'provider_name' => 'facebook',
                'password' => bcrypt(uniqid()),
                'status' => 'active',
            ]
        );

        Auth::login($user);

        return redirect('/discover');
    }
}
