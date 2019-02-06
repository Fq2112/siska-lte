@extends('layouts.mst')
@section('title', ''.$agency->company.'\'s Details &ndash; '.env("APP_NAME").' | SISKA &mdash; Sistem Informasi Karier')
@push("styles")
    <link href="{{ asset('css/mySearchFilter.css') }}" rel="stylesheet">
    <style>
        [data-scrollbar] {
            max-height: 300px;
        }
    </style>
@endpush
@section('content')
    <div class="right_col" role="main">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>{{$agency->company}}
                            <small>Agency Details</small>
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
                            <div class="profile_img">
                                <div id="crop-avatar">
                                    <a href="javascript:void(0)" onclick="showAva()">
                                        <img class="img-responsive avatar-view" src="{{$agency->ava == "" ||
                                        $agency->ava == "agency.png" ? asset('images/agency.png') :
                                        asset('storage/admins/agencies/ava/'.$agency->ava)}}">
                                    </a>
                                </div>
                            </div>
                            <h3>{{$agency->company}}</h3>

                            <ul class="list-unstyled user_data">
                                <li data-placement="left" data-toggle="tooltip" title="Headquarter">
                                    <i class="fa fa-building"></i>&ensp;{{$agency->kantor_pusat}}</li>
                                <li data-placement="left" data-toggle="tooltip" title="Industry">
                                    <i class="fa fa-industry"></i>&ensp;{{$industry->name}}</li>
                                <li data-placement="left" data-toggle="tooltip" title="Working Days">
                                    <i class="fa fa-calendar"></i>&ensp;{{$agency->hari_kerja}}</li>
                                <li data-placement="left" data-toggle="tooltip" title="Working Hours">
                                    <i class="fa fa-clock"></i>&ensp;{{$agency->jam_kerja}}</li>
                                <li data-placement="left" data-toggle="tooltip" title="Website">
                                    <i class="fa fa-globe"></i>&ensp;<a href="{{$agency->link}}" target="_blank">
                                        {{$agency->link}}</a></li>
                            </ul>
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
                                        <a href="#about" role="tab" id="about-tab"
                                           data-toggle="tab" aria-expanded="false">About {{$agency->company}}</a>
                                    </li>
                                    <li role="presentation">
                                        <a href="#vac" role="tab" id="vac-tab"
                                           data-toggle="tab" aria-expanded="false">Vacancies in {{$agency->company}}</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane fade active in" id="about"
                                         aria-labelledby="about-tab">
                                        <blockquote style="font-size: 13px" data-scrollbar>
                                            {!! $agency->tentang !!}
                                            <small style="font-size: 14px;text-transform: uppercase">Why choose us?
                                            </small>
                                            {!! $agency->alasan !!}
                                        </blockquote>
                                    </div>
                                    <div role="tabpanel" class="tab-pane fade" id="vac"
                                         aria-labelledby="vac-tab">
                                        <div data-scrollbar>
                                            @foreach(\App\Models\Vacancies::where('agency_id',$agency->id)
                                                    ->where('isPost',true)->orderByDesc('updated_at')->get() as $row)
                                                @php
                                                    $city = substr($row->getCity->name, 0, 2)=="Ko" ?
                                                    substr($row->getCity->name,5) : substr($row->getCity->name,10);
                                                    $salary = $row->getSalary;
                                                    $jobfunc = $row->getJobFunction;
                                                    $joblevel = $row->getJobLevel;
                                                    $jobtype = $row->getJobType;
                                                    $industry = $row->getIndustry;
                                                    $degree = $row->getDegree;
                                                    $major = $row->getMajor;
                                                    $app = \App\Models\Applications::where('vacancy_id', $row->id)
                                                    ->where('isApply', true)->count();
                                                @endphp
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="media">
                                                            <div class="media-left media-middle">
                                                                <img width="100" class="media-object"
                                                                     src="{{$agency->ava == "" || $agency->ava ==
                                                                 "agency.png" ? asset('images/agency.png') :
                                                                 asset('storage/admins/agencies/ava/'.$agency->ava)}}">
                                                            </div>
                                                            <div class="media-body">
                                                                <strong class="media-heading">
                                                                    <a href="{{route('detail.vacancy',['id'=>$row->id])}}">
                                                                        {{$row->judul}}</a>
                                                                    <sub style="color: #fa5555;text-transform: none">&ndash; {{$row->updated_at->diffForHumans()}}
                                                                    </sub>
                                                                </strong>
                                                                <blockquote style="font-size: 12px;color: #7f7f7f">
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
                                                                                <i class="fa fa-briefcase"></i>&ensp;At
                                                                                least
                                                                                {{$row->pengalaman > 1 ? $row->pengalaman.
                                                                                ' years' : $row->pengalaman.' year'}}
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <a class="myTag myTag-plans">
                                                                                <i class="fa fa-paper-plane"></i>&ensp;
                                                                                <strong>{{$app}}</strong> applicants
                                                                            </a>
                                                                        </li>
                                                                    </ul>
                                                                    <table style="font-size: 12px;margin-top: -.5em">
                                                                        <tr>
                                                                            <td><i class="fa fa-users"></i></td>
                                                                            <td>&nbsp;Recruitment Date</td>
                                                                            <td>:
                                                                                {{$row->recruitmentDate_start &&
                                                                                $row->recruitmentDate_end != "" ?
                                                                                \Carbon\Carbon::parse
                                                                                ($row->recruitmentDate_start)
                                                                                ->format('j F Y')." - ".
                                                                                \Carbon\Carbon::parse
                                                                                ($row->recruitmentDate_end)
                                                                                ->format('j F Y') : '-'}}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><i class="fa fa-comments"></i>
                                                                            </td>
                                                                            <td>&nbsp;Job Interview Date</td>
                                                                            <td>:
                                                                                {{$row->interview_date != "" ?
                                                                                \Carbon\Carbon::parse
                                                                                ($row->interview_date)
                                                                                ->format('l, j F Y') : '-'}}
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </blockquote>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr class="hr-divider">
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="avaModal">
        <div class="modal-dialog modal-lg">
            <img class="img-responsive" src="{{$agency->ava == "" || $agency->ava == "agency.png" ?
            asset('images/agency.png') : asset('storage/admins/agencies/ava/'.$agency->ava)}}">
        </div>
    </div>
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

        function showAva() {
            $("#avaModal").modal('show');
        }
    </script>
@endpush
