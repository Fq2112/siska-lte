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
                                    <li>Buka situs utama <a href="http://localhost:8000#partner" target="_blank">
                                            <strong>SISKA</strong></a>, lalu klik tombol "<strong>Bermitra Sekarang!
                                        </strong>" untuk melakukan <em>partnership request</em>.
                                    </li>
                                    <li>Isi form <strong>SISKA Partnership</strong> dengan data yang valid mulai dari
                                        nama instansi, email, serta nomor telp/hp Anda yang masih aktif.
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
                                                SISKA_API_SECRET=<em>YOUR_API_SECRET</em></code>
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
                                                <span style="margin-left: 2em">$this->uri = 'http://localhost:8000';</span>
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
                                                <span style="margin-left: 2em">$response = $this->client->get($this->uri . '/api/partners?key=' . $this->key .</span><br>
                                                <span style="margin-left: 5em">'&secret=' . $this->secret);</span>
                                                <br><br>
                                                <span style="margin-left: 2em">return json_decode($response->getBody(), true);</span>
                                                <br>
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
                                    <em>synchronize setup</em> untuk data job seeker. Dengan begitu, ketika seeker
                                    membuat akun melalui situs utama SISKA maka datanya juga akan disimpan ke dalam
                                    database Anda maupun database SiskaLTE lainnya yang telah bermitra serta
                                    melakukan sinkronisasi dengan SISKA dan apabila seeker tersebut membuat akun
                                    melalui SiskaLTE instansi Anda maka datanya hanya akan tersimpan di dalam
                                    database Anda sendiri.
                                </p>
                                <ol style="font-size: 15px;">
                                    <li>Buka file <code>routes/api.php</code>.</li>
                                    <li>Tambahkan code berikut :
                                        <blockquote>
                                            <code>
                                                $router->group(['prefix'=>'SISKA', 'middleware'=>'partner'],
                                                function($router){<br>
                                                <span style="margin-left: 2em">$router->group(['prefix' => 'seekers'], function ($router) {</span><br>
                                                <span style="margin-left: 4em">$router->post('create', 'APIController@createSeekers');</span><br>
                                                <span style="margin-left: 4em">$router->post('{provider}', 'APIController@seekersSocialite');</span><br>
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
                                <h2 class="StepTitle">Step 2 Sync&ndash;Seeker <sub>(Part 1)</sub></h2>
                                <ol start="3" style="font-size: 15px;">
                                    <li>Buka file <code>app/Http/Controllers/Api/APIController.php</code>.</li>
                                    <li>Tambahkan code berikut :
                                        <blockquote>
                                            <em>// here is your credentials functions&hellip;</em><br><br>
                                            <code>
                                                public function createSeekers(Request $request)<br>
                                                {<br>
                                                <span style="margin-left: 2em">$data = $request->seeker;</span><br>
                                                <span style="margin-left: 2em">$checkSeeker = User::where('email', $data['email'])->first();</span><br>
                                                <span style="margin-left: 2em">if (!$checkSeeker) {</span><br>
                                                <span style="margin-left: 4em">User::firstOrCreate([</span><br>
                                                <span style="margin-left: 6em">'ava' => 'seeker.png',</span><br>
                                                <span style="margin-left: 6em">'name' => $data['name'],</span><br>
                                                <span style="margin-left: 6em">'email' => $data['email'],</span><br>
                                                <span style="margin-left: 6em">'password' => $data['password'],</span><br>
                                                <span style="margin-left: 4em">]);</span><br>
                                                <span style="margin-left: 2em">}</span><br>
                                                }<br><br>

                                                public function seekersSocialite($provider, Request $request)<br>
                                                {<br>
                                                <span style="margin-left: 2em">$data = $request->seeker;</span><br>
                                                <span style="margin-left: 2em">$checkSeeker = User::where('email', $data['email'])->first();</span><br>
                                                <span style="margin-left: 2em">if (!$checkSeeker) {</span><br>
                                                <span style="margin-left: 4em">$user = User::firstOrCreate([</span><br>
                                                <span style="margin-left: 6em">'ava' => 'seeker.png',</span><br>
                                                <span style="margin-left: 6em">'name' => $data['name'],</span><br>
                                                <span style="margin-left: 6em">'email' => $data['email'],</span><br>
                                                <span style="margin-left: 6em">'password' => $data['password'],</span><br>
                                                <span style="margin-left: 4em">]);</span><br><br>

                                                <span style="margin-left: 4em">$user->socialProviders()->create([</span><br>
                                                <span style="margin-left: 6em">'provider_id' => $data['provider_id'],</span><br>
                                                <span style="margin-left: 6em">'provider' => $provider</span><br>
                                                <span style="margin-left: 4em">]);</span><br>
                                                <span style="margin-left: 2em">}</span><br>
                                                }<br><br>

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
                                <h2 class="StepTitle">Step 3 Sync&ndash;Seeker <sub>(Part 2)</sub></h2>
                                <ol start="6" style="font-size: 15px;">
                                    <li>Buka file <code>app/Http/Controllers/Seekers/AccountController.php</code>.</li>
                                    <li>Tambahkan fungsi berikut :
                                        <blockquote>
                                            <em>// here is your other functions&hellip;</em><br><br>
                                            <code>
                                                public function updateAccount(Request $request)<br>
                                                {<br>
                                                <span style="margin-left: 2em"><em>// here is your seeker update password query code&hellip;</em></span><br><br>
                                                <span style="margin-left: 2em">$data = array('email' => $user->email, 'password' => $user->password);</span><br>
                                                <span style="margin-left: 2em">$this->updatePartners($data, 'password');</span><br><br>
                                                <span style="margin-left: 2em"><em>// other codes&hellip;</em></span><br>
                                                }<br><br>

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

                                                private function updatePartners($data, $check)<br>
                                                {<br>
                                                <span style="margin-left: 2em">$client = new Client([</span><br>
                                                <span style="margin-left: 4em">'base_uri' => 'http://localhost:8000',</span><br>
                                                <span style="margin-left: 4em">'defaults' => [</span><br>
                                                <span style="margin-left: 6em">'exceptions' => false</span><br>
                                                <span style="margin-left: 4em">]</span><br>
                                                <span style="margin-left: 2em">]);</span><br><br>

                                                <span style="margin-left: 2em">$client->put('http://localhost:8000/api/partners/seekers/update', [</span><br>
                                                <span style="margin-left: 4em">''form_params' => [</span><br>
                                                <span style="margin-left: 6em">'key' => env('SISKA_API_KEY'),</span><br>
                                                <span style="margin-left: 6em">'secret' => env('SISKA_API_SECRET'),</span><br>
                                                <span style="margin-left: 6em">'check_form' => $check,</span><br>
                                                <span style="margin-left: 6em">'seeker' => $data,</span><br>
                                                <span style="margin-left: 4em">']</span><br>
                                                <span style="margin-left: 2em">]);</span><br>
                                                }
                                            </code><br><br>
                                            <em>// here is your other functions&hellip;</em>
                                        </blockquote>
                                    </li>
                                    <li>Jangan lupa untuk <em>import library</em> guzzle Anda :
                                        <blockquote><code>use GuzzleHttp\Client;</code></blockquote>
                                    </li>
                                </ol>
                            </div>
                            <div id="sync-vacancy-1">
                                <h2 class="StepTitle">Step 3 Sync&ndash;Vacancy <sub>(Part 1)</sub></h2>
                                <ol start="9" style="font-size: 15px;">
                                    <li>Masih di dalam file <code>app/Http/Controllers/Api/APIController.php</code>,
                                        tambahkan code berikut :
                                        <blockquote>
                                            <em>// here is your sync seekers functions&hellip;</em><br><br>
                                            <code>
                                                public function createSeekers(Request $request)<br>
                                                {<br>
                                                <span style="margin-left: 2em">$data = $request->seeker;</span><br>
                                                <span style="margin-left: 2em">$checkSeeker = User::where('email', $data['email'])->first();</span><br>
                                                <span style="margin-left: 2em">if (!$checkSeeker) {</span><br>
                                                <span style="margin-left: 4em">User::firstOrCreate([</span><br>
                                                <span style="margin-left: 6em">'ava' => 'seeker.png',</span><br>
                                                <span style="margin-left: 6em">'name' => $data['name'],</span><br>
                                                <span style="margin-left: 6em">'email' => $data['email'],</span><br>
                                                <span style="margin-left: 6em">'password' => $data['password'],</span><br>
                                                <span style="margin-left: 4em">]);</span><br>
                                                <span style="margin-left: 2em">}</span><br>
                                                }<br><br>

                                                public function seekersSocialite($provider, Request $request)<br>
                                                {<br>
                                                <span style="margin-left: 2em">$data = $request->seeker;</span><br>
                                                <span style="margin-left: 2em">$checkSeeker = User::where('email', $data['email'])->first();</span><br>
                                                <span style="margin-left: 2em">if (!$checkSeeker) {</span><br>
                                                <span style="margin-left: 4em">$user = User::firstOrCreate([</span><br>
                                                <span style="margin-left: 6em">'ava' => 'seeker.png',</span><br>
                                                <span style="margin-left: 6em">'name' => $data['name'],</span><br>
                                                <span style="margin-left: 6em">'email' => $data['email'],</span><br>
                                                <span style="margin-left: 6em">'password' => $data['password'],</span><br>
                                                <span style="margin-left: 4em">]);</span><br><br>

                                                <span style="margin-left: 4em">$user->socialProviders()->create([</span><br>
                                                <span style="margin-left: 6em">'provider_id' => $data['provider_id'],</span><br>
                                                <span style="margin-left: 6em">'provider' => $provider</span><br>
                                                <span style="margin-left: 4em">]);</span><br>
                                                <span style="margin-left: 2em">}</span><br>
                                                }<br><br>

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
                                </ol>
                            </div>
                            <div id="sync-vacancy-2">
                                <h2 class="StepTitle">Step 4 Sync&ndash;Vacancy <sub>(Part 2)</sub></h2>
                                <ol start="10" style="font-size: 15px;">
                                    <li>Buka file <code>app/Http/Controllers/Admins/AgencyController.php</code>.</li>
                                    <li>Tambahkan construct code berikut :
                                        <blockquote>
                                            <code>
                                                protected $key, $secret, $client, $uri;<br><br>

                                                public function __construct()<br>
                                                {<br>
                                                <span style="margin-left: 2em">$this->key = env('SISKA_API_KEY');</span><br>
                                                <span style="margin-left: 2em">$this->secret = env('SISKA_API_SECRET');</span><br>
                                                <span style="margin-left: 2em">$this->uri = 'http://localhost:8000';</span>
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
                                    <li>Tambahkan code berikut :
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
                                                <span style="margin-left: 8em">'vacancy' => $vacancy->toArray(),</span><br>
                                                <span style="margin-left: 8em">'agency' => $vacancy->getAgency->toArray(),</span><br>
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
                                <h2 class="StepTitle">Step 5 Finish</h2>
                                <ol start="14" style="text-align: justify;font-size: 15px;">
                                    <li>Untuk mengakhiri <em>synchronize setup</em>, tekan tombol
                                        "<strong>Finish</strong>" berikut. Dengan menekan tombol tersebut maka data
                                        agensi dan lowongannya serta data job seeker dari <strong>SISKA</strong>
                                        akan disimpan kedalam database SiskaLTE instansi Anda.
                                    </li>
                                    <li>Begitu pula dengan seluruh data lowongan beserta agensi Anda akan dimigrasikan
                                        ke dalam database<strong>SISKA</strong> yang tentunya akan melalui proses
                                        filter terlebih dahulu, manakah yang akan ditampilkan pada situs utama
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

                        } else {
                            $("#sync-info").html(
                                '<div class="bs-example" data-example-id="simple-jumbotron">' +
                                '<div id="sync-info" class="jumbotron">' +
                                '<h1>Halo, ' + data.name + '</h1>' +
                                '<p>Selamat! SiskaLTE instansi Anda telah berhasil disinkronisasikan, baik dengan ' +
                                '<strong>SISKA</strong> maupun SiskaLTE lainnya yang juga telah bermitra dan ' +
                                'melakukan sinkronisasi dengan <strong>SISKA</strong>.</p></div></div>'
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
