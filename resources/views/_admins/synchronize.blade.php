@extends('layouts.mst')
@section('title', 'Synchronizing Data &ndash; '.env("APP_NAME").' Admins | SISKA &mdash; Sistem Informasi Karier')
@section('content')
    <div class="right_col" role="main">
        <div class="row">
            <div class="col-lg-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2 id="panel_title">Synchronizing Data
                            <small>SISKA Partnership</small>
                        </h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="close-link"><i class="fa fa-times"></i></a></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <img id="image" src="{{asset('images/loading2.gif')}}" class="img-responsive ld ld-fade">
                    <div id="sync-info" class="x_content"></div>
                    <div id="partner-setup" class="x_content" style="display: none;">
                        <button type="button" class="btn btn-sm btn-default" onclick="cancelPartner()">
                            <strong><i class="fa fa-undo-alt"></i>&ensp;CANCEL</strong></button>
                        <div id="wizard_verticle" class="form_wizard wizard_verticle">
                            <ul class="list-unstyled wizard_steps">
                                <li>
                                    <a href="#partnership">
                                        <span class="step_no">1</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#credential">
                                        <span class="step_no">2</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#get-request">
                                        <span class="step_no">3</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#finish">
                                        <span class="step_no">4</span>
                                    </a>
                                </li>
                            </ul>

                            <div id="partnership">
                                <h2 class="StepTitle">Step 1 Partnership</h2>
                                <ol style="text-align: justify;font-size: 15px;">
                                    <li>Buka situs utama <a href="{{env('SISKA_URI').'?q='.env('APP_INSTANSI')}}"
                                                            target="_blank">
                                            <strong>SISKA</strong></a>, lalu klik tombol "<strong>Bermitra Sekarang!
                                        </strong>" untuk melakukan <em>partnership request</em>.
                                    </li>
                                    <li>Isi form <strong>SISKA Partnership</strong> dengan data yang valid mulai dari
                                        nama instansi, email dan nomor telp/hp Anda yang masih aktif,
                                        serta URI (domain) SiskaLTE Anda.
                                    </li>
                                    <li>Tunggu hingga pihak SISKA mengirimkan pesan yang berisi kredensial untuk
                                        <strong>SiskaLTE</strong> instansi Anda melalui email yang telah Anda
                                        masukkan sebelumnya.
                                    </li>
                                </ol>
                            </div>
                            <div id="credential">
                                <h2 class="StepTitle">Step 2 Credential</h2>
                                <ol start="4" style="text-align: justify;font-size: 15px;">
                                    <li>Buka file <code>.env</code> <strong>SiskaLTE</strong> Anda.</li>
                                    <li>Masukkan kredensial Anda pada bagian :
                                        <blockquote>
                                            <code>SISKA_API_KEY=<em>YOUR_API_KEY</em><br>
                                                SISKA_API_SECRET=<em>YOUR_API_SECRET</em>
                                            </code>
                                        </blockquote>
                                    </li>
                                    <li>Jalankan ulang atau <em>refresh</em> server Anda:
                                        <code>php artisan serve</code>.
                                    </li>
                                </ol>
                            </div>
                            <div id="get-request">
                                <h2 class="StepTitle">Step 3 GET&ndash;Request</h2>
                                <ol start="7" style="font-size: 15px;">
                                    <li>Buka file <code>app/Http/Controllers/Api/APIController.php</code>.</li>
                                    <li>Tambahkan code berikut :
                                        <blockquote>
                                            <code>
                                                protected $key, $secret, $client, $uri;<br><br>

                                                public function __construct()<br>
                                                {<br>
                                                <span style="margin-left: 2em">$this->key = env('SISKA_API_KEY');</span><br>
                                                <span style="margin-left: 2em">$this->secret = env('SISKA_API_SECRET');</span><br>
                                                <span style="margin-left: 2em">$this->uri = env('SISKA_URI');</span>
                                                <br><br>
                                                <span style="margin-left: 2em">$this->client = new Client([</span><br>
                                                <span style="margin-left: 4em">'base_uri' => $this->uri,</span><br>
                                                <span style="margin-left: 4em">'defaults' => [</span><br>
                                                <span style="margin-left: 6em">'exceptions' => false</span><br>
                                                <span style="margin-left: 4em">]</span><br>
                                                <span style="margin-left: 2em">]);</span><br>
                                                }<br><br>

                                                public function getCredentials()<br>
                                                {<br>
                                                <span style="margin-left: 2em">try {</span><br>
                                                <span style="margin-left: 4em">$response = $this->client->get($this->uri . '/api/partners?key=' . </span><br>
                                                <span style="margin-left: 7em">$this->key . '&secret=' . $this->secret);</span><br>
                                                <span style="margin-left: 4em">return json_decode($response->getBody(), true);</span>
                                                <br><br>
                                                <span style="margin-left: 2em">} catch (ConnectException $e) {</span><br>
                                                <span style="margin-left: 4em">return $e->getResponse();</span><br>
                                                <span style="margin-left: 2em">}</span><br>
                                                }
                                            </code><br><br>
                                            <em>// here is your other functions&hellip;</em>
                                        </blockquote>
                                    </li>
                                    <li>Jangan lupa untuk <em>import library</em> guzzle Anda :
                                        <blockquote>
                                            <code>use GuzzleHttp\Client;</code>
                                        </blockquote>
                                    </li>
                                </ol>
                            </div>
                            <div id="finish">
                                <h2 class="StepTitle">Step 4 Finish</h2>
                                <ol start="10" style="text-align: justify;font-size: 15px;">
                                    <li>Untuk mengakhiri setup ini dan lanjut ke tahap sinkronisasi data, tekan tombol
                                        "<strong>Finish</strong>" berikut.
                                    </li>
                                    <li>Atas perhatian dan kerjasamanya, terimakasih <i class="fa fa-grin-beam"></i>
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <div id="sync-setup" class="x_content" style="display: none;">
                        <button id="sync-cancel" type="button" class="btn btn-default btn-sm" onclick="cancelSync()">
                            <strong><i class="fa fa-undo-alt"></i>&ensp;CANCEL</strong></button>
                        <div id="wizard" class="form_wizard wizard_verticle">
                            <ul class="list-unstyled wizard_steps">
                                <li>
                                    <a href="#route">
                                        <span class="step_no">1</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#sync-seeker-1">
                                        <span class="step_no">2</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#sync-seeker-2">
                                        <span class="step_no">3</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#sync-vacancy-1">
                                        <span class="step_no">4</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#sync-vacancy-2">
                                        <span class="step_no">5</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#finish">
                                        <span class="step_no">6</span>
                                    </a>
                                </li>
                            </ul>

                            <div id="route">
                                <h2 class="StepTitle">Step 1 Route</h2>
                                <p style="text-align: justify;font-size: 15px;">Selain sinkronisasi data lowongan, Anda
                                    juga perlu melakukan
                                    <em>synchronize setup</em> untuk data job seeker. Dengan begitu, setelah seeker
                                    membuat akun melalui SiskaLTE instansi Anda maka datanya juga akan disimpan
                                    ke dalam database SISKA dan apabila seeker tersebut membuat akun
                                    melalui SISKA maka datanya hanya akan tersimpan di dalam database SISKA.
                                </p>
                                <ol style="font-size: 15px;">
                                    <li>Buka file <code>routes/api.php</code>.</li>
                                    <li>Tambahkan code berikut:
                                        <blockquote>
                                            <code>
                                                $router->group(['prefix'=>'SISKA', 'middleware'=>'partner'],
                                                function($router){<br>
                                                <span style="margin-left: 2em">$router->group(['prefix' => 'seekers'], function ($router) {</span><br>
                                                <span style="margin-left: 4em">$router->put('update', 'APIController@updateSeekers');</span><br>
                                                <span style="margin-left: 4em">$router->delete('delete', 'APIController@deleteSeekers');</span><br>
                                                <span style="margin-left: 2em">});</span><br><br>

                                                <span style="margin-left: 2em">$router->group(['prefix' => 'vacancies'], function ($router) {</span><br>
                                                <span style="margin-left: 4em">$router->post('create', 'APIController@createVacancies');</span><br>
                                                <span style="margin-left: 4em">$router->put('update', 'APIController@updateVacancies');</span><br>
                                                <span style="margin-left: 4em">$router->delete('delete', 'APIController@deleteVacancies');</span><br>
                                                <span style="margin-left: 2em">});</span><br>
                                                });
                                            </code><br><br>
                                            <em>// here is your other routes&hellip;</em>
                                        </blockquote>
                                    </li>
                                </ol>
                            </div>
                            <div id="sync-seeker-1">
                                <h2 class="StepTitle">Step 2 Sync&ndash;Seeker <sub>(Response)</sub></h2>
                                <ol start="3" style="font-size: 15px;">
                                    <li>Buka file <code>app/Http/Controllers/Api/APIController.php</code>.</li>
                                    <li>Tambahkan code berikut:
                                        <blockquote>
                                            <em>// here is your credentials functions&hellip;</em><br><br>
                                            <code>
                                                public function updateSeekers(Request $request)<br>
                                                {<br>
                                                <span style="margin-left: 2em">$data = $request->seeker;</span><br>
                                                <span style="margin-left: 2em">$user = User::where('email', $data['email'])->first();</span><br>
                                                <span style="margin-left: 2em">if ($user != null) {</span><br>
                                                <span style="margin-left: 4em">if ($request->check_form == 'password') {</span><br>
                                                <span style="margin-left: 6em">$user->update(['password' => $data['password']]);</span><br><br>

                                                <span style="margin-left: 4em">} elseif ($request->check_form == 'contact') {</span><br>
                                                <span style="margin-left: 6em">$user->update([</span><br>
                                                <span style="margin-left: 8em">'phone' => $data['input']['phone'],</span><br>
                                                <span style="margin-left: 8em">'address' => $data['input']['address'],</span><br>
                                                <span style="margin-left: 8em">'zip_code' => $data['input']['zip_code'],</span><br>
                                                <span style="margin-left: 6em">]);</span><br><br>

                                                <span style="margin-left: 4em">} elseif ($request->check_form == 'personal') {</span><br>
                                                <span style="margin-left: 6em">$user->update([</span><br>
                                                <span style="margin-left: 8em">'name' => $data['input']['name'],</span><br>
                                                <span style="margin-left: 8em">'birthday' => $data['input']['birthday'],</span><br>
                                                <span style="margin-left: 8em">'gender' => $data['input']['gender'],</span><br>
                                                <span style="margin-left: 8em">'relationship' => $data['input']['relationship'],</span><br>
                                                <span style="margin-left: 8em">'nationality' => $data['input']['nationality'],</span><br>
                                                <span style="margin-left: 8em">'website' => $data['input']['website'],</span><br>
                                                <span style="margin-left: 8em">'lowest_salary' => str_replace(',', '', $data['input']['lowest']),</span><br>
                                                <span style="margin-left: 8em">'highest_salary' => str_replace(',', '', $data['input']['highest']),</span><br>
                                                <span style="margin-left: 6em">]);</span><br><br>

                                                <span style="margin-left: 4em">} elseif ($request->check_form == 'summary') {</span><br>
                                                <span style="margin-left: 6em">$user->update(['summary' => $data['summary']]);</span><br>
                                                <span style="margin-left: 4em">}</span><br>
                                                <span style="margin-left: 2em">}</span><br>
                                                }<br><br>

                                                public function deleteSeekers(Request $request)<br>
                                                {<br>
                                                <span style="margin-left: 2em">$user = User::where('email', $request->email)->first();</span><br>
                                                <span style="margin-left: 2em">if ($user != null) {</span><br>
                                                <span style="margin-left: 4em">$user->forceDelete();</span><br>
                                                <span style="margin-left: 2em">}</span><br>
                                                }
                                            </code><br><br>
                                            <em>// here is your other functions&hellip;</em>
                                        </blockquote>
                                    </li>
                                    <li>Jangan lupa untuk <em>import model</em> user Anda :
                                        <blockquote>
                                            <code>use App\Models\User;</code>
                                        </blockquote>
                                    </li>
                                </ol>
                            </div>
                            <div id="sync-seeker-2">
                                <h2 class="StepTitle">Step 3 Sync&ndash;Seeker <sub>(Request)</sub></h2>
                                <ol start="6" style="font-size: 15px;">
                                    <li>Buka file <code>app/Http/Controllers/Auth/ActivationController.php</code> dan
                                        tambahkan <em>POST&ndash;Request</em> seperti code berikut:
                                        <blockquote>
                                            <code>
                                                public function activate(Request $request)<br>
                                                {<br>
                                                <span style="margin-left: 2em"><em>// here is your activation code&hellip;</em></span><br><br>
                                                <span style="margin-left: 2em">$response = app(Credential::class)->getCredentials();</span><br>
                                                <span style="margin-left: 2em">if ($response['isSync'] == true) {</span><br>
                                                <span style="margin-left: 4em">$data = array('name' => $user->name, 'email' => $user->email,</span><br>
                                                <span style="margin-left: 6em">'password' => $user->password);</span><br>
                                                <span style="margin-left: 4em">$client = new Client([</span><br>
                                                <span style="margin-left: 6em">'base_uri' => env('SISKA_URI'),</span><br>
                                                <span style="margin-left: 6em">'defaults' => [</span><br>
                                                <span style="margin-left: 8em">'exceptions' => false</span><br>
                                                <span style="margin-left: 6em">]</span><br>
                                                <span style="margin-left: 4em">]);</span><br><br>

                                                <span style="margin-left: 4em">$client->post(env('SISKA_URI') . '/api/partners/seekers/create', [</span><br>
                                                <span style="margin-left: 6em">'form_params' => [</span><br>
                                                <span style="margin-left: 8em">'key' => env('SISKA_API_KEY'),</span><br>
                                                <span style="margin-left: 8em">'secret' => env('SISKA_API_SECRET'),</span><br>
                                                <span style="margin-left: 8em">'seeker' => $data,</span><br>
                                                <span style="margin-left: 6em">]</span><br>
                                                <span style="margin-left: 4em">]);</span><br>
                                                <span style="margin-left: 2em">}</span><br><br>
                                                <span style="margin-left: 2em"><em>// other codes&hellip;</em></span><br>
                                                }
                                            </code>
                                        </blockquote>
                                    </li>
                                    <li>Buka file <code>app/Http/Controllers/Auth/SocialAuthController.php</code> dan
                                        tambahkan <em>POST&ndash;Request</em> seperti code berikut:
                                        <blockquote>
                                            <code>
                                                public function handleProviderCallback($provider)<br>
                                                {<br>
                                                <span style="margin-left: 2em"><em>// here is your socialite code&hellip;</em></span><br><br>
                                                <span style="margin-left: 2em">$response = app(Credential::class)->getCredentials();</span><br>
                                                <span style="margin-left: 2em">if ($response['isSync'] == true) {</span><br>
                                                <span style="margin-left: 4em">$data = array('name' => $user->name, 'email' => $user->email,</span><br>
                                                <span style="margin-left: 6em">'password' => $user->password, 'provider_id' => $userSocial->getId());</span><br>
                                                <span style="margin-left: 4em">$client = new Client([</span><br>
                                                <span style="margin-left: 6em">'base_uri' => env('SISKA_URI'),</span><br>
                                                <span style="margin-left: 6em">'defaults' => [</span><br>
                                                <span style="margin-left: 8em">'exceptions' => false</span><br>
                                                <span style="margin-left: 6em">]</span><br>
                                                <span style="margin-left: 4em">]);</span><br><br>

                                                <span style="margin-left: 4em">$client->post(env('SISKA_URI') . '/api/partners/seekers/' . $provider, [</span><br>
                                                <span style="margin-left: 6em">'form_params' => [</span><br>
                                                <span style="margin-left: 8em">'key' => env('SISKA_API_KEY'),</span><br>
                                                <span style="margin-left: 8em">'secret' => env('SISKA_API_SECRET'),</span><br>
                                                <span style="margin-left: 8em">'seeker' => $data,</span><br>
                                                <span style="margin-left: 6em">]</span><br>
                                                <span style="margin-left: 4em">]);</span><br>
                                                <span style="margin-left: 2em">}</span><br><br>
                                                <span style="margin-left: 2em"><em>// other codes&hellip;</em></span><br>
                                                }
                                            </code>
                                        </blockquote>
                                    </li>
                                    <li>Buka file <code>app/Http/Controllers/Auth/ResetPasswordController.php</code> dan
                                        tambahkan <em>PUT&ndash;Request</em> seperti code berikut:
                                        <blockquote>
                                            <code>
                                                protected function resetPassword($user, $password)<br>
                                                {<br>
                                                <span style="margin-left: 2em"><em>// here is your reset password code&hellip;</em></span><br><br>
                                                <span style="margin-left: 2em">if($guard == 'web') {</span><br>
                                                <span style="margin-left: 4em">$findUser = User::find($user->id);</span><br>
                                                <span style="margin-left: 4em">$response = app(Credential::class)->getCredentials();</span><br>
                                                <span style="margin-left: 4em">if ($response['isSync'] == true) {</span><br>
                                                <span style="margin-left: 5em">$data = array('email' => $findUser->email,</span><br>
                                                <span style="margin-left: 7em">'password' => $findUser->password);</span><br>
                                                <span style="margin-left: 5em">$client = new Client([</span><br>
                                                <span style="margin-left: 7em">'base_uri' => env('SISKA_URI'),</span><br>
                                                <span style="margin-left: 7em">'defaults' => [</span><br>
                                                <span style="margin-left: 9em">'exceptions' => false</span><br>
                                                <span style="margin-left: 7em">]</span><br>
                                                <span style="margin-left: 5em">]);</span><br><br>

                                                <span style="margin-left: 5em">$client->put(env('SISKA_URI') . '/api/partners/seekers/update', [</span><br>
                                                <span style="margin-left: 7em">'form_params' => [</span><br>
                                                <span style="margin-left: 9em">'key' => env('SISKA_API_KEY'),</span><br>
                                                <span style="margin-left: 9em">'secret' => env('SISKA_API_SECRET'),</span><br>
                                                <span style="margin-left: 9em">'check_form' => 'password',</span><br>
                                                <span style="margin-left: 9em">'seeker' => $data,</span><br>
                                                <span style="margin-left: 7em">]</span><br>
                                                <span style="margin-left: 5em">]);</span><br>
                                                <span style="margin-left: 4em">}</span><br>
                                                <span style="margin-left: 2em">}</span><br><br>
                                                <span style="margin-left: 2em"><em>// other codes&hellip;</em></span><br>
                                                }
                                            </code>
                                        </blockquote>
                                    </li>
                                    <li>Buka file <code>app/Http/Controllers/Seekers/AccountController.php</code> dan
                                        tambahkan <em>PUT&ndash;Request</em> seperti code berikut:
                                        <blockquote>
                                            <em>// here is your other functions&hellip;</em><br><br>
                                            <code>
                                                public function updateProfile(Request $request)<br>
                                                {<br>
                                                <span style="margin-left: 2em">if ($check == 'contact') {</span><br>
                                                <span style="margin-left: 4em"><em>// here is your seeker update contact query code&hellip;</em></span><br>
                                                <span style="margin-left: 4em">$data = array('email' => $user->email, 'input' => $input);</span><br>
                                                <span style="margin-left: 4em">$this->updatePartners($data, 'contact');</span><br><br>

                                                <span style="margin-left: 2em">} elseif ($check == 'personal') {</span><br>
                                                <span style="margin-left: 4em"><em>// here is your seeker update personal query code&hellip;</em></span><br>
                                                <span style="margin-left: 4em">$data = array('email' => $user->email, 'input' => $input);</span><br>
                                                <span style="margin-left: 4em">$this->updatePartners($data, 'personal');</span><br><br>

                                                <span style="margin-left: 2em">} elseif ($check == 'summary') {</span><br>
                                                <span style="margin-left: 4em"><em>// here is your seeker update summary query code&hellip;</em></span><br>
                                                <span style="margin-left: 4em">$data = array('email' => $user->email, 'summary' => $user->summary);</span><br>
                                                <span style="margin-left: 4em">$this->updatePartners($data, 'summary');</span><br>
                                                <span style="margin-left: 2em">}</span><br><br>
                                                <span style="margin-left: 2em"><em>// other codes&hellip;</em></span><br>
                                                }<br><br>

                                                public function updateAccount(Request $request)<br>
                                                {<br>
                                                <span style="margin-left: 2em"><em>// here is your seeker update password query code&hellip;</em></span><br><br>
                                                <span style="margin-left: 2em">$data = array('email' => $user->email, 'password' => $user->password);</span><br>
                                                <span style="margin-left: 2em">$this->updatePartners($data, 'password');</span><br><br>
                                                <span style="margin-left: 2em"><em>// other codes&hellip;</em></span><br>
                                                }<br><br>

                                                private function updatePartners($data, $check)<br>
                                                {<br>
                                                <span style="margin-left: 2em">$response = app(Credential::class)->getCredentials();</span><br>
                                                <span style="margin-left: 2em">if ($response['isSync'] == true) {</span><br>
                                                <span style="margin-left: 4em">$client = new Client([</span><br>
                                                <span style="margin-left: 6em">'base_uri' => env('SISKA_URI'),</span><br>
                                                <span style="margin-left: 6em">'defaults' => [</span><br>
                                                <span style="margin-left: 8em">'exceptions' => false</span><br>
                                                <span style="margin-left: 6em">]</span><br>
                                                <span style="margin-left: 4em">]);</span><br><br>

                                                <span style="margin-left: 4em">$client->put(env('SISKA_URI') . '/api/partners/seekers/update', [</span><br>
                                                <span style="margin-left: 6em">'form_params' => [</span><br>
                                                <span style="margin-left: 8em">'key' => env('SISKA_API_KEY'),</span><br>
                                                <span style="margin-left: 8em">'secret' => env('SISKA_API_SECRET'),</span><br>
                                                <span style="margin-left: 8em">'check_form' => $check,</span><br>
                                                <span style="margin-left: 8em">'seeker' => $data,</span><br>
                                                <span style="margin-left: 6em">]</span><br>
                                                <span style="margin-left: 4em">]);</span><br>
                                                <span style="margin-left: 2em">}</span><br>
                                                }
                                            </code><br><br>
                                            <em>// here is your other functions&hellip;</em>
                                        </blockquote>
                                    </li>
                                    <li>Buka file
                                        <code>app/Http/Controllers/Admins/DataMaster/AccountsController.php</code> dan
                                        tambahkan <em>DELETE&ndash;Request</em> seperti code berikut:
                                        <blockquote>
                                            <em>// here is your other functions&hellip;</em><br><br>
                                            <code>
                                                public function deleteUsers($id)<br>
                                                {<br>
                                                <span style="margin-left: 2em"><em>// here is your delete query code&hellip;</em></span><br><br>
                                                <span style="margin-left: 2em">$response = app(Credential::class)->getCredentials();</span><br>
                                                <span style="margin-left: 2em">if ($response['isSync'] == true) {</span><br>
                                                <span style="margin-left: 4em">$client = new Client([</span><br>
                                                <span style="margin-left: 6em">'base_uri' => env('SISKA_URI'),</span><br>
                                                <span style="margin-left: 6em">'defaults' => [</span><br>
                                                <span style="margin-left: 8em">'exceptions' => false</span><br>
                                                <span style="margin-left: 6em">]</span><br>
                                                <span style="margin-left: 4em">]);</span><br><br>

                                                <span style="margin-left: 4em">$client->delete(env('SISKA_URI') . '/api/partners/seekers/delete', [</span><br>
                                                <span style="margin-left: 6em">'form_params' => [</span><br>
                                                <span style="margin-left: 8em">'key' => env('SISKA_API_KEY'),</span><br>
                                                <span style="margin-left: 8em">'secret' => env('SISKA_API_SECRET'),</span><br>
                                                <span style="margin-left: 8em">'email' => $user->email,</span><br>
                                                <span style="margin-left: 6em">]</span><br>
                                                <span style="margin-left: 4em">]);</span><br>
                                                <span style="margin-left: 2em">}</span><br><br>
                                                <span style="margin-left: 2em"><em>// other codes&hellip;</em></span><br>
                                                }
                                            </code><br><br>
                                            <em>// here is your other functions&hellip;</em>
                                        </blockquote>
                                    </li>
                                    <li>Jangan lupa untuk <em>import library</em> guzzle Anda dan tambahkan code berikut
                                        :
                                        <blockquote>
                                            <code>
                                                use GuzzleHttp\Client;<br>
                                                use App\Http\Controllers\Api\APIController as Credential;
                                            </code>
                                        </blockquote>
                                    </li>
                                </ol>
                            </div>
                            <div id="sync-vacancy-1">
                                <h2 class="StepTitle">Step 4 Sync&ndash;Vacancy <sub>(Response)</sub></h2>
                                <ol start="12" style="font-size: 15px;">
                                    <li>Masih di dalam file <code>app/Http/Controllers/Api/APIController.php</code>,
                                        tambahkan code berikut:
                                        <blockquote>
                                            <em>// here is your sync seekers functions&hellip;</em><br><br>
                                            <code>
                                                public function createVacancies(Request $request)<br>
                                                {<br>
                                                <span style="margin-left: 2em">$vacancies = $request->vacancies;</span><br>
                                                <span style="margin-left: 2em">foreach ($vacancies as $data) {</span><br>
                                                <span style="margin-left: 4em">$checkAgency = Agencies::where('email', $data['agency']['email'])</span><br>
                                                <span style="margin-left: 6em">->first();</span><br>
                                                <span style="margin-left: 4em">if (!$checkAgency) {</span><br>
                                                <span style="margin-left: 6em">$agency = Agencies::firstOrCreate([</span><br>
                                                <span style="margin-left: 8em">'ava' => 'agency.png',</span><br>
                                                <span style="margin-left: 8em">'email' => $data['agency']['email'],</span><br>
                                                <span style="margin-left: 8em">'company' => $data['agency']['company'],</span><br>
                                                <span style="margin-left: 8em">'kantor_pusat' => $data['agency']['kantor_pusat'],</span><br>
                                                <span style="margin-left: 8em">'industry_id' => $data['agency']['industry_id'],</span><br>
                                                <span style="margin-left: 8em">'tentang' => $data['agency']['tentang'],</span><br>
                                                <span style="margin-left: 8em">'alasan' => $data['agency']['alasan'],</span><br>
                                                <span style="margin-left: 8em">'link' => $data['agency']['link'],</span><br>
                                                <span style="margin-left: 8em">'alamat' => $data['agency']['alamat'],</span><br>
                                                <span style="margin-left: 8em">'phone' => $data['agency']['phone'],</span><br>
                                                <span style="margin-left: 8em">'hari_kerja' => $data['agency']['hari_kerja'],</span><br>
                                                <span style="margin-left: 8em">'jam_kerja' => $data['agency']['jam_kerja'],</span><br>
                                                <span style="margin-left: 8em">'lat' => $data['agency']['lat'],</span><br>
                                                <span style="margin-left: 8em">'long' => $data['agency']['long'],</span><br>
                                                <span style="margin-left: 8em">'isSISKA' => $data['agency']['isSISKA']</span><br>
                                                <span style="margin-left: 6em">]);</span><br>
                                                <span style="margin-left: 4em">} else {</span><br>
                                                <span style="margin-left: 6em">$agency = $checkAgency;</span><br>
                                                <span style="margin-left: 4em">}</span><br><br>

                                                <span style="margin-left: 4em">Vacancies::create([</span><br>
                                                <span style="margin-left: 6em">'judul' => $data['judul'],</span><br>
                                                <span style="margin-left: 6em">'city_id' => $data['cities_id'],</span><br>
                                                <span style="margin-left: 6em">'syarat' => $data['syarat'],</span><br>
                                                <span style="margin-left: 6em">'tanggungjawab' => $data['tanggungjawab'],</span><br>
                                                <span style="margin-left: 6em">'pengalaman' => $data['pengalaman'],</span><br>
                                                <span style="margin-left: 6em">'jobtype_id' => $data['jobtype_id'],</span><br>
                                                <span style="margin-left: 6em">'joblevel_id' => $data['joblevel_id'],</span><br>
                                                <span style="margin-left: 6em">'industry_id' => $data['industry_id'],</span><br>
                                                <span style="margin-left: 6em">'salary_id' => $data['salary_id'],</span><br>
                                                <span style="margin-left: 6em">'agency_id' => $agency->id,</span><br>
                                                <span style="margin-left: 6em">'degree_id' => $data['tingkatpend_id'],</span><br>
                                                <span style="margin-left: 6em">'major_id' => $data['jurusanpend_id'],</span><br>
                                                <span style="margin-left: 6em">'jobfunction_id' => $data['fungsikerja_id'],</span><br>
                                                <span style="margin-left: 6em">'isPost' => true,</span><br>
                                                <span style="margin-left: 4em">]);</span><br>
                                                <span style="margin-left: 2em">}</span><br>
                                                }<br><br>

                                                public function updateVacancies(Request $request)<br>
                                                {<br>
                                                <span style="margin-left: 2em">$data = $request->agencies;</span><br>
                                                <span style="margin-left: 2em">$agency = Agencies::where('email', $data['email'])->first();</span><br>
                                                <span style="margin-left: 2em">if ($agency != null) {</span><br>
                                                <span style="margin-left: 4em">if ($request->check_form == 'personal_data') {</span><br>
                                                <span style="margin-left: 5em">$agency->update([</span><br>
                                                <span style="margin-left: 7em">'company' => $data['input']['name'],</span><br>
                                                <span style="margin-left: 7em">'kantor_pusat' => $data['input']['kantor_pusat'],</span><br>
                                                <span style="margin-left: 7em">'industry_id' => $data['input']['industri_id'],</span><br>
                                                <span style="margin-left: 7em">'link' => $data['input']['link'],</span><br>
                                                <span style="margin-left: 7em">'phone' => $data['input']['phone'],</span><br>
                                                <span style="margin-left: 7em">'hari_kerja' => $data['input']['start_day'] . ' - ' . </span><br>
                                                <span style="margin-left: 9em">$data['input']['end_day'],</span><br>
                                                <span style="margin-left: 7em">'jam_kerja' => $data['input']['start_time'] . ' - ' . </span><br>
                                                <span style="margin-left: 9em">$data['input']['end_time'],</span><br>
                                                <span style="margin-left: 5em">]);</span><br><br>

                                                <span style="margin-left: 4em">} elseif ($request->check_form == 'address') {</span><br>
                                                <span style="margin-left: 5em">$agency->update([</span><br>
                                                <span style="margin-left: 7em">'alamat' => $data['input']['alamat'],</span><br>
                                                <span style="margin-left: 7em">'lat' => $data['input']['lat'],</span><br>
                                                <span style="margin-left: 7em">'long' => $data['input']['long'],</span><br>
                                                <span style="margin-left: 5em">]);</span><br><br>

                                                <span style="margin-left: 4em">} elseif ($request->check_form == 'about') {</span><br>
                                                <span style="margin-left: 5em">$agency->update([</span><br>
                                                <span style="margin-left: 7em">'tentang' => $data['input']['tentang'],</span><br>
                                                <span style="margin-left: 7em">'alasan' => $data['input']['alasan'],</span><br>
                                                <span style="margin-left: 5em">]);</span><br><br>

                                                <span style="margin-left: 4em">} elseif ($request->check_form == 'vacancy') {</span><br>
                                                <span style="margin-left: 5em">$vacancy = Vacancies::where('agency_id', $agency->id)</span><br>
                                                <span style="margin-left: 7em">->where('judul', $data['judul'])->first();</span><br>
                                                <span style="margin-left: 5em">if ($vacancy != null) {</span><br>
                                                <span style="margin-left: 7em">$vacancy->update([</span><br>
                                                <span style="margin-left: 9em">'judul' => $data['input']['judul'],</span><br>
                                                <span style="margin-left: 9em">'city_id' => $data['input']['cities_id'],</span><br>
                                                <span style="margin-left: 9em">'syarat' => $data['input']['syarat'],</span><br>
                                                <span style="margin-left: 9em">'tanggungjawab' => $data['input']['tanggungjawab'],</span><br>
                                                <span style="margin-left: 9em">'pengalaman' => $data['input']['pengalaman'],</span><br>
                                                <span style="margin-left: 9em">'jobtype_id' => $data['input']['jobtype_id'],</span><br>
                                                <span style="margin-left: 9em">'joblevel_id' => $data['input']['joblevel_id'],</span><br>
                                                <span style="margin-left: 9em">'industry_id' => $data['input']['industri_id'],</span><br>
                                                <span style="margin-left: 9em">'salary_id' => $data['input']['salary_id'],</span><br>
                                                <span style="margin-left: 9em">'degree_id' => $data['input']['tingkatpend_id'],</span><br>
                                                <span style="margin-left: 9em">'major_id' => $data['input']['jurusanpend_id'],</span><br>
                                                <span style="margin-left: 9em">'jobfunction_id' => $data['input']['fungsikerja_id'],</span><br>
                                                <span style="margin-left: 7em">]);</span><br>
                                                <span style="margin-left: 5em">}</span><br>

                                                <span style="margin-left: 4em">} elseif ($request->check_form == 'schedule') {</span><br>
                                                <span style="margin-left: 5em">$vacancy = Vacancies::where('agency_id', $agency->id)</span><br>
                                                <span style="margin-left: 7em">->where('judul', $data['judul'])->first();</span><br>
                                                <span style="margin-left: 5em">if ($vacancy != null) {</span><br>
                                                <span style="margin-left: 7em">$vacancy->update([</span><br>
                                                <span style="margin-left: 9em">'recruitmentDate_start' => $data['input']['isPost'] == 1 ?</span><br>
                                                <span style="margin-left: 11em">$data['input']['recruitmentDate_start'] : null,</span><br>
                                                <span style="margin-left: 9em">'recruitmentDate_end' => $data['input']['isPost'] == 1 ?</span><br>
                                                <span style="margin-left: 11em">$data['input']['recruitmentDate_end'] : null,</span><br>
                                                <span style="margin-left: 9em">'interview_date' => $data['input']['isPost'] == 1 ?</span><br>
                                                <span style="margin-left: 11em">$data['input']['interview_date'] : null,</span><br>
                                                <span style="margin-left: 7em">]);</span><br>
                                                <span style="margin-left: 5em">}</span><br>
                                                <span style="margin-left: 4em">}</span><br>
                                                <span style="margin-left: 2em">}</span><br>
                                                }<br><br>

                                                public function deleteVacancies(Request $request)<br>
                                                {<br>
                                                <span style="margin-left: 2em">$data = $request->agencies;</span><br>
                                                <span style="margin-left: 2em">$agency = Agencies::where('email', $data['email'])->first();</span><br>
                                                <span style="margin-left: 2em">if ($agency != null) {</span><br>
                                                <span style="margin-left: 4em">if ($request->check_form == 'agency') {</span><br>
                                                <span style="margin-left: 5em">$agency->delete();</span><br><br>

                                                <span style="margin-left: 4em">} elseif ($request->check_form == 'vacancy') {</span><br>
                                                <span style="margin-left: 5em">$vacancy = Vacancies::where('agency_id', $agency->id)</span><br>
                                                <span style="margin-left: 7em">->where('judul', $data['judul'])->first();</span><br>
                                                <span style="margin-left: 5em">if ($vacancy != null) {</span><br>
                                                <span style="margin-left: 7em">if ($vacancy->getAgency->getVacancy->count() > 0) {</span><br>
                                                <span style="margin-left: 9em">$vacancy->delete();</span><br>
                                                <span style="margin-left: 7em">} else {</span><br>
                                                <span style="margin-left: 9em">$agency->delete();</span><br>
                                                <span style="margin-left: 7em">}</span><br>
                                                <span style="margin-left: 5em">}</span><br>
                                                <span style="margin-left: 4em">}</span><br>
                                                <span style="margin-left: 2em">}</span><br>
                                                }
                                            </code><br><br>
                                            <em>// here is your other functions&hellip;</em>
                                        </blockquote>
                                    </li>
                                </ol>
                            </div>
                            <div id="sync-vacancy-2">
                                <h2 class="StepTitle">Step 5 Sync&ndash;Vacancy <sub>(Request)</sub></h2>
                                <ol start="13" style="font-size: 15px;">
                                    <li>Buka file <code>app/Http/Controllers/Admins/AgencyController.php</code>.</li>
                                    <li>Tambahkan construct code berikut:
                                        <blockquote>
                                            <code>
                                                protected $key, $secret, $client, $uri;<br><br>

                                                public function __construct()<br>
                                                {<br>
                                                <span style="margin-left: 2em">$this->key = env('SISKA_API_KEY');</span><br>
                                                <span style="margin-left: 2em">$this->secret = env('SISKA_API_SECRET');</span><br>
                                                <span style="margin-left: 2em">$this->uri = env('SISKA_URI');</span>
                                                <br><br>
                                                <span style="margin-left: 2em">$this->client = new Client([</span><br>
                                                <span style="margin-left: 4em">'base_uri' => $this->uri,</span><br>
                                                <span style="margin-left: 4em">'defaults' => [</span><br>
                                                <span style="margin-left: 6em">'exceptions' => false</span><br>
                                                <span style="margin-left: 4em">]</span><br>
                                                <span style="margin-left: 2em">]);</span><br>
                                                }
                                            </code><br><br>
                                            <em>// here is your other functions&hellip;</em>
                                        </blockquote>
                                    </li>
                                    <li>Jangan lupa untuk <em>import library</em> guzzle Anda dan tambahkan code berikut
                                        :
                                        <blockquote>
                                            <code>
                                                use GuzzleHttp\Client;<br>
                                                use App\Http\Controllers\Api\APIController as Credential;
                                            </code>
                                        </blockquote>
                                    </li>
                                    <li>Tambahkan code berikut:
                                        <blockquote>
                                            <code>
                                                public function updateAgencies(Request $request)<br>
                                                {<br>
                                                <span style="margin-left: 2em"><em>// other codes&hellip;</em></span><br><br>
                                                <span style="margin-left: 2em">$response = app(Credential::class)->getCredentials();</span><br>
                                                <span style="margin-left: 2em">if ($response['isSync'] == true) {</span><br>
                                                <span style="margin-left: 4em">$this->client->put($this->uri . '/api/partners/vacancies/agency/update', [</span><br>
                                                <span style="margin-left: 6em">'form_params' => [</span><br>
                                                <span style="margin-left: 8em">'key' => $this->key,</span><br>
                                                <span style="margin-left: 8em">'secret' => $this->secret,</span><br>
                                                <span style="margin-left: 8em">'agency' => $agency->toArray(),</span><br>
                                                <span style="margin-left: 8em">'data' => $request->toArray(),</span><br>
                                                <span style="margin-left: 6em">]</span><br>
                                                <span style="margin-left: 4em">]);</span><br>
                                                <span style="margin-left: 2em">}</span><br><br>
                                                <span style="margin-left: 2em"><em>// here is your agency update query code&hellip;</em></span><br>
                                                }<br><br>

                                                public function deleteAgencies($id)<br>
                                                {<br>
                                                <span style="margin-left: 2em"><em>// here is your agency delete query code&hellip;</em></span><br><br>
                                                <span style="margin-left: 2em">$response = app(Credential::class)->getCredentials();</span><br>
                                                <span style="margin-left: 2em">if ($response['isSync'] == true) {</span><br>
                                                <span style="margin-left: 4em">$this->client->delete($this->uri.'/api/partners/vacancies/agency/delete',[</span><br>
                                                <span style="margin-left: 6em">'form_params' => [</span><br>
                                                <span style="margin-left: 8em">'key' => $this->key,</span><br>
                                                <span style="margin-left: 8em">'secret' => $this->secret,</span><br>
                                                <span style="margin-left: 8em">'agency' => $agency->toArray(),</span><br>
                                                <span style="margin-left: 6em">]</span><br>
                                                <span style="margin-left: 4em">]);</span><br>
                                                <span style="margin-left: 2em">}</span><br><br>
                                                <span style="margin-left: 2em"><em>// other codes&hellip;</em></span><br>
                                                }<br><br>

                                                public function createVacancies(Request $request)<br>
                                                {<br>
                                                <span style="margin-left: 2em"><em>// here is your vacancy insert/create query code&hellip;</em></span><br><br>
                                                <span style="margin-left: 2em">$response = app(Credential::class)->getCredentials();</span><br>
                                                <span style="margin-left: 2em">if ($response['isSync'] == true) {</span><br>
                                                <span style="margin-left: 4em">$this->client->post($this->uri . '/api/partners/vacancies/create', [</span><br>
                                                <span style="margin-left: 6em">'form_params' => [</span><br>
                                                <span style="margin-left: 8em">'key' => $this->key,</span><br>
                                                <span style="margin-left: 8em">'secret' => $this->secret,</span><br>
                                                <span style="margin-left: 8em">'vacancy' => $vacancy->toArray(),</span><br>
                                                <span style="margin-left: 8em">'agency' => $vacancy->getAgency->toArray(),</span><br>
                                                <span style="margin-left: 6em">]</span><br>
                                                <span style="margin-left: 4em">]);</span><br>
                                                <span style="margin-left: 2em">}</span><br><br>
                                                <span style="margin-left: 2em"><em>// other codes&hellip;</em></span><br>
                                                }<br><br>

                                                public function updateVacancies(Request $request)<br>
                                                {<br>
                                                <span style="margin-left: 2em"><em>// other codes&hellip;</em></span><br><br>
                                                <span style="margin-left: 2em">$response = app(Credential::class)->getCredentials();</span><br>
                                                <span style="margin-left: 2em">if ($response['isSync'] == true) {</span><br>
                                                <span style="margin-left: 4em">$this->client->put($this->uri . '/api/partners/vacancies/update', [</span><br>
                                                <span style="margin-left: 6em">'form_params' => [</span><br>
                                                <span style="margin-left: 8em">'key' => $this->key,</span><br>
                                                <span style="margin-left: 8em">'secret' => $this->secret,</span><br>
                                                <span style="margin-left: 8em">'agency' => $vacancy->getAgency->toArray(),</span><br>
                                                <span style="margin-left: 8em">'vacancy' => $vacancy->toArray(),</span><br>
                                                <span style="margin-left: 8em">'data' => $request->toArray(),</span><br>
                                                <span style="margin-left: 6em">]</span><br>
                                                <span style="margin-left: 4em">]);</span><br>
                                                <span style="margin-left: 2em">}</span><br><br>
                                                <span style="margin-left: 2em"><em>// here is your vacancy update query code&hellip;</em></span><br>
                                                }<br><br>

                                                public function deleteVacancies($id)<br>
                                                {<br>
                                                <span style="margin-left: 2em"><em>// here is your vacancy delete query code&hellip;</em></span><br><br>
                                                <span style="margin-left: 2em">$response = app(Credential::class)->getCredentials();</span><br>
                                                <span style="margin-left: 2em">if ($response['isSync'] == true) {</span><br>
                                                <span style="margin-left: 4em">if($vacancy->getAgency->getVacancy->count() > 0){</span><br>
                                                <span style="margin-left: 5em">$this->client->delete($this->uri . '/api/partners/vacancies/delete', [</span><br>
                                                <span style="margin-left: 7em">'form_params' => [</span><br>
                                                <span style="margin-left: 9em">'key' => $this->key,</span><br>
                                                <span style="margin-left: 9em">'secret' => $this->secret,</span><br>
                                                <span style="margin-left: 9em">'agency' => $vacancy->getAgency->toArray(),</span><br>
                                                <span style="margin-left: 9em">'vacancy' => $vacancy->toArray(),</span><br>
                                                <span style="margin-left: 7em">]</span><br>
                                                <span style="margin-left: 5em">]);</span><br><br>

                                                <span style="margin-left: 4em">} else{</span><br>
                                                <span style="margin-left: 5em">$this->client->delete($this->uri.'/api/partners/vacancies/agency/delete',[</span><br>
                                                <span style="margin-left: 7em">'form_params' => [</span><br>
                                                <span style="margin-left: 9em">'key' => $this->key,</span><br>
                                                <span style="margin-left: 9em">'secret' => $this->secret,</span><br>
                                                <span style="margin-left: 9em">'agency' => $vacancy->getAgency->toArray(),</span><br>
                                                <span style="margin-left: 7em">]</span><br>
                                                <span style="margin-left: 5em">]);</span><br><br>
                                                <span style="margin-left: 4em">}</span><br>
                                                <span style="margin-left: 2em">}</span><br><br>
                                                <span style="margin-left: 2em"><em>// other codes&hellip;</em></span><br>
                                                }
                                            </code>
                                        </blockquote>
                                    </li>
                                </ol>
                            </div>
                            <div id="finish">
                                <h2 class="StepTitle">Step 6 Finish</h2>
                                <ol start="17" style="text-align: justify;font-size: 15px;">
                                    <li>Untuk mengakhiri <em>synchronize setup</em>, tekan tombol
                                        "<strong>Finish</strong>" berikut. Dengan menekan tombol tersebut maka data
                                        <strong>SISKA</strong>, yaitu agensi beserta lowongannya akan disalin ke dalam
                                        database SiskaLTE instansi Anda.
                                    </li>
                                    <li>Begitu pula dengan seluruh data lowongan beserta agensi Anda juga akan disalin
                                        ke dalam database<strong>SISKA</strong> yang tentunya akan melalui proses
                                        validasi terlebih dahulu, manakah yang akan ditampilkan pada situs utama
                                        <strong>SISKA</strong>.
                                    </li>
                                    <li>Atas perhatian dan kerjasamanya, kami ucapkan terimakasih banyak
                                        <i class="fa fa-grin-beam"></i>.
                                    </li>
                                </ol>
                            </div>
                        </div>
                        <form method="post" id="form-submit-sync">
                            {{csrf_field()}}
                            <input type="hidden" name="key" value="{{env('SISKA_API_KEY')}}">
                            <input type="hidden" name="secret" value="{{env('SISKA_API_SECRET')}}">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push("scripts")
    <script>
        $(function () {
            loadCredentials();

            $("#wizard a.buttonNext, #wizard a.buttonPrevious, #wizard .wizard_steps li a, #wizard_verticle a.buttonNext, #wizard_verticle a.buttonPrevious, #wizard_verticle .wizard_steps li a").on("click", function () {
                $('html, body').animate({
                    scrollTop: $("#panel_title").offset().top
                }, 500);
            });
        });

        function loadCredentials() {
            clearTimeout(this.delay);
            this.delay = setTimeout(function () {
                $.ajax({
                    url: "{{route('get.credentials')}}",
                    type: "GET",
                    beforeSend: function () {
                        $('#image').show();
                        $("#sync-info").hide();
                    },
                    complete: function () {
                        $('#image').hide();
                        $("#sync-info").show();
                    },
                    success: function (data) {
                        if (data.isSync == 0) {
                            $("#sync-info").html(
                                '<div class="bs-example" data-example-id="simple-jumbotron">' +
                                '<div id="sync-info" class="jumbotron">' +
                                '<h1>Halo, ' + data.name + '</h1>' +
                                '<p>Selamat, partnership setup berhasil! Instansi Anda dinyatakan telah resmi bermitra ' +
                                'dengan <strong>SISKA</strong>. Selanjutnya, sinkronisasikan data lowongan Anda dengan ' +
                                'data lowongan SiskaLTE dari seluruh instansi yang telah bermitra dengan ' +
                                '<strong>SISKA</strong>.</p><hr>' +
                                '<a href="javascript:void(0)" class="btn btn-primary btn-lg" ' +
                                'style="text-transform: capitalize" onclick="startSync()">' +
                                '<strong><i class="fa fa-sync-alt"></i>&ensp;sinkronisasi sekarang!</strong></a></div></div>'
                            );

                        } else if (data.isSync == 1) {
                            $("#sync-info").html(
                                '<div class="bs-example" data-example-id="simple-jumbotron">' +
                                '<div id="sync-info" class="jumbotron">' +
                                '<h1>Halo, ' + data.name + '</h1>' +
                                '<p>Selamat! SiskaLTE instansi Anda telah berhasil disinkronisasikan, baik dengan ' +
                                '<strong>SISKA</strong> maupun SiskaLTE lainnya yang juga telah bermitra dan ' +
                                'melakukan sinkronisasi dengan <strong>SISKA</strong>.</p></div></div>'
                            );

                        } else {
                            $("#sync-info").html(
                                '<div class="bs-example" data-example-id="simple-jumbotron">' +
                                '<div id="sync-info" class="jumbotron">' +
                                '<h1><strong>500</strong>. Internal Server Error!</h1>' +
                                '<p>Kami minta maaf atas ketidaknyamanannya, tetapi tampaknya ada kesalahan ' +
                                'server internal SISKA saat memproses permintaan Anda. ' +
                                'Teknisi kami telah diberitahu dan bekerja untuk menyelesaikan masalah ini. ' +
                                'Silakan coba lagi nanti.</p></div></div>'
                            );
                        }

                        $("#wizard .stepContainer").css('height', 'auto');
                        $("#wizard a.buttonFinish").attr('href', 'javascript:void(0)')
                            .attr('onclick', 'submitSync()');
                    },
                    error: function () {
                        $("#sync-info").html(
                            '<div class="bs-example" data-example-id="simple-jumbotron">' +
                            '<div id="sync-info" class="jumbotron">' +
                            '<h1>Halo, {{Auth::guard('admin')->user()->name}}</h1>' +
                            '<p>Untuk dapat melakukan sinkronisasi data, Anda memerlukan sebuah hak akses ' +
                            '(<em>credential</em>) berupa <strong>API Key & API Secret</strong> dari SISKA ' +
                            'dan dengan bermitra Anda akan mendapatkan hak akses tersebut.</p>' +
                            '<small style="color: #c7254e">P.S.: Jika Anda masih melihat pesan ini maka Anda belum ' +
                            'menyelesaikan <em>partnership setup</em> dengan benar atau kredensial Anda sudah kadaluarsa.' +
                            '</small><hr>' +
                            '<a href="javascript:void(0)" class="btn btn-primary btn-lg" onclick="startPartner()"' +
                            'style="text-transform: capitalize;"><strong><i class="fa fa-handshake"></i>&ensp;' +
                            'bermitra sekarang!</strong></a></div></div>'
                        );

                        $("#wizard_verticle .stepContainer").css('height', 'auto');
                        $("#wizard_verticle a.buttonFinish").attr('href', 'javascript:void(0)')
                            .attr('onclick', 'submitPartner()');
                    }
                });
            }.bind(this), 800);

            return false;
        }

        function startPartner() {
            $("#sync-info").fadeOut('fast', function () {
                $("#panel_title").html('SISKA Partnership<small>Setup</small>');
                $("#partner-setup").fadeIn('fast');
            });
        }

        function cancelPartner() {
            $("#partner-setup").fadeOut('fast', function () {
                $("#panel_title").html('Synchronizing Data<small>SISKA Partnership</small>');
                $("#sync-info").fadeIn('fast');
            });
        }

        function submitPartner() {
            swal({
                title: 'Apakah Anda sudah yakin?',
                text: "Jika partnership setup belum diselesaikan dengan baik dan benar, maka Anda tidak dapat " +
                    "melanjutkan ke tahap sinkronisasi data lowongan!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, saya yakin!',
                showLoaderOnConfirm: true,

                preConfirm: function () {
                    return new Promise(function (resolve) {
                        window.location.href = '{{route('show.synchronize')}}';
                    });
                },
                allowOutsideClick: false
            });
        }

        function startSync() {
            $("#sync-info").fadeOut('fast', function () {
                $("#panel_title").html('Synchronizing Data<small>Setup</small>');
                $("#sync-setup").fadeIn('fast');
            });
        }

        function cancelSync() {
            $("#sync-setup").fadeOut('fast', function () {
                $("#panel_title").html('Synchronizing Data<small>SISKA Partnership</small>');
                $("#sync-info").fadeIn('fast');
            });
        }

        function submitSync() {
            swal({
                title: 'Apakah Anda sudah yakin?',
                text: "Jika synchronize setup belum diselesaikan dengan baik dan benar maka setiap kali ada " +
                    "penambahan atau perubahan terkait data agensi beserta lowongannya maupun data job seeker " +
                    "maka data tersebut hanya akan tersimpan di dalam database Anda sendiri!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, saya yakin!',
                showLoaderOnConfirm: true,

                preConfirm: function () {
                    return new Promise(function (resolve) {
                        $.ajax({
                            type: "POST",
                            url: "{{route('submit.synchronize')}}",
                            data: new FormData($("#form-submit-sync")[0]),
                            contentType: false,
                            processData: false,
                            success: function (data) {
                                swal('Synchronize Setup', data, 'success');
                                loadCredentials();
                                cancelSync();
                            },
                            error: function () {
                                swal('Synchronize Setup', 'Something went wrong! Please refresh the page.', 'error');
                            }
                        });
                    });
                },
                allowOutsideClick: false
            });
        }
    </script>
@endpush
