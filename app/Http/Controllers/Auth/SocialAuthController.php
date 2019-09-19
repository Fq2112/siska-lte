<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Api\APIController as Credential;
use App\Models\User;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
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
                return back()->with('inactive', 'This email (' . $userSocial->email . ') address doesn\'t have an associated user account. Are you sure you\'ve registered?');

            } else {
                $user = $checkUser;
            }

            if ($user->ava == "seeker.png" || $user->ava == "") {
                Storage::disk('local')
                    ->put('public/users/ava/' . $userSocial->getId() . ".jpg", file_get_contents($userSocial->getAvatar()));

                $user->update(['ava' => $userSocial->getId() . ".jpg"]);
            }

            $response = app(Credential::class)->getCredentials();
            if ($response['isSync'] == true) {
                $data = array('name' => $user->name, 'email' => $user->email, 'password' => $user->password,
                    'provider_id' => $userSocial->getId());
                $client = new Client([
                    'base_uri' => env('SISKA_URI'),
                    'defaults' => [
                        'exceptions' => false
                    ]
                ]);
                $client->post(env('SISKA_URI') . '/api/partners/seekers/' . $provider, [
                    'form_params' => [
                        'key' => env('SISKA_API_KEY'),
                        'secret' => env('SISKA_API_SECRET'),
                        'seeker' => $data,
                    ]
                ]);
            }

            Auth::loginUsingId($user->id);

            return redirect()->route('home-seeker')->with('signed', 'You`re now signed in as a Job Seeker.');

        } catch (\Exception $e) {
            return back()->with('unknown', 'Please, login/register with SISKA account.');
        }
    }
}
