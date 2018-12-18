@extends('layouts.mst')
@section('title', 'Search Vacancy &ndash; '.env("APP_NAME").' | SISKA &mdash; Sistem Informasi Karier')
@push("styles")

@endpush
@section('content')
    <div class="right_col" role="main">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>{{$vacancy->judul}}
                            <small>{{$agency->company}}</small>
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
                                    <img class="img-responsive avatar-view" src="{{$agency->ava == "" || $agency->ava ==
                                     "agency.png" ? asset('images/agency.png') :
                                     asset('storage/admins/agencies/ava/'.$agency->ava)}}">
                                </div>
                            </div>
                            <h3>{{$vacancy->judul}}</h3>

                            <ul class="list-unstyled user_data">
                                <li data-toggle="tooltip" data-placement="left" title="Agency Address">
                                    <i class="fa fa-map-marker-alt user-profile-icon"></i> {{$agency->alamat}}</li>
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

                            <a class="btn btn-danger"><i class="fa fa-paper-plane m-right-xs"></i>
                                <strong>APPLY</strong></a><br>

                            <h4>Schedule</h4>
                            <table>
                                <tr data-placement="left" data-toggle="tooltip" title="Recruitment Date">
                                    <td><i class="fa fa-users"></i></td>
                                    <td>&nbsp;</td>
                                    <td>
                                        <span>{{$vacancy->recruitmentDate_start && $vacancy->recruitmentDate_end != "" ?
                                        \Carbon\Carbon::parse($vacancy->recruitmentDate_start)->format('j F Y').
                                        " - ".\Carbon\Carbon::parse($vacancy->recruitmentDate_end)
                                        ->format('j F Y') : '-'}}</span>
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
                                        <blockquote data-scrollbar>{!! $vacancy->syarat !!}</blockquote>
                                    </div>
                                    <div role="tabpanel" class="tab-pane fade" id="responsibilities"
                                         aria-labelledby="responsibilities-tab">
                                        <blockquote data-scrollbar>{!! $vacancy->syarat !!}</blockquote>
                                    </div>
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
    </script>
@endpush
