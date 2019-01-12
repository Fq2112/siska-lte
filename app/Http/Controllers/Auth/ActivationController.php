<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Api\APIController as Credential;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ActivationController extends Controller
{
    public function activate(Request $request)
    {
        $user = User::byActivationColumns($request->email, $request->verifyToken)->firstOrFail();

        $user->update([
            'status' => true,
            'verifyToken' => null
        ]);

        $response = app(Credential::class)->getCredentials();
        if ($response['isSync'] == true) {
            $data = array('name' => $user->name, 'email' => $user->email, 'password' => $user->password);
            $client = new Client([
                'base_uri' => env('SISKA_URI'),
                'defaults' => [
                    'exceptions' => false
                ]
            ]);

            $client->post(env('SISKA_URI') . '/api/partners/seekers/create', [
                'form_params' => [
                    'key' => env('SISKA_API_KEY'),
                    'secret' => env('SISKA_API_SECRET'),
                    'seeker' => $data,
                ]
            ]);
        }

        Auth::loginUsingId($user->id);

        return redirect()->route('home-seeker')->with('activated', 'You`re now signed in as Job Seeker.');
    }
}
