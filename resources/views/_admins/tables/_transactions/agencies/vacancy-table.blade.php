@extends('layouts.mst')
@section('title', 'Job Vacancies Table &ndash; '.env("APP_NAME").' Admins | SISKA &mdash; Sistem Informasi Karier')
@section('content')
    <div class="right_col" role="main">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Job Vacancies
                            <small>Table</small>
                        </h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link" data-toggle="tooltip" title="Minimize" data-placement="left">
                                    <i class="fa fa-chevron-up"></i></a></li>
                            <li><a onclick="createVacancy()" data-toggle="tooltip" title="Create"
                                   data-placement="right">
                                    <i class="fa fa-plus"></i></a></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <table id="datatable-buttons" class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Details</th>
                                <th>Action</th>
                            </tr>
                            </thead>

                            <tbody>
                            @php $no = 1; @endphp
                            @foreach($vacancies as $vacancy)
                                @php
                                    $agency = $vacancy->getAgency;
                                    $city = $vacancy->getCity->name;
                                    $salary = $vacancy->getSalary;
                                    $jobfunc = $vacancy->getJobFunction;
                                    $joblevel = $vacancy->GetJoblevel;
                                    $industry = $vacancy->getIndustry;
                                    $degrees = $vacancy->getDegree;
                                    $majors = $vacancy->getMajor;
                                @endphp
                                <tr>
                                    <td style="vertical-align: middle" align="center">{{$no++}}</td>
                                    <td style="vertical-align: middle">
                                        <table style="margin: 0">
                                            <tr>
                                                <td>
                                                    <a href="{{route('agency.profile',['id' => $vacancy->agency_id])}}"
                                                       target="_blank"
                                                       style="float: left;margin-right: .5em;margin-bottom: 0">
                                                        <img class="img-responsive" width="64" src="{{$agency->ava == ""
                                                        || $agency->ava == "agency.png" ? asset('images/agency.png') :
                                                        asset('storage/admins/ava/'.$agency->ava)}}">
                                                    </a>
                                                    <table style="margin: 0">
                                                        <tr>
                                                            <td>
                                                                <a href="{{route('detail.vacancy',['id' =>
                                                                $vacancy->id])}}" target="_blank">
                                                                    <strong>{{$vacancy->judul}}</strong></a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><a href="{{route('agency.profile',['id' =>
                                                            $vacancy->agency_id])}}"
                                                                   target="_blank">{{$agency->name}}</a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>{{substr($city, 0, 2)=="Ko" ? substr($city,5) :
                                                            substr($city,10)}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <span data-toggle="tooltip" data-placement="bottom"
                                                                      title="Recruitment Date" class="label label-info">
                                                                    <strong><i class="fa fa-users"></i>&ensp;
                                                                        {{$vacancy->recruitmentDate_start != "" &&
                                                                        $vacancy->recruitmentDate_end != "" ?
                                                                        \Carbon\Carbon::parse
                                                                        ($vacancy->recruitmentDate_start)
                                                                        ->format('j F Y').' - '.\Carbon\Carbon::parse
                                                                        ($vacancy->recruitmentDate_end)
                                                                        ->format('j F Y') : 'Unknown'}}
                                                                    </strong>
                                                                </span>&nbsp;|
                                                                <span data-toggle="tooltip" data-placement="bottom"
                                                                      title="Job Interview Date"
                                                                      class="label label-primary">
                                                                    <strong><i class="fa fa-user-tie"></i>&ensp;
                                                                        {{$vacancy->interview_date != "" ?
                                                                        \Carbon\Carbon::parse($vacancy->interview_date)
                                                                        ->format('l, j F Y') : 'Unknown'}}
                                                                    </strong>
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                        <hr style="margin: .5em auto">
                                        <table style="margin: 0">
                                            <tr>
                                                <td><i class="fa fa-warehouse"></i>&nbsp;</td>
                                                <td>Job Function</td>
                                                <td>&nbsp;:&nbsp;</td>
                                                <td>{{$jobfunc->name}}</td>
                                            </tr>
                                            <tr>
                                                <td><i class="fa fa-industry"></i>&nbsp;</td>
                                                <td>Industry</td>
                                                <td>&nbsp;:&nbsp;</td>
                                                <td>{{$industry->name}}</td>
                                            </tr>
                                            <tr>
                                                <td><i class="fa fa-level-up-alt"></i>&nbsp;</td>
                                                <td>Job Level</td>
                                                <td>&nbsp;:&nbsp;</td>
                                                <td>{{$joblevel->name}}</td>
                                            </tr>
                                            <tr>
                                                <td><i class="fa fa-money-bill-wave"></i>&nbsp;</td>
                                                <td>Salary</td>
                                                <td>&nbsp;:&nbsp;</td>
                                                <td>IDR {{$salary->name}}</td>
                                            </tr>
                                            <tr>
                                                <td><i class="fa fa-graduation-cap"></i>&nbsp;</td>
                                                <td>Education Degree</td>
                                                <td>&nbsp;:&nbsp;</td>
                                                <td>{{$degrees->name}}</td>
                                            </tr>
                                            <tr>
                                                <td><i class="fa fa-user-graduate"></i>&nbsp;</td>
                                                <td>Education Major</td>
                                                <td>&nbsp;:&nbsp;</td>
                                                <td>{{$majors->name}}</td>
                                            </tr>
                                            <tr>
                                                <td><i class="fa fa-briefcase"></i>&nbsp;</td>
                                                <td>Work Experience</td>
                                                <td>&nbsp;:&nbsp;</td>
                                                <td>
                                                    At least {{$vacancy->pengalaman > 1 ?
                                                    $vacancy->pengalaman.' years' : $vacancy->pengalaman.' year'}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><i class="fa fa-paper-plane"></i></td>
                                                <td>Total Applicant</td>
                                                <td>&nbsp;:&nbsp;</td>
                                                <td><strong>{{\App\Models\Applications::where('vacancy_id',$vacancy->id)
                                                ->where('isApply',true)->count()}}</strong> applicants
                                                </td>
                                            </tr>
                                        </table>
                                        <hr style="margin: .5em auto">
                                        <strong>Requirements</strong><br>{!! $vacancy->syarat !!}
                                        <hr style="margin: .5em auto">
                                        <strong>Responsibilities</strong><br>{!! $vacancy->tanggungjawab !!}
                                        <hr style="margin: .5em auto">
                                        <table style="margin: 0">
                                            <tr>
                                                <td><i class="fa fa-calendar"></i>&nbsp;</td>
                                                <td>Created at</td>
                                                <td>&nbsp;:&nbsp;</td>
                                                <td>{{\Carbon\Carbon::parse($vacancy->created_at)->format('j F Y')}}</td>
                                            </tr>
                                            <tr>
                                                <td><i class="fa fa-clock"></i>&nbsp;</td>
                                                <td>Last Update</td>
                                                <td>&nbsp;:&nbsp;</td>
                                                <td>{{$vacancy->updated_at->diffForHumans()}}</td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td style="vertical-align: middle" align="center">
                                        <a href="{{route('detail.vacancy',['id' => $vacancy->id])}}" target="_blank"
                                           class="btn btn-info btn-sm" style="font-size: 16px" data-toggle="tooltip"
                                           title="Details"><i class="fa fa-info-circle"></i></a>
                                        <hr style="margin: 5px auto">
                                        <a href="{{route('delete.vacancies',['id'=>encrypt($vacancy->id)])}}"
                                           class="btn btn-danger btn-sm delete-data" style="font-size: 16px"
                                           data-toggle="tooltip"
                                           title="Delete" data-placement="bottom"><i class="fa fa-trash-alt"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="createModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">Create Form</h4>
                </div>
                <form method="post" action="{{route('create.vacancies')}}">
                    {{csrf_field()}}
                    <div class="modal-body">
                        <div class="row form-group">
                            <div class="col-lg-12 has-feedback">
                                <label for="degree">Education Degree <span class="required">*</span></label>
                                <input id="degree" type="text" class="form-control" maxlength="191" name="name"
                                       placeholder="Degree name" required>
                                <span class="fa fa-graduation-cap form-control-feedback right"
                                      aria-hidden="true"></span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push("scripts")
    <script>
        function createVacancy() {
            $("#createModal").modal('show');
        }
    </script>
@endpush