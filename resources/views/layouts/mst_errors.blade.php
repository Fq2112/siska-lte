<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    <!-- Bootstrap -->
    <link href="{{asset('css/bootstrap.css')}}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{asset('fonts/fontawesome-free/css/all.css')}}" rel="stylesheet">
    <!-- NProgress -->
    <link href="{{asset('css/nprogress.css')}}" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="{{asset('css/custom.css')}}" rel="stylesheet">
    <link href="{{ asset('css/myPagination.css') }}" rel="stylesheet">
    <link href="{{ asset('css/mySearchFilter.css') }}" rel="stylesheet">
    <style>
        [data-scrollbar] {
            max-height: 575px;
        }
    </style>
</head>

<body class="nav-md">
<div class="container body">
    <div class="main_container">
        <!-- page content -->
        <div class="col-md-12">
            <div class="col-middle">
                <div class="text-center text-center">
                    <h1 class="error-number">@yield('error_number')</h1>
                    <h2>@yield('error_title')</h2>
                    <p>@yield('error_message')</p>
                    <div class="mid_center" style="width: 670px;">
                        <h3>Search Vacancy</h3>
                        <form action="{{route('home-seeker')}}" class="form-horizontal" role="search" id="form-search">
                            <div id="custom-search-input">
                                <div class="input-group">
                                    <div class="input-group-btn dropdown">
                                        <button style="text-transform: uppercase;letter-spacing: 2px" id="lokasi"
                                                type="button"
                                                class="btn btn-default dropdown-toggle"
                                                data-toggle="dropdown" aria-expanded="false"
                                                title="Pilih lokasi">Filter <span class="fa fa-caret-down"></span>
                                        </button>
                                        <ul class="dropdown-menu scrollable-menu" id="list-lokasi">
                                            <div class="row form-group has-feedback">
                                                <div class="col-lg-12">
                                                    <input class="form-control" type="text"
                                                           placeholder="Search location&hellip;"
                                                           id="txt_filter" onkeyup="filterFunction()" autofocus>
                                                </div>
                                            </div>
                                            <li id="divider" class="divider"></li>
                                            @foreach(\App\Models\Provinces::all() as $province)
                                                <li class="province{{$province->id}} dropdown-header">
                                                    <strong style="font-size: 15px;margin-left: -1em">{{$province->name}}</strong>
                                                </li>
                                                @foreach($province->getCity as $city)
                                                    <li data-value="{{$city->id}}" data-id="{{$province->id}}">
                                                        <a style="font-size: 15px;cursor: pointer;">{{substr($city->name, 0, 2)=="Ko" ?
                                                        substr($city->name,5) : substr($city->name,10)}}</a>
                                                    </li>
                                                @endforeach
                                                <li class="province{{$province->id}} divider"></li>
                                            @endforeach
                                            <li class="not_found dropdown-header" style="display: none;">
                                                <strong>&nbsp;</strong>
                                            </li>
                                        </ul>
                                    </div>
                                    <input id="txt_keyword" type="text" name="q"
                                           class="form-control myInput input-lg"
                                           placeholder="Job Title or Agency's Name&hellip;"
                                           onkeyup="showResetBtn(this.value)">
                                    <input type="hidden" name="loc" id="txt_location">
                                    <span class="input-group-btn">
                                        <button type="reset" class="btn btn-info btn-lg" id="btn_reset">
                                            <span class="glyphicon glyphicon-remove">
                                                <span class="sr-only">Close</span>
                                            </span>
                                        </button>
                                        <button id="cari" class="btn btn-info btn-lg" type="submit">
                                            <i class="glyphicon glyphicon-search"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /page content -->
    </div>
</div>

<!-- jQuery -->
<script src="{{asset('js/jquery.min.js')}}"></script>
<!-- Bootstrap -->
<script src="{{asset('js/bootstrap.min.js')}}"></script>
<!-- FastClick -->
<script src="{{asset('js/fastclick.js')}}"></script>
<!-- NProgress -->
<script src="{{asset('js/nprogress.js')}}"></script>
<!-- Custom Theme Scripts -->
<script src="{{asset('js/custom.min.js')}}"></script>
<script src="{{asset('js/filter-gridList.js')}}"></script>
<script>
    $("#btn_reset").hide().on("click", function () {
        $("#lokasi").html('Filter&nbsp;<span class="fa fa-caret-down">' + '</span>');
        $("#txt_keyword").removeAttr('value');
        $("#form-search input").val('');
    });

    $("#list-lokasi li a").on("click", function () {
        var location = $(this).text();
        $('#lokasi').html(location + '&nbsp;<span class="fa fa-caret-down">' + '</span>');
        $("#btn_reset").show();
        $("#txt_location").val(location);
    });
</script>
</body>
</html>