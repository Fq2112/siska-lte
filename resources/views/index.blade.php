<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{env("APP_NAME")}} | SISKA &mdash; Sistem Informasi Karier</title>
    <!-- Bootstrap -->
    <link href="{{asset('css/bootstrap.css')}}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{asset('fonts/fontawesome-free/css/all.css')}}" rel="stylesheet">
    <!-- NProgress -->
    <link href="{{asset('css/nprogress.css')}}" rel="stylesheet">
    <!-- Animate.css -->
    <link href="{{asset('css/animate.min.css')}}" rel="stylesheet">
    <!-- Sweet Alert v2 -->
    <link rel="stylesheet" href="{{ asset('sweetalert2/sweetalert2.min.css') }}">
    <script src="{{ asset('sweetalert2/sweetalert2.min.js') }}"></script>
    <!-- Custom Theme Style -->
    <link href="{{asset('css/custom.css')}}" rel="stylesheet">
    <link href="{{asset('css/signIn-Up.css')}}" rel="stylesheet">
    <script src='https://www.google.com/recaptcha/api.js?onload=recaptchaCallback&render=explicit' async defer></script>
</head>

<body>
<div id="particles-js"></div>
<div class="wrapper">
    <div class="sign-panels">
        <!-- Sign in form -->
        <div class="login">
            <div class="title">
                <span>Sign In</span>
                <p>Welcome back, please login to your account.<br>You can sign in with :</p>
            </div>

            <div class="social">
                <a class="circle github" href="{{route('redirect', ['provider' => 'github'])}}"
                   data-toggle="tooltip" data-title="Github" data-placement="left">
                    <i class="fab fa-github fa-fw"></i>
                </a>
                {{--<a id="facebook_login" class="circle facebook"
                                   href="{{route('redirect', ['provider' => 'facebook'])}}"
                                   data-toggle="tooltip" data-title="Facebook" data-placement="top">
                                    <i class="fab fa-facebook-f fa-fw"></i>
                                </a>--}}
                <a id="linkedin_login" class="circle linkedin"
                   href="{{route('redirect', ['provider' => 'linkedin'])}}"
                   data-toggle="tooltip" data-title="Linkedin" data-placement="top">
                    <i class="fab fa-linkedin-in fa-fw"></i>
                </a>
                <a class="circle twitter" href="{{route('redirect', ['provider' => 'twitter'])}}"
                   data-toggle="tooltip" data-title="Twitter" data-placement="bottom">
                    <i class="fab fa-twitter fa-fw"></i>
                </a>
                <a id="google_login" class="circle google"
                   href="{{route('redirect', ['provider' => 'google'])}}"
                   data-toggle="tooltip" data-title="Google+" data-placement="right">
                    <i class="fab fa-google-plus-g fa-fw"></i>
                </a>
            </div>

            <div class="or"><span>OR</span></div>

            @if(session('register') || session('recovered'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-check"></i> Alert!</h4>
                    {{session('register') ? session('register') : session('recovered')}}
                </div>
            @elseif(session('error') || session('inactive'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-times"></i> Alert!</h4>
                    {{session('error') ? session('error') : session('inactive')}}
                </div>
            @endif
            <form class="form-horizontal" method="post" accept-charset="UTF-8" action="{{route('login')}}"
                  id="form-login">
                {{ csrf_field() }}
                <div class="row form-group has-feedback">
                    <input type="email" placeholder="Email" name="email" value="{{ old('email') }}" required>
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>
                <div class="row form-group has-feedback">
                    <input id="log_password" type="password" placeholder="Password" name="password" minlength="6"
                           required>
                    <span class="glyphicon glyphicon-eye-open form-control-feedback"></span>
                </div>
                <div class="row form-group">
                    <div class="col-lg-4">
                        <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember">Keep me sign in</label>
                    </div>
                    <div class="col-lg-8" id="recaptcha-login"></div>
                </div>
                <div class="row">
                    <button id="btn_login" type="submit" class="btn btn-signin btn-block" disabled>SIGN IN</button>
                </div>
                @if(session('error'))
                    <strong>{{ $errors->first('password') }}</strong>
                @endif
                <a href="javascript:void(0)" class="btn-reset btn-fade">Forgot password?
                    <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
                <a href="javascript:void(0)" class="btn-member btn-fade">Looking to create an account?
                    <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
            </form>
        </div>

        <!-- Sign up form -->
        <div class="signup" style="display: none;">
            <div class="title">
                <span>Sign Up</span>
                <p>Create a new account. You can sign up with :</p>
            </div>

            <div class="social">
                <a class="circle github" href="{{route('redirect', ['provider' => 'github'])}}"
                   data-toggle="tooltip" data-title="Github" data-placement="left">
                    <i class="fab fa-github fa-fw"></i>
                </a>
                {{--<a id="facebook_login" class="circle facebook"
                                   href="{{route('redirect', ['provider' => 'facebook'])}}"
                                   data-toggle="tooltip" data-title="Facebook" data-placement="top">
                                    <i class="fab fa-facebook-f fa-fw"></i>
                                </a>--}}
                <a id="linkedin_login" class="circle linkedin"
                   href="{{route('redirect', ['provider' => 'linkedin'])}}"
                   data-toggle="tooltip" data-title="Linkedin" data-placement="top">
                    <i class="fab fa-linkedin-in fa-fw"></i>
                </a>
                <a class="circle twitter" href="{{route('redirect', ['provider' => 'twitter'])}}"
                   data-toggle="tooltip" data-title="Twitter" data-placement="bottom">
                    <i class="fab fa-twitter fa-fw"></i>
                </a>
                <a id="google_login" class="circle google"
                   href="{{route('redirect', ['provider' => 'google'])}}"
                   data-toggle="tooltip" data-title="Google+" data-placement="right">
                    <i class="fab fa-google-plus-g fa-fw"></i>
                </a>
            </div>

            <div class="or"><span>OR</span></div>

            @if ($errors->has('email'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-times"></i> Alert!</h4>{{ $errors->first('email') }}
                </div>
            @elseif($errors->has('password') || $errors->has('name'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;
                    </button>
                    <h4><i class="icon fa fa-times"></i> Alert!</h4>
                    {{ $errors->has('password') ? $errors->first('password') : $errors->first('name') }}
                </div>
            @endif
            <div id="reg_errorAlert"></div>
            <form class="form-horizontal" method="post" accept-charset="UTF-8" action="{{ route('register') }}"
                  id="form-register">
                {{ csrf_field() }}
                <div class="row form-group has-feedback">
                    <input id="reg_name" type="text" placeholder="Full name" name="name" required>
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>
                <div class="row form-group has-feedback">
                    <input id="reg_email" type="email" placeholder="Email" name="email" required>
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>
                <div class="row form-group has-feedback">
                    <input id="reg_password" type="password" placeholder="Password" name="password"
                           minlength="6" required>
                    <span class="glyphicon glyphicon-eye-open form-control-feedback"></span>
                </div>
                <div class="row form-group has-feedback">
                    <input id="reg_password_confirm" type="password" placeholder="Retype password"
                           name="password_confirmation" minlength="6" required>
                    <span class="glyphicon glyphicon-eye-open form-control-feedback"></span>
                </div>
                <div class="row">
                    <div class="col-lg-6" id="recaptcha-register"></div>
                    <div class="col-lg-6">
                        <button id="btn_register" type="submit" class="btn btn-signup" disabled>CREATE ACCOUNT</button>
                    </div>
                </div>
                <a href="javascript:void(0)" class="btn-login btn-fade">Already have an account? Sign In
                    <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
            </form>
        </div>

        <!-- Reset & Recover password form -->
        <div class="recover-password" style="display: none;">
            <div class="title">
                <span>{{session('reset') || session('recover_failed') ? 'Recovery' : 'Reset'}} Password</span>
                <p>
                    {{session('reset') || session('recover_failed') ? 'Please, enter your new password ' :
                    'To recover your password, please enter an email that associated with your account '}}
                    or you can sign in with :
                </p>
            </div>

            <div class="social">
                <a class="circle github" href="{{route('redirect', ['provider' => 'github'])}}"
                   data-toggle="tooltip" data-title="Github" data-placement="left">
                    <i class="fab fa-github fa-fw"></i>
                </a>
                {{--<a id="facebook_login" class="circle facebook"
                                   href="{{route('redirect', ['provider' => 'facebook'])}}"
                                   data-toggle="tooltip" data-title="Facebook" data-placement="top">
                                    <i class="fab fa-facebook-f fa-fw"></i>
                                </a>--}}
                <a id="linkedin_login" class="circle linkedin"
                   href="{{route('redirect', ['provider' => 'linkedin'])}}"
                   data-toggle="tooltip" data-title="Linkedin" data-placement="top">
                    <i class="fab fa-linkedin-in fa-fw"></i>
                </a>
                <a class="circle twitter" href="{{route('redirect', ['provider' => 'twitter'])}}"
                   data-toggle="tooltip" data-title="Twitter" data-placement="bottom">
                    <i class="fab fa-twitter fa-fw"></i>
                </a>
                <a id="google_login" class="circle google"
                   href="{{route('redirect', ['provider' => 'google'])}}"
                   data-toggle="tooltip" data-title="Google+" data-placement="right">
                    <i class="fab fa-google-plus-g fa-fw"></i>
                </a>
            </div>

            <div class="or"><span>OR</span></div>

            @if(session('resetLink') || session('resetLink_failed'))
                <div class="alert alert-{{session('resetLink') ? 'success' : 'danger'}} alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-{{session('resetLink') ? 'check' : 'times'}}"></i> Alert!</h4>
                    {{session('resetLink') ? session('resetLink') : session('resetLink_failed')}}
                </div>
            @elseif(session('recover_failed'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-times"></i> Alert!</h4>{{ session('recover_failed') }}
                </div>
            @endif
            <form id="form-recovery" class="form-horizontal" method="post" accept-charset="UTF-8"
                  action="{{session('reset') || session('recover_failed') ? route('password.request',
                  ['token' => session('reset') ? session('reset')['token'] : old('token')]) : route('password.email')}}">
                {{ csrf_field() }}
                <div class="row form-group has-feedback">
                    <input type="email" placeholder="Email" id="resetPassword" name="email" value="{{ old('email') }}"
                           required>
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    <span class="error"></span>
                </div>
                @if(session('reset') || session('recover_failed'))
                    <div class="row form-group has-feedback error_forgPass">
                        <input id="forg_password" type="password" placeholder="New Password" name="password"
                               minlength="6" required>
                        <span class="glyphicon glyphicon-eye-open form-control-feedback"></span>
                    </div>
                    <div class="row form-group has-feedback error_forgPass">
                        <input id="forg_password_confirm" type="password" placeholder="Retype password" required
                               name="password_confirmation" minlength="6" onkeyup="return checkForgotPassword()">
                        <span class="glyphicon glyphicon-eye-open form-control-feedback"></span>
                        <span class="help-block"><strong class="aj_forgPass"
                                                         style="text-transform: none"></strong></span>
                    </div>
                @endif
                <div class="row">
                    <button type="submit" class="btn btn-signup btn-password">
                        {{session('reset')||session('recover_failed') ? 'Reset Password' : 'Send Password Reset Link'}}
                    </button>
                </div>
                @unless(session('reset') || session('recover_failed'))
                    <a href="javascript:void(0)" class="btn-member btn-fade">
                        <i class="fa fa-long-arrow-left" aria-hidden="true"></i> Looking to create an account?</a>
                @endunless
            </form>
        </div>
    </div>
</div>
</body>
<!-- jQuery -->
<script src="{{asset('js/jquery.min.js')}}"></script>
<script src="{{asset('js/hideShowPassword.min.js')}}"></script>
<!-- Bootstrap -->
<script src="{{asset('js/bootstrap.min.js')}}"></script>
<!-- Particle-js -->
<script src="{{asset('js/particles.min.js')}}"></script>
<script>
    $('[data-toggle="tooltip"]').tooltip();

    $('.btn-reset').on("click", function () {
        $('.login').hide();
        $('.recover-password').fadeIn(300);
    });

    $('.btn-member').on("click", function () {
        $('.login, .recover-password').hide();
        $('.signup').fadeIn(300);
    });


    $('.btn-login').on("click", function () {
        $('.signup').hide();
        $('.login').fadeIn(300);
    });

    @if(session('resetLink') || session('resetLink_failed') || session('reset') || session('recover_failed'))
    $(".btn-reset").click();
            @endif

    var recaptcha_login, recaptcha_register, recaptchaCallback = function () {
            recaptcha_login = grecaptcha.render(document.getElementById('recaptcha-login'), {
                'sitekey': '{{env('reCAPTCHA_v2_SITEKEY')}}',
                'callback': 'enable_btnLogin',
                'expired-callback': 'disabled_btnLogin'
            });
            recaptcha_register = grecaptcha.render(document.getElementById('recaptcha-register'), {
                'sitekey': '{{env('reCAPTCHA_v2_SITEKEY')}}',
                'callback': 'enable_btnRegister',
                'expired-callback': 'disabled_btnRegister'
            });
        };

    function enable_btnLogin() {
        $("#btn_login").removeAttr('disabled');
    }

    function disabled_btnLogin() {
        $("#btn_login").attr('disabled', 'disabled');
    }

    function enable_btnRegister() {
        $("#btn_register").removeAttr('disabled');
    }

    function disabled_btnRegister() {
        $("#btn_register").attr('disabled', 'disabled');
    }

    $("#form-login").on("submit", function (e) {
        if (grecaptcha.getResponse(recaptcha_login).length === 0) {
            e.preventDefault();
            swal('ATTENTION!', 'Please confirm us that you\'re not a robot by clicking in ' +
                'the reCAPTCHA dialog-box.', 'warning');
        }
    });

    $("#form-register").on("submit", function (e) {
        if (grecaptcha.getResponse(recaptcha_register).length === 0) {
            e.preventDefault();
            swal('ATTENTION!', 'Please confirm us that you\'re not a robot by clicking in ' +
                'the reCAPTCHA dialog-box.', 'warning');
        }

        if ($.trim($("#reg_email,#reg_name,#reg_password,#reg_password_confirm").val()) === "") {
            return false;

        } else {
            if ($("#reg_password_confirm").val() != $("#reg_password").val()) {
                return false;

            } else {
                $("#reg_errorAlert").html('');
                return true;
            }
        }
    });

    $("#reg_password_confirm").on("keyup", function () {
        if ($(this).val() != $("#reg_password").val()) {
            $("#reg_errorAlert").html(
                '<div class="alert alert-danger alert-dismissible">' +
                '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                '<h4><i class="icon fa fa-times"></i> Alert!</h4>Your password confirmation doesn\'t match!</div>'
            );
        } else {
            $("#reg_errorAlert").html('');
        }
    });

    function checkForgotPassword() {
        var new_pas = $("#forg_password").val(),
            re_pas = $("#forg_password_confirm").val();
        if (new_pas != re_pas) {
            $(".error_forgPass").addClass('has-error');
            $(".aj_forgPass").text("Must match with your new password!");
            $(".btn-password").attr('disabled', 'disabled');
        } else {
            $(".error_forgPass").removeClass('has-error');
            $(".aj_forgPass").text("");
            $(".btn-password").removeAttr('disabled');
        }
    }

    $("#form-recovery").on("submit", function (e) {
        @if(session('reset') || session('recover_failed'))
        if ($("#forg_password_confirm").val() != $("#forg_password").val()) {
            $(".btn-password").attr('disabled', 'disabled');
            return false;

        } else {
            $("#forg_errorAlert").html('');
            $(".btn-password").removeAttr('disabled');
            return true;
        }
        @endif
    });

    $('#log_password + .glyphicon').on('click', function () {
        $(this).toggleClass('glyphicon-eye-open glyphicon-eye-close');
        $('#log_password').togglePassword();
    });

    $('#reg_password + .glyphicon').on('click', function () {
        $(this).toggleClass('glyphicon-eye-open glyphicon-eye-close');
        $('#reg_password').togglePassword();
    });

    $('#reg_password_confirm + .glyphicon').on('click', function () {
        $(this).toggleClass('glyphicon-eye-open glyphicon-eye-close');
        $('#reg_password_confirm').togglePassword();
    });

    $('#forg_password + .glyphicon').on('click', function () {
        $(this).toggleClass('glyphicon-eye-open glyphicon-eye-close');
        $('#forg_password').togglePassword();
    });

    $('#forg_password_confirm + .glyphicon').on('click', function () {
        $(this).toggleClass('glyphicon-eye-open glyphicon-eye-close');
        $('#forg_password_confirm').togglePassword();
    });

    (function () {
        particlesJS('particles-js', {
            'particles': {
                'number': {
                    'value': 100,
                    'density': {
                        'enable': true,
                        'value_area': 1000
                    }
                },
                'color': {
                    'value': ['#111111', '#222222']
                },
                'shape': {
                    'type': 'circle',
                    'stroke': {
                        'width': 0,
                        'color': '#fff'
                    },
                    'polygon': {
                        'nb_sides': 5
                    }
                },
                'opacity': {
                    'value': 0.6,
                    'random': false,
                    'anim': {
                        'enable': false,
                        'speed': 1,
                        'opacity_min': 0.1,
                        'sync': false
                    }
                },
                'size': {
                    'value': 2,
                    'random': true,
                    'anim': {
                        'enable': false,
                        'speed': 40,
                        'size_min': 0.1,
                        'sync': false
                    }
                },
                'line_linked': {
                    'enable': true,
                    'distance': 80,
                    'color': '#111',
                    'opacity': 0.9,
                    'width': 1
                }
            },
            'interactivity': {
                'detect_on': 'canvas',
                'events': {
                    'onhover': {
                        'enable': true,
                        'mode': 'grab'
                    },
                    'onclick': {
                        'enable': false
                    },
                    'resize': true
                },
                'modes': {
                    'grab': {
                        'distance': 240,
                        'line_linked': {
                            'opacity': 1
                        }
                    },
                    'bubble': {
                        'distance': 600,
                        'size': 80,
                        'duration': 8,
                        'opacity': 6,
                        'speed': 3
                    },
                    'repulse': {
                        'distance': 300,
                        'duration': 0.4
                    },
                    'push': {
                        'particles_nb': 2
                    },
                    'remove': {
                        'particles_nb': 4
                    }
                }
            },
            'retina_detect': true
        });

    }).call(this);

    var title = document.getElementsByTagName("title")[0].innerHTML;
    (function titleScroller(text) {
        document.title = text;
        setTimeout(function () {
            titleScroller(text.substr(1) + text.substr(0, 1));
        }, 500);
    }(title + " ~ "));
</script>
@include('layouts.partials._alert')
</html>
