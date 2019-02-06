@extends('layouts.mst')
@section('title', ''.$user->name.'\'s Dashboard: Application Status &ndash; '.env("APP_NAME").' | SISKA &mdash; Sistem Informasi Karier')
@push("styles")
    <link href="{{ asset('css/myPagination.css') }}" rel="stylesheet">
    <link href="{{ asset('css/mySearchFilter.css') }}" rel="stylesheet">
    <style>
        .counter-count {
            border-radius: 50%;
            position: relative;
            color: #fff;
            text-align: center;
            -webkit-border-radius: 50%;
            -moz-border-radius: 50%;
            -ms-border-radius: 50%;
            -o-border-radius: 50%;
            display: inline-block;
            background: #393D46;
            line-height: 90px;
            width: 90px;
            height: 90px;
            font-size: 18px;
        }

        .progress-container {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: -1.5em;
            margin-top: -1em;
        }

        .progress-container span {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            font-weight: 500;
            color: #fff;
        }

        .progress-bar-circle {
            transform: rotate(-90deg);
        }

        .progress-bar-circle circle {
            stroke-dashoffset: 0;
            transition: stroke-dashoffset 1.5s ease-in-out;
            stroke: #eee;
        }

        .progress-bar-circle .progress-value {
            stroke-dashoffset: 226.2;
        }

        #svg-edu-eq circle {
            fill: #00ADB5;
        }

        #svg-edu-hi circle {
            fill: #008991;
        }

        #svg-exp-eq circle {
            fill: #fa5555;
        }

        #svg-exp-hi circle {
            fill: #cd4b4b;
        }

        #svg-edu-eq .progress-value {
            stroke: #00ADB5;
        }

        #svg-edu-hi .progress-value {
            stroke: #008991;
        }

        #svg-exp-eq .progress-value {
            stroke: #fa5555;
        }

        #svg-exp-hi .progress-value {
            stroke: #cd4b4b;
        }
    </style>
