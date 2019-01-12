@extends('layouts.mst')
@section('title', ''.$vacancy->judul.'\'s Details &ndash; '.env("APP_NAME").' | SISKA &mdash; Sistem Informasi Karier')
@push("styles")
    <link href="{{ asset('css/mySearchFilter.css') }}" rel="stylesheet">
    <style>
        [data-scrollbar] {
            max-height: 300px;
        }

        #applyModal .modal-dialog {
            width: 60%;
        }

        #applyModal a, #resumeModal a {
            text-decoration: none;
        }

        #applyModal .modal-footer .card-read-more, #resumeModal .modal-footer .card-read-more {
            margin: -15px;
        }

        #applyModal .modal-footer .card-read-more button:hover, #resumeModal .modal-footer .card-read-more a:hover {
            color: #fff;
            border-radius: 0 0 4px 4px;
        }

        .card-read-more {
            border: none;
        }
    </style>
@endpush
@section('content')
    <div class="right_col" role="main">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>{{$vacancy->judul}}
                            <small>Vacancy Details</small>
                        </h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a>&nbsp;</a></li>
                            <li><a>&nbsp;</a></li>
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="col-md-3 col-sm-3 col-xs-12 profile_left">
                            <div class="row">
                                <div class="profile_img">
                                    <div id="crop-avatar">
                                        <a href="{{route('agency.profile',['id' => $agency->id])}}" target="_blank">
                                            <img class="img-responsive avatar-view" src="{{$agency->ava == "" ||
                                        $agency->ava == "agency.png" ? asset('images/agency.png') :
                                        asset('storage/admins/agencies/ava/'.$agency->ava)}}">
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <h3>{{$vacancy->judul}}</h3>
                                <ul class="list-unstyled user_data">

                                    <li data-toggle="tooltip" data-placement="left" title="Location">
                                        <i class="fa fa-map-marked user-profile-icon"></i> {{$city}}</li>
                                    <li data-toggle="tooltip" data-placement="left" title="Job Function">
                                        <i class="fa fa-warehouse user-profile-icon"></i> {{$jobfunc->name}}</li>
                                    <li data-toggle="tooltip" data-placement="left" title="Industry">
                                        <i class="fa fa-industry user-profile-icon"></i> {{$industry->name}}</li>
                                    <li data-toggle="tooltip" data-placement="left" title="Job Level">
                                        <i class="fa fa-level-up-alt user-profile-icon"></i> {{$joblevel->name}}</li>
                                    <li data-toggle="tooltip" data-placement="left" title="Job Type">
                                        <i class="fa fa-user-clock user-profile-icon"></i> {{$jobtype->name}}</li>
                                    <li data-toggle="tooltip" data-placement="left" title="Salary">
                                        <i class="fa fa-money-bill-wave user-profile-icon"></i> {{$salary->name}}</li>
                                    <li data-toggle="tooltip" data-placement="left" title="Education Degree">
                                        <i class="fa fa-graduation-cap user-profile-icon"></i> {{$degree->name}}</li>
                                    <li data-toggle="tooltip" data-placement="left" title="Education Major">
                                        <i class="fa fa-user-graduate user-profile-icon"></i> {{$major->name}}</li>
                                    <li data-toggle="tooltip" data-placement="left" title="Work Experience">
                                        <i class="fa fa-briefcase user-profile-icon"></i> At least {{$vacancy->pengalaman >1
                                    ? $vacancy->pengalaman.' years' : $vacancy->pengalaman.' year'}}</li>
                                    <li data-toggle="tooltip" data-placement="left" title="Total Applicant">
                                        <i class="fa fa-paper-plane user-profile-icon"></i> {{$applicants}} applicants
                                    </li>
                                </ul>
                            </div>
                            <div class="row">
                                <ul class="nav navbar-nav list-unstyled">
                                    <li class="{{Auth::guard('admin')->check() ? '' : 'ld ld-heartbeat'}}"
                                        id="apply" data-placement="top" data-toggle="tooltip">
                                        <button type="button" class="btn btn-danger btn-block"
                                                style="padding: 5px 25px;"
                                                {{Auth::guard('admin')->check() ? 'disabled' : ''}}>
                                            <i class="fa fa-paper-plane"></i>&ensp;<strong>APPLY</strong>
                                        </button>
                                    </li>
                                    <li>
                                        <form method="post" action="{{route('bookmark.vacancy')}}" id="form-bookmark">
                                            {{csrf_field()}}
                                            <div class="anim-icon anim-icon-md bookmark ld ld-breath"
                                                 style="margin: 0 10px">
                                                <input type="hidden" name="vacancy_id" value="{{$vacancy->id}}">
                                                <input type="checkbox" id="bookmark" {{$vacancy->isPost == false ?
                                            'disabled' : ''}}>
                                                <label for="bookmark" style="cursor: {{$vacancy->isPost == false ?
                                            'not-allowed' : 'pointer'}}" data-toggle="tooltip" id="bm"
                                                       title="{{$vacancy->isPost == true ? 'Bookmark this vacancy' : ''}}">
                                                </label>
                                            </div>
                                            <div class="anim-icon anim-icon-md info" style="margin: 0;">
                                                <input type="checkbox" id="info">
                                                <label for="info" style="cursor: help;" data-toggle="popover"
                                                       data-placement="top" title="FYI"></label>
                                            </div>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                            <div class="row">
                                <h4>Schedule</h4>
                                <table>
                                    <tr data-placement="left" data-toggle="tooltip" title="Recruitment Date">
                                        <td><i class="fa fa-users"></i></td>
                                        <td>&nbsp;</td>
                                        <td>
                                        <span>{{$vacancy->recruitmentDate_start && $vacancy->recruitmentDate_end != "" ?
                                        \Carbon\Carbon::parse($vacancy->recruitmentDate_start)
                                        ->formatLocalized('%d %b %Y'). " - ".\Carbon\Carbon::parse
                                        ($vacancy->recruitmentDate_end)->formatLocalized('%d %b %Y') : '-'}}</span>
                                        </td>
                                    </tr>
                                    <tr data-placement="left" data-toggle="tooltip" title="Job Interview Date">
                                        <td><i class="fa fa-comments"></i></td>
                                        <td>&nbsp;</td>
                                        <td>
                                        <span>{{$vacancy->interview_date != "" ? \Carbon\Carbon::parse
                                        ($vacancy->interview_date)->format('l, j F Y') : '-'}}</span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <div class="profile_title">
                                <div class="col-md-12">
                                    <h2>{{$agency->company}}'s address</h2>
                                </div>
                            </div>
                            <div id="map" style="width:100%; height:280px;"></div>
                            <div id="infowindow-content" style="display: none;">
                                <img src="{{$agency->ava == "" || $agency->ava == "agency.png" ?
                                 asset('images/agency.png') : asset('storage/admins/agencies/ava/'.$agency->ava)}}"
                                     width="32" height="32" id="place-icon">
                                <span id="place-name" class="title">{{$agency->company}}</span><br>
                                <span id="place-address">{{$agency->alamat}}</span>
                            </div>

                            <div role="tabpanel" data-example-id="togglable-tabs">
                                <ul class="nav nav-tabs bar_tabs" role="tablist">
                                    <li role="presentation" class="active">
                                        <a href="#requirements" id="requirements-tab" role="tab" data-toggle="tab"
                                           aria-expanded="true">Requirements</a>
                                    </li>
                                    <li role="presentation">
                                        <a href="#responsibilities" role="tab" id="responsibilities-tab"
                                           data-toggle="tab" aria-expanded="false">Responsibilities</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane fade active in" id="requirements"
                                         aria-labelledby="requirements-tab">
                                        <blockquote style="font-size: 13px"
                                                    data-scrollbar>{!! $vacancy->syarat !!}</blockquote>
                                    </div>
                                    <div role="tabpanel" class="tab-pane fade" id="responsibilities"
                                         aria-labelledby="responsibilities-tab">
                                        <blockquote style="font-size: 13px"
                                                    data-scrollbar>{!! $vacancy->syarat !!}</blockquote>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @auth
        <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="applyModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Hi {{Auth::user()->name}},</h4>
                    </div>
                    <div class="modal-body">
                        <p style="font-size: 17px" align="justify">
                            Complete data will make you a lot easier to apply for any jobs and the agency (HRD)
                            is interested with. You will register for this vacancy with the
                            following details:</p>
                        <hr class="hr-divider">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="media">
                                    <div class="media-left media-middle">
                                        <img width="100" class="media-object" src="{{$agency->ava == "" ||
                                        $agency->ava == "agency.png" ? asset('images/agency.png') :
                                        asset('storage/admins/agencies/ava/'.$agency->ava)}}">
                                    </div>
                                    <div class="media-body">
                                        <small class="media-heading" style="font-size: 17px;">
                                            <a href="{{route('detail.vacancy',['id'=>$vacancy->id])}}"
                                               style="color: #00ADB5">
                                                {{$vacancy->judul}}</a>
                                            <sub style="color: #fa5555;text-transform: none">&ndash; {{$vacancy->updated_at->diffForHumans()}}</sub>
                                        </small>
                                        <blockquote style="font-size: 15px;color: #7f7f7f">
                                            <ul class="list-inline">
                                                <li>
                                                    <a class="myTag">
                                                        <i class="fa fa-map-marked"></i>&ensp;
                                                        {{$city}}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="myTag">
                                                        <i class="fa fa-warehouse"></i>&ensp;
                                                        {{$jobfunc->name}}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="myTag">
                                                        <i class="fa fa-industry"></i>&ensp;
                                                        {{$industry->name}}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="myTag">
                                                        <i class="fa fa-money-bill-wave"></i>
                                                        &ensp;IDR {{$salary->name}}</a>
                                                </li>
                                                <li>
                                                    <a class="myTag">
                                                        <i class="fa fa-graduation-cap"></i>
                                                        &ensp;{{$degree->name}}</a>
                                                </li>
                                                <li>
                                                    <a class="myTag">
                                                        <i class="fa fa-user-graduate"></i>
                                                        &ensp;{{$major->name}}</a>
                                                </li>
                                                <li>
                                                    <a class="myTag">
                                                        <i class="fa fa-briefcase"></i>&ensp;At least
                                                        {{$vacancy->pengalaman > 1 ? $vacancy->pengalaman.
                                                        ' years' : $vacancy->pengalaman.' year'}}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="myTag myTag-plans">
                                                        <i class="fa fa-paper-plane"></i>&ensp;
                                                        <strong>{{$applicants}}</strong> applicants
                                                    </a>
                                                </li>
                                            </ul>
                                            <table style="font-size: 12px;margin-top: -.5em">
                                                <tr>
                                                    <td><i class="fa fa-users"></i></td>
                                                    <td>&nbsp;Recruitment Date</td>
                                                    <td>: {{$vacancy->recruitmentDate_start && $vacancy
                                                    ->recruitmentDate_end != "" ? \Carbon\Carbon::parse($vacancy
                                                    ->recruitmentDate_start)->format('j F Y')." - ".
                                                    \Carbon\Carbon::parse($vacancy->recruitmentDate_end)
                                                    ->format('j F Y') : '-'}}</td>
                                                </tr>
                                                <tr>
                                                    <td><i class="fa fa-comments"></i>
                                                    </td>
                                                    <td>&nbsp;Interview Date</td>
                                                    <td>: {{$vacancy->interview_date != "" ? \Carbon\Carbon::parse
                                                    ($vacancy->interview_date)->format('l, j F Y') : '-'}}</td>
                                                </tr>
                                            </table>
                                        </blockquote>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="card-read-more" id="btn-apply">
                            <form method="post" action="{{route('apply.vacancy')}}" id="form-apply">
                                {{csrf_field()}}
                                <input type="hidden" name="vacancy_id" value="{{$vacancy->id}}">
                                <button class="btn btn-link btn-block" type="button">
                                    <i class="fa fa-paper-plane"></i>&ensp;APPLY
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="resumeModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Resume Incomplete</h4>
                    </div>
                    <div class="modal-body" style="font-size: 15px">
                        Required data to be completed before applying:
                        <ol>
                            <li>Your personal data (gender, phone number, address, and date of birth)</li>
                            <li>Education or work experience (at least one)</li>
                        </ol>
                    </div>
                    <div class="modal-footer">
                        <div class="card-read-more">
                            <a class="btn btn-link btn-block" href="{{route('seeker.edit.profile')}}">
                                <i class="fa fa-user"></i>&ensp;Go to resume</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endauth
