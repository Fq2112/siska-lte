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
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
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
                                <ol start="7" style="text-align: justify;font-size: 15px;">
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
                                                }<br><br>
                                            </code>
                                            <em>// here is your other functions&hellip;</em>
                                        </blockquote>
                                    </li>
                                </ol>
                            </div>
                            <div id="finish">
                                <h2 class="StepTitle">Step 4 Finish</h2>
                                <ol start="9" style="text-align: justify;font-size: 15px;">
                                    <li>Untuk mengakhiri setup ini dan lanjut ke tahap sinkronisasi data lowongan,
                                        refresh halaman ini dengan menekan tombol refresh pada browser Anda atau
                                        dengan menekan tombol "<strong>Finish</strong>" dibawah.
                                    </li>
                                    <li>Atas perhatian dan kerjasamanya terimakasih <i class="fa fa-grin-beam"></i>.
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
                                <ol start="7" style="text-align: justify;font-size: 15px;">
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
                                                }<br><br>
                                            </code>
                                            <em>// here is your other functions&hellip;</em>
                                        </blockquote>
                                    </li>
                                </ol>
                            </div>
                            <div id="finish">
                                <h2 class="StepTitle">Step 4 Finish</h2>
                                <ol start="9" style="text-align: justify;font-size: 15px;">
                                    <li>Untuk mengakhiri setup ini dan lanjut ke tahap sinkronisasi data lowongan,
                                        refresh halaman ini dengan menekan tombol refresh pada browser Anda atau
                                        dengan menekan tombol "<strong>Finish</strong>" dibawah.
                                    </li>
                                    <li>Atas perhatian dan kerjasamanya terimakasih <i class="fa fa-grin-beam"></i>.
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push("scripts")
    <script>
        $(function () {
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
                    $("#sync-info").html(
                        '<div class="bs-example" data-example-id="simple-jumbotron">' +
                        '<div id="sync-info" class="jumbotron">' +
                        '<h1>Halo, ' + data.name + '</h1>' +
                        '<p>Selamat, partnership setup berhasil! Instansi Anda dinyatakan telah resmi bermitra ' +
                        'dengan <strong>SISKA</strong>. Selanjutnya, sinkronisasikan data lowongan Anda dengan ' +
                        'data lowongan SiskaLTE dari seluruh instansi yang telah bermitra dengan ' +
                        '<strong>SISKA</strong>.</p>' +
                        '<small style="color: #c7254e">P.S.: Jika Anda telah menyelesaikan ' +
                        '<em>synchronize setup</em> dengan baik dan benar maka setiap ada penambahan lowongan, ' +
                        'Anda tidak perlu melakukan sinkronisasi ulang.</small><hr>' +
                        '<a href="javascript:void(0)" class="btn btn-primary btn-lg" ' +
                        'style="text-transform: capitalize" onclick="startSync()">' +
                        '<strong><i class="fa fa-sync-alt"></i>&ensp;sinkronisasi sekarang!</strong></a></div></div>'
                    );

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
        });

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
                text: "Jika synchronize setup belum diselesaikan dengan baik dan benar maka setiap ada penambahan " +
                    "lowongan, Anda perlu melakukan sinkronisasi ulang!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, saya yakin!',
                showLoaderOnConfirm: true,

                preConfirm: function () {
                    return new Promise(function (resolve) {
                        window.location.href = '{{route('sync.vacancy')}}'
                    });
                },
                allowOutsideClick: false
            });
        }
    </script>
@endpush