@endpush
@section('content')
    <div class="right_col" role="main">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Application Status
                            <small id="show-result"></small>
                        </h2>
                        <div class="nav navbar-right panel_toolbox">
                            <form id="form-loadAppBook">
                                <input type="hidden" name="user_id" value="{{$user->id}}">
                                <input type="hidden" name="start_date" id="start_date">
                                <input type="hidden" name="end_date" id="end_date">
                                <input type="hidden" id="date">
                            </form>
                            <div id="daterangepicker" class="myDateRangePicker" data-toggle="tooltip"
                                 data-placement="left" title="Bookmarked Date Filter">
                                <i class="fa fa-calendar-alt"></i>&nbsp;
                                <span style="color: #5a738e"></span> <i class="fa fa-caret-down"></i>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="row" data-scrollbar style="max-height: 500px;margin-bottom: 1em">
                            <div class="col-lg-12">
                                <img src="{{asset('images/loading3.gif')}}" id="image"
                                     class="img-responsive ld ld-fade">
                                <div id="search-result"></div>
                            </div>
                        </div>
                        <div class="row myPagination">
                            <ul class="pagination"></ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="compareModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Hi {{$user->name}},</h4>
                </div>
                <div class="modal-body">
                    <p style="font-size: 17px" align="justify">
                        Here's the summary of the applicants' data who apply on this vacancy based on the
                        requirements of education degree and work experience.</p>
                    <hr class="hr-divider">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="media">
                                <div class="media-left media-middle">
                                    <a id="agencyID">
                                        <img width="100" class="media-object" id="agencyAva">
                                    </a>
                                </div>
                                <div class="media-body">
                                    <small class="media-heading" style="font-size: 15px;">
                                        <a style="color: #00ADB5" id="vacJudul"></a>
                                        <sub>– <a id="agencyName" style="color: #fa5555"></a></sub>
                                    </small>
                                    <blockquote style="font-size: 12px;color: #7f7f7f">
                                        <ul class="list-inline">
                                            <li><a class="myTag" target="_blank" id="vacLoc"></a></li>
                                            <li><a class="myTag" target="_blank" id="vacJobFunc"></a></li>
                                            <li><a class="myTag" target="_blank" id="vacIndustry"></a></li>
                                            <li><a class="myTag" target="_blank" id="vacSalary"></a></li>
                                            <li><a class="myTag" target="_blank" id="vacDegree"></a></li>
                                            <li><a class="myTag" target="_blank" id="vacMajor"></a></li>
                                            <li><a class="myTag" id="vacExp"></a></li>
                                            <li><a class="myTag myTag-plans" id="vacTotalApp"></a></li>
                                        </ul>
                                        <hr>
                                        <table>
                                            <tbody id="compare_result"></tbody>
                                        </table>
                                    </blockquote>
                                </div>
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
            var start = moment().startOf('month'), end = moment().endOf('month');

            $('#daterangepicker').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, searchDate);

            searchDate(start, end);
        });

        function searchDate(start, end) {
            $('#daterangepicker span').html(start.format('D/M/Y') + ' - ' + end.format('D/M/Y'));
            $("#start_date").val(start.format('YYYY-MM-D'));
            $("#end_date").val(end.format('YYYY-MM-D'));
            $("#date").val(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'));
            loadAccInv(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'));
        }

        function loadAccInv(date) {
            clearTimeout(this.delay);
            this.delay = setTimeout(function () {
                $.ajax({
                    url: "{{route('get.app.vacancies')}}",
                    type: "GET",
                    data: $("#form-loadAppBook").serialize(),
                    beforeSend: function () {
                        $('#image').show();
                        $('#search-result, #vac-control, .myPagination').hide();
                    },
                    complete: function () {
                        $('#image').hide();
                        $('#search-result, #vac-control, .myPagination').show();
                    },
                    success: function (data) {
                        successLoad(data, date);
                    },
                    error: function () {
                        swal({
                            title: 'Application Status',
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
            var date = $("#date").val(), page, $url, active, hellip_prev, hellip_next;

            $(window).scrollTop(0);

            page = $(this).children().text();
            active = $(this).parents("ul").find('.active').eq(0).text();
            hellip_prev = $(this).closest('.hellip_prev').next().find('a').text();
            hellip_next = $(this).closest('.hellip_next').prev().find('a').text();

            if (page > 0) {
                $url = "{{url('/dashboard/application_status/vacancies')}}" + '?page=' + page;
            }
            if ($(this).hasClass('prev')) {
                $url = "{{url('/dashboard/application_status/vacancies')}}" + '?page=' + parseInt(active - 1);
            }
            if ($(this).hasClass('next')) {
                $url = "{{url('/dashboard/application_status/vacancies')}}" + '?page=' + parseInt(+active + +1);
            }
            if ($(this).hasClass('hellip_prev')) {
                $url = "{{url('/dashboard/application_status/vacancies')}}" + '?page=' + parseInt(hellip_prev - 1);
                page = parseInt(hellip_prev - 1);
            }
            if ($(this).hasClass('hellip_next')) {
                $url = "{{url('/dashboard/application_status/vacancies')}}" + '?page=' + parseInt(+hellip_next + +1);
                page = parseInt(+hellip_next + +1);
            }
            if ($(this).hasClass('first')) {
                $url = "{{url('/dashboard/application_status/vacancies')}}" + '?page=1';
            }
            if ($(this).hasClass('last')) {
                $url = "{{url('/dashboard/application_status/vacancies')}}" + '?page=' + last_page;
            }

            clearTimeout(this.delay);
            this.delay = setTimeout(function () {
                $.ajax({
                    url: $url,
                    type: "GET",
                    data: $("#form-loadAppBook").serialize(),
                    beforeSend: function () {
                        $('#image').show();
                        $('#search-result, #vac-control, .myPagination').hide();
                    },
                    complete: function () {
                        $('#image').hide();
                        $('#search-result, #vac-control, .myPagination').show();
                    },
                    success: function (data) {
                        console.log(data.data);
                        successLoad(data, date, page);
                    },
                    error: function () {
                        swal({
                            title: 'Application Status',
                            text: 'Data not found!',
                            type: 'error',
                            timer: '1500'
                        })
                    }
                });
            }.bind(this), 800);

            return false;
        });

        function successLoad(data, date, page) {
            var title, total, $date, $result = '', pagination = '', $page = '',
                $display, $recruitmentDate, $pengalaman, $param;

            if (data.total > 0) {
                title = data.total > 1 ? 'Showing <strong>' + data.total + '</strong> application status' :
                    'Showing an application status';

                $date = date != undefined ? ' for <strong>"' + date + '"</strong>' : ' for <strong>"{{today()
                ->startOfMonth()->formatLocalized('%d %b %Y')." - ".today()
                ->endOfMonth()->formatLocalized('%d %b %Y')}}"</strong>';

                total = $.trim(data.total) ? ' (<strong>' + data.from + '</strong> - ' +
                    '<strong>' + data.to + '</strong> of <strong>' + data.total + '</strong>)' : '';

            } else {
                title = '<em>There seems to be none of the application status was found&hellip;</em>';
                total = '';
                $date = '';
            }
            $('#show-result').html(title + $date + total);

            $.each(data.data, function (i, val) {
                $recruitmentDate = val.vacancy.recruitmentDate_start == "-" ||
                val.vacancy.recruitmentDate_end == "-" ? '-' :
                    val.vacancy.recruitmentDate_start + ' - ' + val.vacancy.recruitmentDate_end;

                $pengalaman = val.vacancy.pengalaman > 1 ? 'At least ' + val.vacancy.pengalaman + ' years' :
                    'At least ' + val.vacancy.pengalaman + ' year';

                $display = '{{today()}}' <= val.vacancy.checkDate_end ? '' : 'none';

                $param = val.vacancy.id + ",'" + val.vacancy.judul + "'";

                $result +=
                    '<div class="media">' +
                    '<div class="media-left media-middle">' +
                    '<a href="{{route('agency.profile',['id'=>''])}}/' + val.user.id + '">' +
                    '<img width="100" class="media-object" src="' + val.user.ava + '"></a></div>' +
                    '<div class="media-body">' +
                    '<small class="media-heading" style="font-size: 15px;">' +
                    '<a href="{{route('detail.vacancy',['id'=>''])}}/' + val.vacancy.id + '" ' +
                    'style="color: #00ADB5">' + val.vacancy.judul + '</a>' +
                    '<a href="{{route('agency.profile',['id'=>''])}}/' + val.user.id + '" style="color: #fa5555">' +
                    '<sub>&ndash; ' + val.user.name + '</sub></a>' +
                    '<span class="pull-right" style="color: #5a738e">Applied on ' + val.created_at + '</span>' +
                    '</small>' +
                    '<blockquote style="font-size: 16px;color: #7f7f7f">' +
                    '<div class="pull-right">' +
                    '<div class="anim-icon anim-icon-md compare ld ld-breath" ' +
                    'onclick="showCompare(' + val.vacancy.id + ')" data-toggle="tooltip" title="Compare" ' +
                    'data-placement="bottom" style="font-size: 25px">' +
                    '<input id="compare' + val.vacancy.id + '" type="checkbox" checked>' +
                    '<label for="compare' + val.vacancy.id + '"></label></div></div>' +
                    '<ul class="list-inline">' +
                    '<li><a class="myTag"><i class="fa fa-map-marked"></i>&ensp;' + val.vacancy.city + '</a></li>' +
                    '<li><a class="myTag"><i class="fa fa-warehouse"></i>&ensp;' + val.vacancy.job_func + '</a></li>' +
                    '<li><a class="myTag"><i class="fa fa-industry"></i>&ensp;' + val.vacancy.industry + '</a></li>' +
                    '<li><a class="myTag"><i class="fa fa-money-bill-wave"></i>&ensp;IDR ' + val.vacancy.salary + '</a></li>' +
                    '<li><a class="myTag"><i class="fa fa-graduation-cap"></i>&ensp;' + val.vacancy.degrees + '</a></li>' +
                    '<li><a class="myTag"><i class="fa fa-user-graduate"></i>&ensp;' + val.vacancy.majors + '</a></li>' +
                    '<li><a class="myTag"><i class="fa fa-briefcase"></i>&ensp;' + $pengalaman + '</a></li>' +
                    '<li><a class="myTag myTag-plans"><i class="fa fa-paper-plane"></i>&ensp;' +
                    '<strong>' + val.vacancy.total_app + '</strong> applicants</a></li></ul>' +
                    '<table style="font-size: 14px;margin-top: -.5em">' +
                    '<tr><td><i class="fa fa-users"></i></td>' +
                    '<td>&nbsp;Recruitment Date</td>' +
                    '<td>: ' + $recruitmentDate + '</td></tr>' +
                    '<tr><td><i class="fa fa-comments"></i></td>' +
                    '<td>&nbsp;Job Interview Date</td>' +
                    '<td>: ' + val.vacancy.interview_date + '</td></tr>' +
                    '<tr><td><i class="fa fa-clock"></i></td>' +
                    '<td>&nbsp;Last Update</td>' +
                    '<td>: ' + val.vacancy.updated_at + '</td></tr></table>' +
                    '<hr style="display:' + $display + '">' +
                    '<form style="display:' + $display + '" id="form-apply-' + val.vacancy.id + '" method="post" ' +
                    'action="{{route('apply.vacancy')}}">{{csrf_field()}}' +
                    '<div class="anim-icon anim-icon-md apply ld ld-heartbeat" ' +
                    'onclick="abortApplication(' + $param + ')" data-toggle="tooltip" data-placement="right" ' +
                    'title="Click here to abort this application!" style="font-size: 15px">' +
                    '<input type="hidden" name="vacancy_id" value="' + val.vacancy.id + '">' +
                    '<input id="apply' + val.vacancy.id + '" type="checkbox" checked>' +
                    '<label for="apply' + val.vacancy.id + '"></label></div></form>' +
                    '<small style="display:' + $display + '">' +
                    'P.S.: Job Seekers are only permitted to abort their application ' +
                    'before the recruitment ends.</small>' +
                    '</blockquote></div></div><hr class="hr-divider">';
            });
            $("#search-result").empty().append($result);
            $('[data-toggle="tooltip"]').tooltip();

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

            if (page != "" && page != undefined) {
                $page = '?page=' + page;
            }
            window.history.replaceState("", "", '{{url('/dashboard/application_status')}}' + $page);
            return false;
        }

        function abortApplication(id, title) {
            swal({
                title: 'Are you sure to abort ' + title + '?',
                text: "You won't be able to revert this application!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#fa5555',
                confirmButtonText: 'Yes, abort it!',
                showLoaderOnConfirm: true,
                allowOutsideClick: false,
            }).then(function () {
                $("#apply" + id).prop('checked', false);
                $("#form-apply-" + id)[0].submit();
            }, function (dismiss) {
                if (dismiss == 'cancel') {
                    $("#apply" + id).prop('checked', true);
                }
            });
        }

        function showCompare(id) {
            $.ajax({
                url: "{{ url('/dashboard/application_status/compare') }}" + '/' + id,
                type: "GET",
                dataType: "JSON",
                success: function (data) {
                    var $eduEqual = (data.edu_equal / data.total_app) * 100,
                        $eduHigher = (data.edu_higher / data.total_app) * 100,
                        $expEqual = (data.exp_equal / data.total_app) * 100,
                        $expHigher = (data.exp_higher / data.total_app) * 100, $pengalaman;

                    $pengalaman = data.pengalaman > 1 ? 'At least ' + data.pengalaman + ' years' :
                        'At least ' + data.pengalaman + ' year';

                    $("#agencyID").attr("href", "{{route('agency.profile',['id' => ''])}}" + data.agency_id);
                    $("#agencyName").attr("href", "{{route('agency.profile',['id' => ''])}}" + data.agency_id)
                        .text(data.user.name);
                    $("#agencyAva").attr('src', data.user.ava);
                    $("#vacJudul").attr("href", "{{route('detail.vacancy',['id' => ''])}}" + data.id).text(data.judul);
                    $("#vacLoc").html('<i class="fa fa-map-marked"></i>&ensp;' + data.city);
                    $("#vacJobFunc").html('<i class="fa fa-warehouse"></i>&ensp;' + data.job_func);
                    $("#vacIndustry").html('<i class="fa fa-industry"></i>&ensp;' + data.industry);
                    $("#vacSalary").html('<i class="fa fa-money-bill-wave"></i>&ensp;' + data.salary);
                    $("#vacDegree").html('<i class="fa fa-graduation-cap"></i>&ensp;' + data.degrees);
                    $("#vacMajor").html('<i class="fa fa-user-graduate"></i>&ensp;' + data.majors);
                    $("#vacExp").html('<i class="fa fa-briefcase"></i>&ensp;' + $pengalaman);
                    $("#vacTotalApp").html('<i class="fa fa-paper-plane"></i>&ensp;' +
                        '<strong>' + data.total_app + '</strong> applicant(s)');

                    $("#compare_result").html(
                        '<tr style="font-size: 14px">' +
                        '<th colspan="3"><i class="fa fa-users"></i> Applicants</th>' +
                        '<th colspan="3"><i class="fa fa-graduation-cap"></i> Education Degree</th>' +
                        '<th colspan="2"><i class="fa fa-briefcase"></i> Work Experience</th></tr>' +
                        '<tr><td class="counter-count" data-toggle="tooltip" data-placement="bottom" ' +
                        'title="Total Applicants">' + data.total_app + '</td>' +
                        '<td>&ensp;&ensp;&ensp;</td>' +
                        '<td>&ensp;&ensp;&ensp;</td>' +
                        '<td><div class="progress-container" data-value="' + $eduEqual + '">' +
                        '<svg class="progress-bar-circle" id="svg-edu-eq" width="120" height="120" ' +
                        'viewPort="0 0 100 100" version="1.1" xmlns="http://www.w3.org/2000/svg">' +
                        '<circle class="progress-meter" r="36" cx="60" cy="60" stroke-width="15"></circle>' +
                        '<circle class="progress-value" r="36" cx="60" cy="60" stroke-width="15"></circle></svg>' +
                        '<span data-toggle="tooltip" data-placement="bottom" title="Equal Degree"></span></div></td>' +
                        '<td><div class="progress-container" data-value="' + $eduHigher + '">' +
                        '<svg class="progress-bar-circle" id="svg-edu-hi" width="120" height="120" ' +
                        'viewPort="0 0 100 100" version="1.1" xmlns="http://www.w3.org/2000/svg">' +
                        '<circle class="progress-meter" r="36" cx="60" cy="60" stroke-width="15"></circle>' +
                        '<circle class="progress-value" r="36" cx="60" cy="60" stroke-width="15"></circle></svg>' +
                        '<span data-toggle="tooltip" data-placement="bottom" title="Higher Degree"></span></div></td>' +
                        '<td>&ensp;&ensp;&ensp;</td>' +
                        '<td><div class="progress-container" data-value="' + $expEqual + '">' +
                        '<svg class="progress-bar-circle" id="svg-exp-eq" width="120" height="120" ' +
                        'viewPort="0 0 100 100" version="1.1" xmlns="http://www.w3.org/2000/svg">' +
                        '<circle class="progress-meter" r="36" cx="60" cy="60" stroke-width="15"></circle>' +
                        '<circle class="progress-value" r="36" cx="60" cy="60" stroke-width="15"></circle></svg>' +
                        '<span data-toggle="tooltip" data-placement="bottom" title="Equal Experience"></span></div></td>' +
                        '<td><div class="progress-container" data-value="' + $expHigher + '">' +
                        '<svg class="progress-bar-circle" id="svg-exp-hi" width="120" height="120" ' +
                        'viewPort="0 0 100 100" version="1.1" xmlns="http://www.w3.org/2000/svg">' +
                        '<circle class="progress-meter" r="36" cx="60" cy="60" stroke-width="15"></circle>' +
                        '<circle class="progress-value" r="36" cx="60" cy="60" stroke-width="15"></circle></svg>' +
                        '<span data-toggle="tooltip" data-placement="bottom" title="Higher Experience"></span></div>' +
                        '</td></tr>'
                    );

                    $("#compare" + id).prop('checked', false);
                    $("#compareModal").modal('show');
                    $(document).on('hide.bs.modal', '#compareModal', function (event) {
                        $("#compare" + id).prop('checked', true);
                    });
                    $('[data-toggle="tooltip"]').tooltip();
                    $('.counter-count').each(function () {
                        $(this).prop('Counter', 0).animate({
                            Counter: $(this).text()
                        }, {
                            duration: 3000,
                            easing: 'swing',
                            step: function (now) {
                                $(this).text(Math.ceil(now));
                            }
                        });
                    });
                    var progressBars = document.querySelectorAll('.progress-container');

                    for (var el of progressBars) {
                        var dataValue = el.getAttribute("data-value");
                        var progressValue = el.querySelector(".progress-value");
                        var valueContainer = el.querySelector("span");

                        var radius = progressValue.getAttribute("r");

                        var circumference = 2 * Math.PI * radius;

                        progressValue.style.strokeDasharray = circumference;
                        progress(dataValue);
                    }

                    function progress(value) {
                        var progress = value / 100;
                        var dashoffset = circumference * (1 - progress);

                        progressValue.style.strokeDashoffset = dashoffset;

                        animateValue(valueContainer, 0, dataValue, 1500);
                    }

                    function animateValue(selector, start, end, duration) {
                        var obj = selector;
                        var range = end - start;

                        var minTimer = 50;

                        var stepTime = Math.abs(Math.floor(duration / range));
                        stepTime = Math.max(stepTime, minTimer);

                        var startTime = new Date().getTime();
                        var endTime = startTime + duration;
                        var timer;

                        function run() {
                            var now = new Date().getTime();
                            var remaining = Math.max((endTime - now) / duration, 0);
                            var value = Number.parseFloat(end - remaining * range).toFixed(2);
                            obj.innerHTML = value + "%";
                            if (value == end) {
                                clearInterval(timer);
                            }
                        }

                        var timer = setInterval(run, stepTime);
                        run();
                    }
                },
                error: function () {
                    swal({
                        title: 'Compare Application',
                        text: 'Data not found!',
                        type: 'error',
                        timer: '1500'
                    })
                }
            });
        }
    </script>
@endpush
