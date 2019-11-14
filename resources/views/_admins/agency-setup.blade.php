@extends('layouts.mst')
@section('title', 'Agency List &ndash; '.env("APP_NAME").' Admins | SISKA &mdash; Sistem Informasi Karier')
@push("styles")
    <style>
        #map {
            height: 410px;
        }

        #infowindow-content .title {
            font-weight: bold;
        }

        #infowindow-content {
            display: none;
        }

        #map #infowindow-content {
            display: inline;
        }
    </style>
@endpush
@section('content')
    <div class="right_col" role="main">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2 id="panel_title">Agency
                            <small>List</small>
                        </h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="btn_agency" data-toggle="tooltip" title="Create"
                                   data-placement="right"><i class="fa fa-plus"></i></a></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div id="content1" class="x_content">
                        <table id="datatable-buttons" class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Ava</th>
                                <th>Contact</th>
                                <th>Work Days</th>
                                <th>Work Hours</th>
                                <th>Last Update</th>
                                <th>Action</th>
                            </tr>
                            </thead>

                            <tbody>
                            @php $no = 1; @endphp
                            @foreach($agencies as $agency)
                                <tr>
                                    <td style="vertical-align: middle" align="center">{{$no++}}</td>
                                    <td style="vertical-align: middle" align="center">
                                        <a target="_blank" href="{{route('agency.profile',['id' => $agency->id])}}">
                                            <img class="img-responsive" width="100" alt="agency.png"
                                                 src="{{$agency->ava == "" || $agency->ava == "agency.png" ?
                                                 asset('images/agency.png') : asset('storage/admins/agencies/ava/'.
                                                 $agency->ava)}}">
                                        </a>
                                    </td>
                                    <td style="vertical-align: middle">
                                        <a target="_blank" href="{{route('agency.profile',['id' => $agency->id])}}">
                                            <strong>{{$agency->company}}</strong></a> &ndash; <strong>{{$agency
                                            ->getIndustry->name}}</strong><br>
                                        <a href="mailto:{{$agency->email}}">{{$agency->email}}</a><br>
                                        <strong>Headquarter : </strong>
                                        <span class="label label-default"
                                              style="text-transform: uppercase;background: #00adb5">{{$agency->kantor_pusat}}</span>
                                        <hr style="margin: 5px auto">
                                        <a href="{{$agency->link}}" target="_blank">{{$agency->link}}</a><br>
                                        <a href="tel:{{$agency->phone}}">{{$agency->phone}}</a><br>{{$agency->alamat}}
                                    </td>
                                    <td style="vertical-align: middle" align="center">{{$agency->hari_kerja}}</td>
                                    <td style="vertical-align: middle" align="center">{{$agency->jam_kerja}}</td>
                                    <td style="vertical-align: middle"
                                        align="center">{{$agency->updated_at->diffForHumans()}}</td>
                                    <td style="vertical-align: middle" align="center">
                                        <a class="btn btn-warning btn-sm" style="font-size: 16px" data-toggle="tooltip"
                                           title="Edit" onclick="editAgency('{{$agency->id}}')">
                                            <i class="fa fa-edit"></i></a>
                                        <hr style="margin: 5px auto">
                                        <a href="{{route('delete.agencies',['id'=>encrypt($agency->id)])}}"
                                           class="btn btn-danger btn-sm delete-data" style="font-size: 16px"
                                           data-toggle="tooltip"
                                           title="Delete" data-placement="left"><i class="fa fa-trash-alt"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div id="content2" class="x_content" style="display: none">
                        <form method="post" action="{{route('create.agencies')}}" id="form-agency" enctype="multipart/form-data">
                            {{csrf_field()}}
                            <input type="hidden" name="_method" id="method">
                            <div class="row form-group">
                                <img id="agency_ava_img" class="img-responsive"
                                     style="margin: 0 auto;width: 25%;cursor: pointer" data-toggle="tooltip"
                                     data-placement="bottom"
                                     title="Allowed extension: jpg, jpeg, gif, png. Allowed size: < 2 MB">
                                <hr id="agency_divider" style="margin: .5em auto">
                                <div class="col-lg-12">
                                    <label for="agency_ava_file">Avatar</label>
                                    <input id="agency_ava_file" type="file" name="ava" style="display: none;"
                                           accept="image/*">
                                    <div class="input-group">
                                        <input type="text" id="agency_ava_input" class="browse_files form-control"
                                               placeholder="Upload file here..." readonly style="cursor: pointer"
                                               data-toggle="tooltip"
                                               title="Allowed extension: jpg, jpeg, gif, png. Allowed size: < 2 MB">
                                        <span class="input-group-btn">
                                        <button id="agency_ava_btn" class="browse_files btn btn-info" type="button">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </span>
                                    </div>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-lg-6">
                                    <label for="company">Agency <span class="required">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-user-tie"></i></span>
                                        <input type="text" id="company" class="form-control" name="company"
                                               placeholder="Agency/company name" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <label for="industry_id">Industry <span class="required">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-industry"></i></span>
                                        <select id="industry_id" class="form-control selectpicker" name="industry_id"
                                                data-live-search="true" title="-- Select Industry --" required>
                                            @foreach(\App\Models\Industries::all() as $industry)
                                                <option value="{{$industry->id}}">{{$industry->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div id="map"></div>
                                    <div id="infowindow-content">
                                        <img src="{{asset('images/unesa.jpg')}}" width="32" height="32" id="place-icon">
                                        <span id="place-name" class="title">JTIF UNESA</span><br>
                                        <span id="place-address">Kampus Universitas Negeri Surabaya, Jl. Ketintang,
                                            Ketintang, Gayungan, Kota SBY, Jawa Timur 60231</span>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="row form-group">
                                        <div class="col-lg-12">
                                            <label for="agency_email">Email <span class="required">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                                <input type="email" id="agency_email" class="form-control" name="email"
                                                       placeholder="Agency email" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-lg-12">
                                            <label for="phone">Phone <span class="required">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                                                <input type="text" id="phone" class="form-control" name="phone"
                                                       placeholder="Agency/company phone number"
                                                       onkeypress="return numberOnly(event, false)" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-lg-12">
                                            <label for="address_map">Address <span class="required">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i
                                                            class="fa fa-map-marker-alt"></i></span>
                                                <input type="text" id="address_map" class="form-control" name="address"
                                                       placeholder="Agency/company address" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-lg-12">
                                            <label for="kantor_pusat">Headquarter <span
                                                        class="required">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-building"></i></span>
                                                <input type="text" id="kantor_pusat" class="form-control"
                                                       name="kantor_pusat"
                                                       placeholder="Agency/company headquarter" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-lg-12">
                                            <label for="link">Website <span class="required">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-globe"></i></span>
                                                <input type="text" id="link" class="form-control" name="link"
                                                       placeholder="Agency/company website" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-lg-6">
                                    <label for="start_day">Start Day <span class="required">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-calendar-alt"></i></span>
                                        <select id="start_day" class="form-control selectpicker" name="start_day"
                                                title="-- Start Day --" required>
                                            <option value="Monday">Monday</option>
                                            <option value="Tuesday">Tuesday</option>
                                            <option value="Wednesday">Wednesday</option>
                                            <option value="Thursday">Thursday</option>
                                            <option value="Friday">Friday</option>
                                            <option value="Saturday">Saturday</option>
                                            <option value="Sunday">Sunday</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <label for="end_day">End Day <span class="required">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-calendar-check"></i></span>
                                        <select id="end_day" class="form-control selectpicker" name="end_day"
                                                title="-- End Day --" required>
                                            <option value="Monday">Monday</option>
                                            <option value="Tuesday">Tuesday</option>
                                            <option value="Wednesday">Wednesday</option>
                                            <option value="Thursday">Thursday</option>
                                            <option value="Friday">Friday</option>
                                            <option value="Saturday">Saturday</option>
                                            <option value="Sunday">Sunday</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-lg-6">
                                    <label for="start_time">Start Time <span class="required">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-hourglass-start"></i></span>
                                        <input id="start_time" class="form-control timepicker" name="start_time"
                                               type="text" placeholder="08:00" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <label for="end_time">End Time <span class="required">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-hourglass-end"></i></span>
                                        <input id="end_time" class="form-control timepicker" name="end_time"
                                               type="text" placeholder="08:00" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-lg-12">
                                    <label for="tentang">About <span class="required">*</span></label>
                                    <textarea id="tentang" class="use-tinymce" name="tentang"></textarea>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-lg-12">
                                    <label for="alasan">Why Choose Us? <span class="required">*</span></label>
                                    <textarea id="alasan" class="use-tinymce" name="alasan"></textarea>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-lg-12">
                                    <button id="btn_submit" type="submit" class="btn btn-block btn-primary">Submit
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push("scripts")
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBIljHbKjgtTrpZhEiHum734tF1tolxI68&libraries=places"></script>
    <script>
        var google;

        function init(lat, long) {
            var myLatlng = new google.maps.LatLng(lat, long);
            var mapOptions = {
                zoom: 15,
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

            var marker = new google.maps.Marker({
                map: map,
                icon: '{{asset('images/pin-agency.png')}}',
                position: myLatlng,
                anchorPoint: new google.maps.Point(0, -29)
            });

            var infowindow = new google.maps.InfoWindow();
            var infowindowContent = document.getElementById('infowindow-content');
            infowindow.setContent(infowindowContent);

            marker.addListener('click', function () {
                infowindow.open(map, marker);
            });

            google.maps.event.addListener(map, 'click', function () {
                infowindow.close();
            });

            var autocomplete = new google.maps.places.Autocomplete(document.getElementById('address_map'));

            autocomplete.bindTo('bounds', map);

            autocomplete.setFields(['address_components', 'geometry', 'icon', 'name']);

            autocomplete.addListener('place_changed', function () {
                infowindow.close();
                marker.setVisible(false);

                var place = autocomplete.getPlace();
                if (!place.geometry) {
                    window.alert("No details available for input: '" + place.name + "'");
                    return;
                }

                if (place.geometry.viewport) {
                    map.fitBounds(place.geometry.viewport);
                } else {
                    map.setCenter(place.geometry.location);
                    map.setZoom(17);
                }
                marker.setPosition(place.geometry.location);
                marker.setVisible(true);

                var address = '';
                if (place.address_components) {
                    address = [
                        (place.address_components[0] && place.address_components[0].short_name || ''),
                        (place.address_components[1] && place.address_components[1].short_name || ''),
                        (place.address_components[2] && place.address_components[2].short_name || '')
                    ].join(' ');
                }

                infowindowContent.children['place-icon'].src = place.icon;
                infowindowContent.children['place-name'].textContent = place.name;
                infowindowContent.children['place-address'].textContent = address;
                infowindow.open(map, marker);

                marker.addListener('click', function () {
                    infowindow.open(map, marker);
                });

                google.maps.event.addListener(map, 'click', function () {
                    infowindow.close();
                });
            });
        }

        $(".btn_agency").on("click", function () {
            init(-7.317174, 112.725614);

            $("#content1").toggle(300);
            $("#content2").toggle(300);
            $(".btn_agency i").toggleClass('fa-plus fa-th-list');

            $(".btn_agency[data-toggle=tooltip]").attr('data-original-title', function (i, v) {
                return v === "Create" ? "View" : "Create";
            }).tooltip('show');

            $("#panel_title").html(function (i, v) {
                return v === "Agency Setup<small>Form</small>" ? "Agency<small>List</small>" : "Agency Setup<small>Form</small>";
            });

            $("#agency_ava_img, #agency_divider").hide();
            $('#industry_id, #start_day, #end_day').val('default').selectpicker("refresh");
            tinyMCE.get('tentang').setContent('');
            tinyMCE.get('alasan').setContent('');

            $("#method").val('');
            $("#form-agency").attr('action', '{{route('create.agencies')}}')[0].reset();
            $("#btn_submit").html("<strong>SUBMIT</strong>");
        });

        function editAgency(id) {
            $("#content1").toggle(300);
            $("#content2").toggle(300);
            $(".btn_agency i").toggleClass('fa-plus fa-th-list');

            $(".btn_agency[data-toggle=tooltip]").attr('data-original-title', function (i, v) {
                return v === "Create" ? "View" : "Create";
            }).tooltip('show');

            $("#panel_title").html(function (i, v) {
                return v === "Agency Edit<small>Form</small>" ? "Agency<small>List</small>" : "Agency Edit<small>Form</small>";
            });
            $("#agency_ava_img, #agency_divider").show();

            $.get("{{route('edit.agencies',['id' => ''])}}/" + id, function (data) {
                var $ava, day = data.hari_kerja, start_day = '', end_day = '', time = data.jam_kerja,
                    start_time = time != "" ? time.substr(0, 5) : '', end_time = time != "" ? time.substr(8, 5) : '';

                if (data.ava == "" || data.ava == "agency.png") {
                    $ava = '{{asset('images/agency.png')}}';
                } else {
                    $ava = '{{asset('storage/admins/agencies/ava')}}/' + data.ava;
                }

                if (day != "") {
                    if (day.substr(0, 3) == 'Mon') {
                        start_day = 'Monday';
                    } else if (day.substr(0, 3) == 'Tue') {
                        start_day = 'Tuesday';
                    } else if (day.substr(0, 3) == 'Wed') {
                        start_day = 'Wednesday';
                    } else if (day.substr(0, 3) == 'Thu') {
                        start_day = 'Thursday';
                    } else if (day.substr(0, 3) == 'Fri') {
                        start_day = 'Friday';
                    } else if (day.substr(0, 3) == 'Sat') {
                        start_day = 'Saturday';
                    } else if (day.substr(0, 3) == 'Sun') {
                        start_day = 'Sunday';
                    }

                    if ((day.substr(9, 3) == 'Mon' && (day.substr(0, 3) == 'Mon' || day.substr(0, 3) == 'Fri' ||
                        day.substr(0, 3) == 'Sun')) || (day.substr(11, 3) == 'Mon' && (day.substr(0, 3) == 'Thu' ||
                        day.substr(0, 3) == 'Sat')) || (day.substr(12, 3) == 'Mon' && day.substr(0, 3) == 'Wed') ||
                        (day.substr(10, 3) == 'Mon' && day.substr(0, 3) == 'Tue')) {
                        end_day = 'Monday';

                    } else if ((day.substr(9, 3) == 'Tue' && (day.substr(0, 3) == 'Mon' || day.substr(0, 3) == 'Fri' ||
                        day.substr(0, 3) == 'Sun')) || (day.substr(11, 3) == 'Tue' && (day.substr(0, 3) == 'Thu' ||
                        day.substr(0, 3) == 'Sat')) || (day.substr(12, 3) == 'Tue' && day.substr(0, 3) == 'Wed') ||
                        (day.substr(10, 3) == 'Tue' && day.substr(0, 3) == 'Tue')) {
                        end_day = 'Tuesday';

                    } else if ((day.substr(9, 3) == 'Wed' && (day.substr(0, 3) == 'Mon' || day.substr(0, 3) == 'Fri' ||
                        day.substr(0, 3) == 'Sun')) || (day.substr(11, 3) == 'Wed' && (day.substr(0, 3) == 'Thu' ||
                        day.substr(0, 3) == 'Sat')) || (day.substr(12, 3) == 'Wed' && day.substr(0, 3) == 'Wed') ||
                        (day.substr(10, 3) == 'Wed' && day.substr(0, 3) == 'Tue')) {
                        end_day = 'Wednesday';

                    } else if ((day.substr(9, 3) == 'Thu' && (day.substr(0, 3) == 'Mon' || day.substr(0, 3) == 'Fri' ||
                        day.substr(0, 3) == 'Sun')) || (day.substr(11, 3) == 'Thu' && (day.substr(0, 3) == 'Thu' ||
                        day.substr(0, 3) == 'Sat')) || (day.substr(12, 3) == 'Thu' && day.substr(0, 3) == 'Wed') ||
                        (day.substr(10, 3) == 'Thu' && day.substr(0, 3) == 'Tue')) {
                        end_day = 'Thursday';

                    } else if ((day.substr(9, 3) == 'Fri' && (day.substr(0, 3) == 'Mon' || day.substr(0, 3) == 'Fri' ||
                        day.substr(0, 3) == 'Sun')) || (day.substr(11, 3) == 'Fri' && (day.substr(0, 3) == 'Thu' ||
                        day.substr(0, 3) == 'Sat')) || (day.substr(12, 3) == 'Fri' && day.substr(0, 3) == 'Wed') ||
                        (day.substr(10, 3) == 'Fri' && day.substr(0, 3) == 'Tue')) {
                        end_day = 'Friday';

                    } else if ((day.substr(9, 3) == 'Sat' && (day.substr(0, 3) == 'Mon' || day.substr(0, 3) == 'Fri' ||
                        day.substr(0, 3) == 'Sun')) || (day.substr(11, 3) == 'Sat' && (day.substr(0, 3) == 'Thu' ||
                        day.substr(0, 3) == 'Sat')) || (day.substr(12, 3) == 'Sat' && day.substr(0, 3) == 'Wed') ||
                        (day.substr(10, 3) == 'Sat' && day.substr(0, 3) == 'Tue')) {
                        end_day = 'Saturday';

                    } else if ((day.substr(9, 3) == 'Sun' && (day.substr(0, 3) == 'Mon' || day.substr(0, 3) == 'Fri' ||
                        day.substr(0, 3) == 'Sun')) || (day.substr(11, 3) == 'Sun' && (day.substr(0, 3) == 'Thu' ||
                        day.substr(0, 3) == 'Sat')) || (day.substr(12, 3) == 'Sun' && day.substr(0, 3) == 'Wed') ||
                        (day.substr(10, 3) == 'Sun' && day.substr(0, 3) == 'Tue')) {
                        end_day = 'Sunday';
                    }
                }

                $("#agency_ava_img").attr("src", $ava);
                $("#agency_ava_input").val(data.ava);
                $("#company").val(data.company);
                $('#industry_id').val(data.industry_id).selectpicker("refresh");
                $("#agency_email").val(data.email);
                $("#phone").val(data.phone);
                $("#address_map").val(data.alamat);
                $("#kantor_pusat").val(data.kantor_pusat);
                $("#link").val(data.link);
                $("#start_day").val(start_day).selectpicker("refresh");
                $("#end_day").val(end_day).selectpicker("refresh");
                $("#start_time").val(start_time);
                $("#end_time").val(end_time);
                tinyMCE.get('tentang').setContent(data.tentang);
                tinyMCE.get('alasan').setContent(data.alasan);

                init(data.lat, data.long);
                $("#place-icon").attr('src', '{{asset('images/agency.png')}}');
                $("#place-name").text(data.company);
                $("#place-address").text(data.alamat);
            });

            $("#method").val('PUT');
            $("#form-agency").attr('action', '{{url('admin/agencies')}}/' + id + '/update');
            $("#btn_submit").html("<strong>SAVE CHANGES</strong>");
        }

        $("#agency_ava_input, #agency_ava_img, #agency_ava_btn").on('click', function () {
            $("#agency_ava_file").trigger('click');
        });

        $("#agency_ava_file").on('change', function () {
            var files = $(this).prop("files");
            var names = $.map(files, function (val) {
                return val.name;
            });
            var txt = $("#agency_ava_input");
            txt.val(names);
            $("#agency_ava_input[data-toggle=tooltip]").attr('data-original-title', names).tooltip('show');
        });

        $("#link").on("blur", function () {
            var $uri = $(this).val().substr(0, 4) != 'http' ? 'http://' + $(this).val() : $(this).val();
            $(this).val($uri);
        });

        $("#form-agency").on("submit", function (e) {
            e.preventDefault();
            if (tinyMCE.get('tentang').getContent() == "") {
                swal({
                    title: 'ATTENTION!',
                    text: 'About field can\'t be null!',
                    type: 'warning',
                    timer: '3500'
                });

            } else if (tinyMCE.get('alasan').getContent() == "") {
                swal({
                    title: 'ATTENTION!',
                    text: 'Why Choose Us? field can\'t be null!',
                    type: 'warning',
                    timer: '3500'
                });

            } else {
                $('#form-agency')[0].submit();
            }
        })
    </script>
@endpush