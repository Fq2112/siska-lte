@extends('layouts.mst')
@section('title', 'Job Applications Table &ndash; '.env("APP_NAME").' Admins | SISKA &mdash; Sistem Informasi Karier')
@section('content')
    <div class="right_col" role="main">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Job Applications
                            <small>Table</small>
                        </h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link" data-toggle="tooltip" title="Minimize" data-placement="left">
                                    <i class="fa fa-chevron-up"></i></a></li>
                            <li><a class="close-link" data-toggle="tooltip" title="Close" data-placement="right">
                                    <i class="fa fa-times"></i></a></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="row form-group">
                            <div class="col-lg-12 has-feedback">
                                <label for="vacancy_id">Vacancy Filter</label>
                                <select id="vacancy_id" class="form-control selectpicker" title="-- Select Vacancy --"
                                        data-live-search="true" data-max-options="1" multiple>
                                    @foreach($vacancies as $vacancy)
                                        <option value="{{$vacancy->judul}}">{{$vacancy->judul}}</option>
                                    @endforeach
                                </select>
                                <span class="fa fa-briefcase form-control-feedback right" aria-hidden="true"></span>
                            </div>
                        </div>
                        <table id="myDataTable" class="table table-striped table-bordered bulk_action">
                            <thead>
                            <tr>
                                <th><input type="checkbox" id="check-all" class="flat"></th>
                                <th>ID</th>
                                <th>Details</th>
                                <th>Status</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($applications as $application)
                                @php
                                    $user = \App\Models\User::find($application->user_id);
                                    $last_edu = \App\Models\Education::where('user_id', $user->id)
                                    ->wherenotnull('end_period')->orderby('degree_id', 'desc')->take(1)->get();
                                    $vacancy = \App\Models\Vacancies::find($application->vacancy_id);
                                    $agency = $vacancy->getAgency;
                                    $city = $vacancy->getCity->name;
                                    $degrees = $vacancy->getDegree;
                                    $majors = $vacancy->getMajor;
                                @endphp
                                <tr>
                                    <td class="a-center" style="vertical-align: middle" align="center">
                                        <input type="checkbox" class="flat">
                                    </td>
                                    <td style="vertical-align: middle">{{$application->id}}</td>
                                    <td style="vertical-align: middle">
                                        <table>
                                            <tr>
                                                <td>
                                                    <a href="{{route('seeker.profile',['id' => $user->id])}}"
                                                       target="_blank"
                                                       style="float: left;margin-right: .5em;margin-bottom: .5em">
                                                        <img class="img-responsive" width="100"
                                                             src="{{$user->ava == "" || $user->ava ==
                                                             "seeker.png" ? asset('images/seeker.png') :
                                                             asset('storage/users/ava/'.$user->ava)}}">
                                                    </a>
                                                    <table style="margin: 0">
                                                        <tr>
                                                            <td>
                                                                <a href="{{route('seeker.profile',['id' => $user->id])}}"
                                                                   target="_blank">
                                                                    <strong>{{$user->name}}</strong></a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><a href="mailto:{{$user->email}}">
                                                                    {{$user->email}}</a>
                                                                <a href="tel:{{$user->phone}}">
                                                                    {{$user->phone != "" ? ' | '.$user->phone : ''}}
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>{{$user->address}} &ndash; {{$user->zip_code}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                @if($user->total_exp != "")
                                                                    <span class="label label-default"
                                                                          style="background: #fa5555"
                                                                          data-toggle="tooltip" data-placement="left"
                                                                          title="Work Experience">
                                                                        <i class="fa fa-briefcase"></i>&ensp;
                                                                        {{$user->total_exp > 1 ?
                                                                        $user->total_exp.' years' :
                                                                        $user->total_exp.' year'}}</span>
                                                                @else
                                                                    <span class="label label-default"
                                                                          style="background: #fa5555"
                                                                          data-toggle="tooltip" data-placement="left"
                                                                          title="Work Experience">
                                                                        <i class="fa fa-briefcase"></i>&ensp;0 year
                                                                    </span>
                                                                @endif|
                                                                <span data-toggle="tooltip"
                                                                      title="Latest Education Degree"
                                                                      class="label label-primary">
                                                                        <strong><i class="fa fa-graduation-cap"></i>&ensp;
                                                                            {{$last_edu->count() ?
                                                                            \App\Models\Degrees::find($last_edu->first()
                                                                            ->degree_id)->name : '-'}}
                                                                        </strong></span>&nbsp;|
                                                                <span data-toggle="tooltip" data-placement="right"
                                                                      title="Latest Education Major"
                                                                      class="label label-info">
                                                                        <strong><i class="fa fa-user-graduate"></i>&ensp;
                                                                            {{$last_edu->count() ?
                                                                            \App\Models\Majors::find($last_edu->first()
                                                                            ->major_id)->name : '-'}}
                                                                        </strong>
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                        <hr style="margin: .5em auto">
                                        <table>
                                            <tr>
                                                <td>
                                                    <a href="{{route('agency.profile',['id' => $agency->id])}}"
                                                       target="_blank"
                                                       style="float: left;margin-right: .5em;margin-bottom: 0">
                                                        <img class="img-responsive" width="100" alt="agency.png"
                                                             src="{{$agency->ava == "" || $agency->ava == "agency.png" ?
                                                             asset('images/agency.png') :
                                                             asset('storage/admins/agencies/ava/'.$agency->ava)}}">
                                                    </a>
                                                    <table>
                                                        <tr>
                                                            <td>
                                                                <a href="{{route('detail.vacancy',['id' =>
                                                                $vacancy->id])}}" target="_blank">
                                                                    <strong>{{$vacancy->judul}}</strong></a>&nbsp;&ndash;
                                                                <span data-toggle="tooltip" data-placement="bottom"
                                                                      title="Status" class="label label-default"
                                                                      style="background: {{$vacancy->isPost == true ?
                                                                      '#00adb5' : '#fa5555'}}">
                                                                    <strong style="text-transform: uppercase">{{$vacancy->isPost == true ?
                                                                    'Active' : 'Inactive'}}</strong></span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <a href="{{route('agency.profile',['id' => $agency->id])}}"
                                                                   target="_blank">{{$agency->company}}</a>&nbsp;&ndash;
                                                                <a href="mailto:{{$agency->email}}">
                                                                    {{$agency->email}}</a>&nbsp;&ndash;
                                                                {{substr($city, 0, 2) == "Ko" ? substr($city,5) :
                                                                substr($city,10)}}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <strong data-toggle="tooltip" data-placement="left"
                                                                        title="Recruitment Date">
                                                                    {{$vacancy->recruitmentDate_start != "" &&
                                                                    $vacancy->recruitmentDate_end != "" ?
                                                                    \Carbon\Carbon::parse
                                                                    ($vacancy->recruitmentDate_start)
                                                                    ->format('j F Y').' - '.\Carbon\Carbon::parse
                                                                    ($vacancy->recruitmentDate_end)
                                                                    ->format('j F Y') : 'Unknown'}}
                                                                </strong> |
                                                                <span data-toggle="tooltip" data-placement="right"
                                                                      title="Job Interview Date" style="line-height: 0">
                                                                    {{$vacancy->interview_date != "" ?
                                                                    \Carbon\Carbon::parse($vacancy->interview_date)
                                                                    ->format('l, j F Y') : 'Unknown'}}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <span data-toggle="tooltip" title="Work Experience"
                                                                      data-placement="bottom" class="label label-info"
                                                                      style="background: #fa5555">
                                                                    <strong><i class="fa fa-briefcase"></i>&ensp;
                                                                        At least {{$vacancy->pengalaman > 1 ?
                                                                        $vacancy->pengalaman.' years' :
                                                                        $vacancy->pengalaman.' year'}}
                                                                    </strong></span>&nbsp;|
                                                                <span data-toggle="tooltip" title="Education Degree"
                                                                      data-placement="bottom"
                                                                      class="label label-primary">
                                                                    <strong><i class="fa fa-graduation-cap"></i>&ensp;
                                                                        {{$degrees->name}}</strong></span>&nbsp;|
                                                                <span data-toggle="tooltip" title="Education Major"
                                                                      data-placement="bottom" class="label label-info">
                                                                    <strong><i class="fa fa-user-graduate"></i>&ensp;
                                                                        {{$majors->name}}</strong></span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td style="vertical-align: middle" align="center">
                                        @if($application->isApply == true)
                                            <span class="label label-default" style="background: #00adb5">APPLIED</span>
                                            &ndash;&nbsp;on&nbsp;&ndash;
                                            <span class="label label-info">
                                                {{\Carbon\Carbon::parse($application->created_at)->format('j F Y')}}
                                            </span>
                                        @else
                                            <span class="label label-default"
                                                  style="background: #fa5555">NOT APPLY</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="row form-group">
                            <div class="col-sm-4" id="action-btn">
                                <div class="btn-group" style="float: right">
                                    <button id="btn_pdf" type="button" class="btn btn-primary btn-sm"
                                            style="font-weight: 600">
                                        <i class="fa fa-file-pdf"></i>&ensp;PDF
                                    </button>
                                    <button id="btn_remove_app" type="button" class="btn btn-danger btn-sm"
                                            style="font-weight: 600">
                                        <i class="fa fa-trash"></i>&ensp;REMOVE
                                    </button>
                                </div>
                            </div>
                            <form method="post" id="form-application">
                                {{csrf_field()}}
                                <input id="applicant_ids" type="hidden" name="applicant_ids">
                            </form>
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
            var table = $("#myDataTable").DataTable({
                order: [[1, "asc"]],
                columnDefs: [
                    {
                        targets: [0],
                        orderable: false
                    },
                    {
                        targets: [1],
                        visible: false,
                        searchable: false
                    }
                ]
            }), toolbar = $("#myDataTable_wrapper").children().eq(0);

            toolbar.children().toggleClass("col-sm-6 col-sm-4");
            $('#action-btn').appendTo(toolbar);

            @if($findVac != "")
            $("#vacancy_id").val('{{$findVac}}').selectpicker('refresh');
            $(".dataTables_filter input[type=search]").val('{{$findVac}}').trigger('keyup');
            @endif

            $("#vacancy_id").on('change', function () {
                $(".dataTables_filter input[type=search]").val($(this).val()).trigger('keyup');
            });

            $("#check-all").on("ifToggled", function () {
                if ($(this).is(":checked")) {
                    $("#myDataTable tbody tr").addClass("selected").find('input[type=checkbox]').iCheck("check");
                } else {
                    $("#myDataTable tbody tr").removeClass("selected").find('input[type=checkbox]').iCheck("uncheck");
                }
            });

            $("#myDataTable tbody").on("click", "tr", function () {
                $(this).toggleClass("selected");
                $(this).find('input[type=checkbox]').iCheck("toggle");
            });

            $('#btn_pdf').on("click", function () {
                var ids = $.map(table.rows('.selected').data(), function (item) {
                    return item[1]
                });
                $("#applicant_ids").val(ids);
                $("#form-application").attr("action", "{{route('table.applications.massPDF')}}").attr('target', '_blank');

                if (ids.length > 0) {
                    swal({
                        title: 'Generate PDF',
                        text: 'Are you sure to generate this ' + ids.length + ' selected records into a pdf file? ' +
                            'You won\'t be able to revert this!',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#00adb5',
                        confirmButtonText: 'Yes, generate now!',
                        showLoaderOnConfirm: true,

                        preConfirm: function () {
                            return new Promise(function (resolve) {
                                $("#form-application")[0].submit();
                                swal('Success', 'Pdf file(s) is successfully generated and zipped into PDFs.zip file!', 'success');
                            });
                        },
                        allowOutsideClick: false
                    });
                } else {
                    swal("Error!", "There's no any selected record!", "error");
                }
                return false;
            });

            $('#btn_remove_app').on("click", function () {
                var ids = $.map(table.rows('.selected').data(), function (item) {
                    return item[1]
                });
                $("#applicant_ids").val(ids);
                $("#form-application").attr("action", "{{route('table.applications.massDelete')}}").removeAttr('target');

                if (ids.length > 0) {
                    swal({
                        title: 'Remove Applications',
                        text: 'Are you sure to remove this ' + ids.length + ' selected records? ' +
                            'You won\'t be able to revert this!',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#fa5555',
                        confirmButtonText: 'Yes, delete it!',
                        showLoaderOnConfirm: true,

                        preConfirm: function () {
                            return new Promise(function (resolve) {
                                $("#form-application")[0].submit();
                            });
                        },
                        allowOutsideClick: false
                    });
                } else {
                    swal("Error!", "There's no any selected record!", "error");
                }
                return false;
            });
        });
    </script>
@endpush