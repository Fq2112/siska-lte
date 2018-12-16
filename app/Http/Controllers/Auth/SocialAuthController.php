<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Storage;

class SocialAuthController extends Controller
{

    /**
     * Redirect the user to the social providers authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from each Social Provider.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($provider)
    {
        try {
            $userSocial = Socialite::driver($provider)->user();

            $checkUser = User::where('email', $userSocial->email)->first();

            if (!$checkUser) {
                Storage::disk('local')
                    ->put('public/users/ava/' . $userSocial->getId() . ".jpg", file_get_contents($userSocial->getAvatar()));

                $user = User::firstOrCreate([
                    'ava' => $userSocial->getId() . ".jpg",
                    'email' => $userSocial->getEmail(),
                    'name' => $userSocial->getName(),
                    'password' => bcrypt(str_random(15)),
                ]);

                $user->socialProviders()->create([
                    'provider_id' => $userSocial->getId(),
                    'provider' => $provider
                ]);
                Auth::loginUsingId($user->id);

            } else {
                if ($checkUser->ava == "seeker.png" || $checkUser->ava == "") {
                    Storage::disk('local')
                        ->put('public/users/ava/' . $userSocial->getId() . ".jpg", file_get_contents($userSocial->getAvatar()));

                    $checkUser->update(['ava' => $userSocial->getId() . ".jpg"]);
                }
                Auth::loginUsingId($checkUser->id);
            }

            return redirect()->route('home-seeker')->with('signed', 'You`re now signed in as a Job Seeker.');

        } catch (\Exception $e) {
            return back()->with('unknown', 'Please, login/register with SISKA account.');
        }
    }
}
