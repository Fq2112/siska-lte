<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Account Page &ndash; {{env("APP_NAME")}} | SISKA &mdash; Sistem Informasi Karier</title>
    <!-- Bootstrap -->
    <link href="{{asset('css/bootstrap.css')}}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{asset('fonts/fontawesome-free/css/all.css')}}" rel="stylesheet">
    <!-- NProgress -->
    <link href="{{asset('css/nprogress.css')}}" rel="stylesheet">
    <!-- Animate.css -->
    <link href="{{asset('css/animate.min.css')}}" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="{{asset('css/custom.css')}}" rel="stylesheet">
    <link href="{{asset('css/signIn-Up.css')}}" rel="stylesheet">
    <script src='https://www.google.com/recaptcha/api.js'></script>
</head>

<body>
<div id="particles-js"></div>
<div class="wrapper">
    <div class="sign-panels">
        <div class="login">
            <div class="title">
                <span>Sign In</span>
                <p>Welcome back, please login to your account. You can sign in with your github, facebook, twitter,
                    google+ account or with your SISKA account.</p>
            </div>

            <div class="social">
                <a class="circle github" href="{{route('redirect', ['provider' => 'github'])}}"
                   data-toggle="tooltip" data-title="Github" data-placement="left">
                    <i class="fab fa-github fa-fw"></i>
                </a>
                <a id="facebook_login" class="circle facebook"
                   href="{{route('redirect', ['provider' => 'facebook'])}}"
                   data-toggle="tooltip" data-title="Facebook" data-placement="top">
                    <i class="fab fa-facebook-f fa-fw"></i>
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

            <form class="form-horizontal" method="post" accept-charset="UTF-8" action="{{route('login')}}"
                  id="form-login">
                {{ csrf_field() }}
                <div class="row form-group">
                    <input type="email" placeholder="Email" name="email" value="{{ old('email') }}" required>
                </div>
                <div class="row form-group">
                    <input type="password" placeholder="Password" name="password" minlength="6" required>
                </div>
                <div class="row form-group">
                    <div class="col-lg-4">
                        <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember">Keep me sign in</label>
                    </div>
                    <div class="col-lg-8">
                        <div class="g-recaptcha" data-sitekey="{{env('reCAPTCHA_v2_SITEKEY')}}"></div>
                    </div>
                </div>
                <div class="row">
                    <button class="btn btn-signin btn-block">Sign In</button>
                </div>
                @if(session('error'))
                    <strong>{{ $errors->first('password') }}</strong>
                    <a href="javascript:void(0)" class="btn-reset btn-fade">Recover your password
                        <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
                @endif
                <a href="javascript:void(0)" class="btn-member btn-fade">Looking to create an account?
                    <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
            </form>
        </div>

        <div class="signup">
            <div class="title">
                <span>Sign Up</span>
                <p>Create a new account. You can sign up with your github, facebook, twitter, google+ account or
                    with your SISKA account.</p>
            </div>

            <div class="social">
                <a class="circle github" href="{{route('redirect', ['provider' => 'github'])}}"
                   data-toggle="tooltip" data-title="Github" data-placement="left">
                    <i class="fab fa-github fa-fw"></i>
                </a>
                <a id="facebook_login" class="circle facebook"
                   href="{{route('redirect', ['provider' => 'facebook'])}}"
                   data-toggle="tooltip" data-title="Facebook" data-placement="top">
                    <i class="fab fa-facebook-f fa-fw"></i>
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

            <form class="form-horizontal" method="post" accept-charset="UTF-8" action="{{ route('register') }}"
                  id="form-register">
                {{ csrf_field() }}
                <div class="row form-group">
                    <input type="text" placeholder="Full name" name="name" required>
                </div>
                <div class="row form-group">
                    <input type="email" placeholder="Email" name="email" required>
                </div>
                <div class="row form-group">
                    <input type="password" placeholder="Password" name="password" minlength="6" required>
                </div>
                <div class="row form-group">
                    <input type="password" placeholder="Retype Password" name="password_confirmation" minlength="6"
                           required>
                </div>
                <div class="row">
                    <button class="btn btn-signup btn-block">Sign Up</button>
                </div>
                <a href="javascript:void(0)" class="btn-login btn-fade">Already have an account? Sign In
                    <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
            </form>
        </div>

        <div class="recover-password">
            <div class="title">
                <span>Recover Password</span>
                <p>Enter in the email associated with your account</p>
            </div>

            <form class="form-horizontal" method="post" accept-charset="UTF-8" action="{{ route('password.email') }}">
                {{ csrf_field() }}
                <div class="row form-group">
                    <input type="email" placeholder="Email" id="resetPassword" name="email" value="{{ old('email') }}"
                           required>
                    <span class="error"></span>
                </div>
                <div class="row">
                    <button class="btn-signup btn-password">Submit Reset</button>
                </div>
                <a href="javascript:void(0)" class="btn-login btn-fade">
                    <i class="fa fa-long-arrow-left" aria-hidden="true"></i> Cancel and go back to Login page </a>
            </form>

            <div class="notification">
                <p>Good job. An email containing information on how to reset your password was sent to
                    <span class="reset-mail"></span>. Please follow the instruction in that email to
                    reset your password. Thanks!</p>
            </div>

        </div>
    </div>
</div>
</body>

<!-- jQuery -->
<script src="{{asset('js/jquery.min.js')}}"></script>
<script src="{{asset('js/particles.min.js')}}"></script>
<script>
    $('.signup').hide();
    $('.recover-password').hide();


    $('.btn-reset').on("click", function () {
        $('.login').hide();
        $('.recover-password').fadeIn(300);
    });

    $('.btn-member').on("click", function () {
        $('.login').hide();
        $('.signup').fadeIn(300);
    });


    $('.btn-login').on("click", function () {
        $('.signup').hide();
        $('.recover-password').hide();
        $('.login').fadeIn(300);

    });

    $('.notification').hide();

    $('.btn-password').on("click", function () {

        if ($('#resetPassword').val() == 0) {
            // $('#resetPassword').after('<span class="error">Email not valid.</span>')
            $('.error').text('Email not valid.')
        } else {
            $('.reset-mail').text($('#resetPassword').val());
            $('.recover-password form').hide();
            $('.notification').fadeIn(300);
        }
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
</html>
