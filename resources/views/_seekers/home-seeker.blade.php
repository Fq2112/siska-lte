@extends('layouts.mst')
@section('title', 'Search Vacancy &ndash; '.env("APP_NAME").' | SISKA &mdash; Sistem Informasi Karier')
@push("styles")
    <link href="{{ asset('css/myPagination.css') }}" rel="stylesheet">
    <link href="{{ asset('css/mySearchFilter.css') }}" rel="stylesheet">
    <style>
        [data-scrollbar] {
            max-height: 575px;
        }
    </style>
@endpush
@section('content')
    <div class="right_col" role="main">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Search Result
                            <small id="show-result"></small>
                        </h2>
                        <div class="dc-view-switcher navbar-right panel_toolbox">
                            <label>&ensp;View: </label>
                            <button data-trigger="grid-view" type="button"></button>
                            <button data-trigger="list-view" class="active" type="button"></button>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content" id="vacancy-list">
                        <div class="row">
                            <div class="col-lg-12">
                                <form class="form-horizontal" role="search" id="form-search">
                                    <div id="custom-search-input">
                                        <div class="input-group">
                                            <div class="input-group-btn dropdown">
                                                <button style="text-transform: uppercase;letter-spacing: 2px"
                                                        id="lokasi" type="button"
                                                        class="btn btn-default dropdown-toggle"
                                                        data-toggle="dropdown" aria-expanded="false"
                                                        title="Pilih lokasi">
                                                    {{empty($location) ? 'filter' : $location}}
                                                    <span class="fa fa-caret-down"></span>
                                                </button>
                                                <ul class="dropdown-menu scrollable-menu" id="list-lokasi">
                                                    <div class="row form-group has-feedback">
                                                        <div class="col-lg-12">
                                                            <input class="form-control" type="text"
                                                                   placeholder="Search location&hellip;"
                                                                   id="txt_filter" onkeyup="filterFunction()"
                                                                   autofocus>
                                                        </div>
                                                    </div>
                                                    <li id="divider" class="divider"></li>
                                                    @foreach($provinces as $province)
                                                        <li class="province{{$province->id}} dropdown-header">
                                                            <strong style="font-size: 15px;margin-left: -1em">{{$province->name}}</strong>
                                                        </li>
                                                        @foreach($province->getCity as $city)
                                                            <li data-value="{{$city->id}}"
                                                                data-id="{{$province->id}}">
                                                                <a style="font-size: 15px;cursor: pointer;">{{substr($city->name, 0, 2)=="Ko" ?
                                                    substr($city->name,5) : substr($city->name,10)}}</a>
                                                            </li>
                                                        @endforeach
                                                        <li class="province{{$province->id}} divider"></li>
                                                    @endforeach
                                                    <li class="not_found dropdown-header"
                                                        style="display: none;">
                                                        <strong>&nbsp;</strong></li>
                                                </ul>
                                            </div>
                                            <input id="txt_keyword" type="text" name="q"
                                                   class="form-control myInput input-lg"
                                                   onkeyup="showResetBtn(this.value)"
                                                   placeholder="Job Title or Agency's Name&hellip;"
                                                   value="{{!empty($keyword) ? $keyword : ''}}">
                                            <input type="hidden" name="loc" id="txt_location"
                                                   value="{{!empty($location) ? $location : ''}}">
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
                        <div class="row" style="margin-top: 1em;margin-bottom: 1em" data-scrollbar>
                            <img src="{{asset('images/loading3.gif')}}" id="image" class="img-responsive ld ld-fade">
                            <div data-view="list-view" class="download-cards" id="search-result"></div>
                        </div>
                        <div class="row myPagination">
                            <ul class="pagination"></ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push("scripts")
    <script>
        var last_page;

        $(function () {
            $('.dc-view-switcher button[data-trigger="grid-view"]').click();

            $('#image').hide();
            $('#search-result').show();
            $('.myPagination').show();

            var keyword = '{{$keyword}}', location = '{{$location}}', page = '{{$page}}',
                $page = page != "" && page != undefined ? '&page=' + page : '';

            if (keyword != "" || location != "") {
                $("#btn_reset").show();
            } else {
                $("#btn_reset").hide();
            }

            $.get('api/vacancies/search?q=' + keyword + '&loc=' + location + $page, function (data) {
                last_page = data.last_page;
                successLoad(data, keyword, location, page);
            });
        });

        $('#txt_keyword').on('keyup', function () {
            loadVacancy();
        });

        $("#list-lokasi li a").on("click", function () {
            $("#txt_location").val($(this).text());
            loadVacancy();
        });

        $("#form-search").on('submit', function (event) {
            event.preventDefault();
            return false;
        });

        $("#btn_reset").on("click", function () {
            $("#lokasi").html('Filter&nbsp;<span class="fa fa-caret-down">' + '</span>');
            $("#txt_keyword").removeAttr('value');
            $("#form-search input").val('');
            loadVacancy();
        });

        function loadVacancy() {
            var keyword = $("#txt_keyword").val(), location = $("#txt_location").val();

            clearTimeout(this.delay);
            this.delay = setTimeout(function () {
                $.ajax({
                    url: "{{route('get.search.vacancy')}}",
                    type: "GET",
                    data: $("#form-search").serialize(),
                    beforeSend: function () {
                        $('#image').show();
                        $('#search-result').hide();
                        $('.myPagination').hide();
                    },
                    complete: function () {
                        $('#image').hide();
                        $('#search-result').show();
                        $('.myPagination').show();
                    },
                    success: function (data) {
                        successLoad(data, keyword, location);
                    },
                    error: function () {
                        swal({
                            title: 'Search Vacancy',
                            text: 'Data not found!',
                            type: 'error',
                            timer: '1500'
                        })
                    }
                });
            }.bind(this), 800);

            return false;
        }

        $('.myPagination ul').on('click', 'li', function () {
            $(window).scrollTop(0);

            var keyword = $("#txt_keyword").val(), location = $("#txt_location").val(),
                page, $url, active, hellip_prev, hellip_next;

            page = $(this).children().text();
            active = $(this).parents("ul").find('.active').eq(0).text();
            hellip_prev = $(this).closest('.hellip_prev').next().find('a').text();
            hellip_next = $(this).closest('.hellip_next').prev().find('a').text();

            if (page > 0) {
                $url = "{{url('/api')}}" + '/vacancies/search?page=' + page;
            }
            if ($(this).hasClass('prev')) {
                $url = "{{url('/api')}}" + '/vacancies/search?page=' + parseInt(active - 1);
            }
            if ($(this).hasClass('next')) {
                $url = "{{url('/api')}}" + '/vacancies/search?page=' + parseInt(+active + +1);
            }
            if ($(this).hasClass('hellip_prev')) {
                $url = "{{url('/api')}}" + '/vacancies/search?page=' + parseInt(hellip_prev - 1);
                page = parseInt(hellip_prev - 1);
            }
            if ($(this).hasClass('hellip_next')) {
                $url = "{{url('/api')}}" + '/vacancies/search?page=' + parseInt(+hellip_next + +1);
                page = parseInt(+hellip_next + +1);
            }
            if ($(this).hasClass('first')) {
                $url = "{{url('/api')}}" + '/vacancies/search?page=1';
            }
            if ($(this).hasClass('last')) {
                $url = "{{url('/api')}}" + '/vacancies/search?page=' + last_page;
            }

            clearTimeout(this.delay);
            this.delay = setTimeout(function () {
                $.ajax({
                    url: $url,
                    type: "GET",
                    data: $("#form-search").serialize(),
                    beforeSend: function () {
                        $('#image').show();
                        $('#search-result').hide();
                        $('.myPagination').hide();
                    },
                    complete: function () {
                        $('#image').hide();
                        $('#search-result').show();
                        $('.myPagination').show();
                    },
                    success: function (data) {
                        successLoad(data, keyword, location, page);
                    },
                    error: function () {
                        swal({
                            title: 'Search Vacancy',
                            text: 'Data not found!',
                            type: 'error',
                            timer: '1500'
                        })
                    }
                });
            }.bind(this), 800);

            return false;
        });

        function successLoad(data, keyword, location, page) {
            var title, total, $q, $loc, $pengalaman, $result, pagination;

            $q = keyword != "" ? ' for <strong>"' + keyword + '"</strong>' : '';
            $loc = location != "" ? ' in <strong>"' + location + '"</strong>' : '';

            if (data.total > 0) {
                title = data.total > 1 ? 'Showing <strong>' + data.total + '</strong> opportunities matched' :
                    'Showing an opportunity matched';
                total = $.trim(data.total) ? ' (<strong>' + data.from + '</strong> - ' +
                    '<strong>' + data.to + '</strong> of <strong>' + data.total + '</strong>)' : '';

            } else {
                title = 'Showing <strong>0</strong> opportunity matched';
                total = '';
            }
            $('#show-result').html(title + $q + $loc + total);

            $result = '';
            $.each(data.data, function (i, val) {
                if (val.pengalaman > 1) {
                    $pengalaman = 'At least ' + val.pengalaman + ' years';
                } else {
                    $pengalaman = 'At least ' + val.pengalaman + ' year';
                }
                $result +=
                    '<article class="download-card">' +
                    '<a href="{{route('agency.profile',['id' => ''])}}/' + val.agency_id + '">' +
                    '<div class="download-card__icon-box"><img src="' + val.agency.ava + '"></div></a>' +
                    '<div class="download-card__content-box">' +
                    '<div class="content">' +
                    '<h2 class="download-card__content-box__catagory">' + val.updated_at + '</h2>' +
                    '<a href="{{route('detail.vacancy',['id' => ''])}}/' + val.id + '">' +
                    '<h3 class="download-card__content-box__title">' + val.judul + '</h3></a>' +
                    '<a href="{{route('agency.profile',['id' => ''])}}/' + val.agency_id + '">' +
                    '<p class="download-card__content-box__description">' + val.agency.company + '</p></a>' +
                    '<table style="font-size: 14px"><tbody>' +
                    '<tr><td><i class="fa fa-industry"></i></td>' +
                    '<td>&nbsp;</td>' +
                    '<td>' + val.industry + '</td></tr>' +
                    '<tr><td><i class="fa fa-map-marked"></i></td>' +
                    '<td>&nbsp;</td>' +
                    '<td>' + val.city + '</td></tr>' +
                    '<tr><td><i class="fas fa-money-bill-wave"></i></td>' +
                    '<td>&nbsp;</td>' +
                    '<td>' + val.salary + '</td></tr>' +
                    '<tr><td><i class="fas fa-briefcase"></i></td>' +
                    '<td>&nbsp;</td>' +
                    '<td>' + $pengalaman + '</td></tr>' +
                    '</tbody></table></div></div>' +
                    '<div class="card-read-more">' +
                    '<a href="{{route('detail.vacancy',['id' => ''])}}/' + val.id + '" class="btn btn-block">' +
                    'Read More</a></div></article>';
            });
            $("#search-result").empty().append($result);

            // pagination
            pagination = '';
            if (data.last_page > 1) {

                if (data.current_page > 4) {
                    pagination += '<li class="first"><a href="' + data.first_page_url + '"><i class="fa fa-angle-double-left"></i></a></li>';
                }

                if ($.trim(data.prev_page_url)) {
                    pagination += '<li class="prev"><a href="' + data.prev_page_url + '" rel="prev"><i class="fa fa-angle-left"></i></a></li>';
                } else {
                    pagination += '<li class="disabled"><span><i class="fa fa-angle-left"></i></span></li>';
                }

                if (data.current_page > 4) {
                    pagination += '<li class="hellip_prev"><a style="cursor: pointer">&hellip;</a></li>'
                }

                for ($i = 1; $i <= data.last_page; $i++) {
                    if ($i >= data.current_page - 3 && $i <= data.current_page + 3) {
                        if (data.current_page == $i) {
                            pagination += '<li class="active"><span>' + $i + '</span></li>'
                        } else {
                            pagination += '<li><a style="cursor: pointer">' + $i + '</a></li>'
                        }
                    }
                }

                if (data.current_page < data.last_page - 3) {
                    pagination += '<li class="hellip_next"><a style="cursor: pointer">&hellip;</a></li>'
                }

                if ($.trim(data.next_page_url)) {
                    pagination += '<li class="next"><a href="' + data.next_page_url + '" rel="next"><i class="fa fa-angle-right"></i></a></li>';
                } else {
                    pagination += '<li class="disabled"><span><i class="fa fa-angle-right"></i></span></li>';
                }

                if (data.current_page < data.last_page - 3) {
                    pagination += '<li class="last"><a href="' + data.last_page_url + '"><i class="fa fa-angle-double-right"></i></a></li>';
                }
            }
            $('.myPagination ul').html(pagination);

            generateUrl(keyword, location, page);
            return false;
        }

        function generateUrl(keyword, location, page) {
            var $page = page != "" && page != undefined ? '&page=' + page : '';

            window.history.replaceState("", "", '{{url('/')}}' + '/search?q=' + keyword + '&loc=' + location + $page);
        }
    </script>
@endpush