@endsection
@push("scripts")
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBIljHbKjgtTrpZhEiHum734tF1tolxI68"></script>
    <script>
        var google;

        function init() {
            var myLatlng = new google.maps.LatLng('{{$agency->lat}}', '{{$agency->long}}');

            var mapOptions = {
                zoom: 16,
                center: myLatlng,
                scrollwheel: true,
                styles: [
                    {
                        "featureType": "administrative.land_parcel",
                        "elementType": "all",
                        "stylers": [{"visibility": "on"}]
                    }, {
                        "featureType": "landscape.man_made",
                        "elementType": "all",
                        "stylers": [{"visibility": "on"}]
                    }, {"featureType": "poi", "elementType": "labels", "stylers": [{"visibility": "on"}]}, {
                        "featureType": "road",
                        "elementType": "labels",
                        "stylers": [{"visibility": "simplified"}, {"lightness": 20}]
                    }, {
                        "featureType": "road.highway",
                        "elementType": "geometry",
                        "stylers": [{"hue": "#f49935"}]
                    }, {
                        "featureType": "road.highway",
                        "elementType": "labels",
                        "stylers": [{"visibility": "simplified"}]
                    }, {
                        "featureType": "road.arterial",
                        "elementType": "geometry",
                        "stylers": [{"hue": "#fad959"}]
                    }, {
                        "featureType": "road.arterial",
                        "elementType": "labels",
                        "stylers": [{"visibility": "on"}]
                    }, {
                        "featureType": "road.local",
                        "elementType": "geometry",
                        "stylers": [{"visibility": "simplified"}]
                    }, {
                        "featureType": "road.local",
                        "elementType": "labels",
                        "stylers": [{"visibility": "simplified"}]
                    }, {
                        "featureType": "transit",
                        "elementType": "all",
                        "stylers": [{"visibility": "on"}]
                    }, {
                        "featureType": "water",
                        "elementType": "all",
                        "stylers": [{"hue": "#a1cdfc"}, {"saturation": 30}, {"lightness": 49}]
                    }]
            };

            var mapElement = document.getElementById('map');

            var map = new google.maps.Map(mapElement, mapOptions);

            var infowindow = new google.maps.InfoWindow();
            var infowindowContent = document.getElementById('infowindow-content');
            infowindow.setContent(infowindowContent);

            var marker = new google.maps.Marker({
                position: myLatlng,
                map: map,
                anchorPoint: new google.maps.Point(0, -29),
                icon: '{{asset('images/pin-agency.png')}}'
            });

            marker.addListener('click', function () {
                $("#infowindow-content").show();
                infowindow.open(map, marker);
            });

            google.maps.event.addListener(map, 'click', function () {
                $("#infowindow-content").hide();
                infowindow.close();
            });
        }

        google.maps.event.addDomListener(window, 'load', init);

        // apply validation
        var $btnApply = $("#apply button"), $btnBookmark = $("#bm"),
            startDate = '{{$vacancy->recruitmentDate_start}}', endDate = '{{$vacancy->recruitmentDate_end}}',
            $content = '', $style = 'none', $class = '', $attr = false,
            now = new Date(), day = ("0" + now.getDate()).slice(-2), month = ("0" + (now.getMonth() + 1)).slice(-2),
            today = now.getFullYear() + "-" + (month) + "-" + (day);

        @if($vacancy->isPost == false)
            $content = 'This vacancy is INACTIVE.';
        $style = 'inline-block';
        $class = '';
        @else
        if (today < startDate || startDate == "") {
            $content = 'The recruitment date of this vacancy hasn\'t started yet.';
            $style = 'inline-block';
            $class = '';
            $attr = true;
        } else if (today > endDate || endDate == "") {
            $content = 'The recruitment date of this vacancy has been ended.';
            $style = 'inline-block';
            $class = '';
            $attr = true;
        } else {
            $content = '';
            $class = 'ld ld-heartbeat';
            $attr = false;
        }
        @endif
        $(".info").css('display', $style);
        $(".info label").data('content', $content);
        $("#apply").addClass($class);
        $btnApply.attr('disabled', $attr);
        @auth
        @php $acc = App\Models\Applications::where('user_id',Auth::user()->id)->where('vacancy_id',$vacancy->id);@endphp
        @if(count($acc->get()))
        @if($acc->first()->isBookmark == true)
        $("#bookmark").prop('checked', true);
        $btnBookmark.attr('title', 'Unmark this vacancy').tooltip('show').parent().removeClass('ld ld-breath');
        @endif
        @if($acc->first()->isApply == true)
        $("#apply").removeClass('ld ld-heartbeat').attr('title', 'Please, check Application Status ' +
            'in your Dashboard.');
        $btnApply.toggleClass('btn-danger btn-dark').attr('disabled', true).html('<i class="fa fa-paper-plane">' +
            '</i>&ensp;<strong>APPLIED</strong>');
        @endif
        @endif
        @endauth
        $("#bookmark").on("click", function () {
            $btnBookmark.parent().toggleClass('ld ld-breath');
            @auth
            $("#form-bookmark")[0].submit();
            @else
            $(this).prop('checked', false);
            swal({
                title: 'ATTENTION!',
                text: 'This feature only works when you\'re signed in as a Job Seeker.',
                type: 'warning',
                timer: '3500'
            });
            @endauth
        });
        $btnApply.on("click", function () {
            @auth
            $("#applyModal").modal('show');
            $("#btn-apply button").on("click", function () {
                $.get("{{route('get.vacancy.requirement',['id' => $vacancy->id])}}", function (data) {
                    if (data == 0) {
                        $("#resumeModal").modal('show');

                    } else if (data == 1) {
                        swal({
                            title: 'Work Experience Unqualified',
                            text: 'It seems that your work experience for {{Auth::user()->total_exp}} year(s) isn\'t ' +
                                'sufficient for this vacancy.',
                            type: 'warning',
                            timer: '7000'
                        });

                    } else if (data == 2) {
                        swal({
                            title: 'Education Degree Unqualified',
                            text: 'There seems to be none of your education history that has qualified for this vacancy.',
                            type: 'warning',
                            timer: '7000'
                        });

                    } else if (data == 3) {
                        $("#applyModal").modal('hide');
                        $("#apply").toggleClass('ld ld-heartbeat');
                        $btnApply.toggleClass('btn-danger btn-dark').attr('disabled', true)
                            .html('<i class="fa fa-paper-plane"></i>&ensp;<strong>APPLIED</strong>');
                        $('#form-apply')[0].submit();

                    } else if (data == 4) {
                        swal({
                            title: 'ATTENTION!',
                            text: "This vacancy is belong to SISKA! If you still wanna apply this, please go to the SISKA main site.",
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#00ADB5',
                            confirmButtonText: 'Yes, redirect me to SISKA main site.',
                            showLoaderOnConfirm: true,

                            preConfirm: function () {
                                return new Promise(function (resolve) {
                                    window.location.href = '{{env('SISKA_URI')}}/search?q={{$vacancy->judul}}&loc={{$city}}';
                                });
                            },
                            allowOutsideClick: false
                        });
                        return false;
                    }
                });
            });
            @endauth
        });

        $(document).on('show.bs.modal', '.modal', function (event) {
            var zIndex = 1040 + (10 * $('.modal:visible').length);
            $(this).css('z-index', zIndex);
            setTimeout(function () {
                $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
            }, 0);
        });
    </script>
@endpush
