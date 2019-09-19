<?php

namespace App\Http\Controllers\Auth;

use App\Events\Auth\UserActivationEmail;
use App\Models\Attachments;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        if ($request->hasFile('ijazah') && $request->hasFile('transkrip')) {
            $ijazah = $request->file('ijazah')->getClientOriginalName();
            $transkrip = $request->file('transkrip')->getClientOriginalName();

            if ($request->file('ijazah')->isValid() && $request->file('transkrip')->isValid()) {
                $request->ijazah->storeAs('public/users/attachments', $ijazah);
                $request->transkrip->storeAs('public/users/attachments', $transkrip);
            }
        }

        event(new Registered($user = $this->create($request->all(), $ijazah, $transkrip)));

        $this->guard()->login($user);

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'nim' => 'required|string|max:255|unique:users',
            'name' => 'required|string|max:255',
            'ijazah' => 'required|max:1024',
            'transkrip' => 'required|max:1024',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return \App\Models\User
     */
    protected function create(array $data, $ijazah, $transkrip)
    {
        $user = User::create([
            'ava' => 'seeker.png',
            'nim' => $data['nim'],
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'status' => false,
            'verifyToken' => Str::random(255),
        ]);

        Attachments::create([
            'user_id' => $user->id,
            'files' => $ijazah,
        ]);

        Attachments::create([
            'user_id' => $user->id,
            'files' => $transkrip,
        ]);

        return $user;
    }

    /**
     * The user has been registered.
     *
     * @return mixed
     */
    protected function registered()
    {
        $this->guard()->logout();

        return back()->with('register', 'Registered, but your account is pending because we need to check whether your data is valid or not. Please keep an eye on your email, have a nice day!');
    }
}
