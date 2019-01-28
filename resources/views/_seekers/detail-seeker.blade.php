@extends('layouts.mst')
@section('title', ''.$user->name.'\'s Profile &ndash; '.env("APP_NAME").' | SISKA &mdash; Sistem Informasi Karier')
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
                        <h2>{{$user->name}}
                            <small>Seeker Details</small>
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
                                        <img class="img-responsive avatar-view" src="{{$user->ava == "" ||
                                        $user->ava == "seeker.png" ? asset('images/seeker.png') :
                                        asset('storage/users/ava/'.$user->ava)}}">
                                    </a>
                                </div>
                            </div>
                            <h3>{{$user->name}}</h3>

                            <ul class="list-unstyled user_data">
                                <li data-toggle="tooltip" data-placement="left" title="{{count($job_title->get()) != 0 ?
                                    'Job Title' : 'Status'}}">
                                    <i class="fa fa-briefcase user-profile-icon"></i> {{count($job_title->get()) != 0 ?
                                    $job_title->first()->job_title : 'Looking for a Job'}}</li>
                                <li data-toggle="tooltip" data-placement="left" title="Address">
                                    <i class="fa fa-map-marker-alt user-profile-icon"></i> {{$user->address}}</li>
                                <li data-placement="left" data-toggle="tooltip" title="Total Work Experience">
                                    <i class="fa fa-briefcase"></i>
                                    &nbsp;{{$user->total_exp != "" ? $user->total_exp.' years' : '0 year'}}
                                </li>
                                <li data-placement="left" data-toggle="tooltip" title="Latest Degree">
                                    <i class="fa fa-graduation-cap"></i>
                                    &nbsp;{{count($last_edu->get()) != 0 ? \App\Models\Degrees::find($last_edu->first()
                                    ->degree_id)->name : 'Latest Degree (-)'}}
                                </li>
                                <li data-placement="left" data-toggle="tooltip" title="Latest Major">
                                    <i class="fa fa-user-graduate"></i>
                                    &nbsp;{{count($last_edu->get()) != 0 ? \App\Models\Majors::find($last_edu->first()
                                    ->major_id)->name : 'Latest Major (-)'}}
                                </li>
                                <li data-placement="left" data-toggle="tooltip" title="Latest GPA">
                                    <i class="fa fa-grin-stars"></i>
                                    &nbsp;{{count($last_edu->get()) != 0 && $last_edu->first()->nilai != "" ?
                                    $last_edu->first()->nilai : 'Latest GPA (-)'}}
                                </li>
                            </ul>

                            @auth
                                <a href="{{route('seeker.edit.profile')}}" class="btn btn-success">
                                    <i class="fa fa-edit m-right-xs"></i> Edit Profile</a>
                            @endauth

                            <h4>Personal Data</h4>
                            <table>
                                <tr data-placement="left" data-toggle="tooltip" title="Name">
                                    <td><i class="fa fa-id-card"></i></td>
                                    <td>&nbsp;</td>
                                    <td>{{$user->name}}</td>
                                </tr>
                                <tr data-placement="left" data-toggle="tooltip" title="Birthday">
                                    <td><i class="fa fa-birthday-cake"></i></td>
                                    <td>&nbsp;</td>
                                    <td>{{$user->birthday == "" ? '-' : \Carbon\Carbon::parse($user->birthday)
                                    ->format('j F Y')}}</td>
                                </tr>
                                <tr data-placement="left" data-toggle="tooltip" title="Gender">
                                    <td><i class="fa fa-transgender"></i></td>
                                    <td>&nbsp;</td>
                                    <td>{{$user->gender != "" ? $user->gender : '-'}}</td>
                                </tr>
                                <tr data-placement="left" data-toggle="tooltip" title="Relationship">
                                    <td><i class="fa fa-hand-holding-heart"></i></td>
                                    <td>&nbsp;</td>
                                    <td>{{$user->relationship != "" ? $user->relationship : '-'}}</td>
                                </tr>
                                <tr data-placement="left" data-toggle="tooltip" title="Nationality">
                                    <td><i class="fa fa-flag"></i></td>
                                    <td>&nbsp;</td>
                                    <td>{{$user->nationality != "" ? $user->nationality : '-'}}</td>
                                </tr>
                                <tr data-placement="left" data-toggle="tooltip" title="Personal Website">
                                    <td><i class="fa fa-globe"></i></td>
                                    <td>&nbsp;</td>
                                    <td style="text-transform: none">
                                        @if($user->website != "")
                                            <a href="{{$user->website}}" target="_blank">{{$user->website}}</a>
                                        @else
                                            (-)
                                        @endif
                                    </td>
                                </tr>
                                <tr data-placement="left" data-toggle="tooltip" title="Expected Salary">
                                    <td><i class="fa fa-hand-holding-usd"></i></td>
                                    <td>&nbsp;</td>
                                    <td style="text-transform: none" id="expected_salary">
                                        @if($user->lowest_salary != "")
                                            <script>
                                                var low = ("{{$user->lowest_salary}}") / 1000000,
                                                    high = ("{{$user->highest_salary}}") / 1000000;
                                                document.getElementById("expected_salary").innerText =
                                                    "IDR " + low + " to " + high + " millions";
                                            </script>
                                        @else
                                            (Anything)
                                        @endif
                                    </td>
                                </tr>
                            </table>

                            <h4>Contact</h4>
                            <table>
                                <tr data-placement="left" data-toggle="tooltip" title="E-mail">
                                    <td><i class="fa fa-envelope"></i></td>
                                    <td>&nbsp;</td>
                                    <td style="text-transform: none"><a
                                                href="mailto:{{$user->email}}">{{$user->email}}</a></td>
                                </tr>
                                <tr data-placement="left" data-toggle="tooltip" title="Phone">
                                    <td><i class="fa fa-phone"></i></td>
                                    <td>&nbsp;</td>
                                    <td>
                                        @if($user->phone != "")
                                            <a href="tel:{{$user->phone}}">{{$user->phone}}</a>
                                        @else
                                            (-)
                                        @endif
                                    </td>
                                </tr>
                                <tr data-placement="left" data-toggle="tooltip" title="Address">
                                    <td><i class="fa fa-home"></i></td>
                                    <td>&nbsp;</td>
                                    <td>{{$user->address != "" ? $user->address : '-'}}</td>
                                </tr>
                                <tr data-placement="left" data-toggle="tooltip" title="ZIP Code">
                                    <td><i class="fa fa-address-card"></i></td>
                                    <td>&nbsp;</td>
                                    <td>{{$user->zip_code != "" ? $user->zip_code : '-'}}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <div class="profile_title">
                                <div class="col-md-12">
                                    <h2>Summary</h2>
                                </div>
                            </div>
                            <blockquote style="font-size: 13px" data-scrollbar>
                                {!! $user->summary != "" ? $user->summary : '<p style="font-size: 13px">(empty)</p>' !!}
                            </blockquote>
                            <div class="profile_title">
                                <div class="col-md-12">
                                    <h2>Video Summary</h2>
                                </div>
                            </div>
                            @if($user->video_summary != "")
                                <video style="width: 100%;height: auto"
                                       src="{{asset('storage/users/video/'.$user->video_summary)}}"
                                       controls></video>
                            @else
                                <video style="width: 100%;height: auto"
                                       src="{{asset('images/vid-placeholder.mp4')}}"></video>
                            @endif

                            <div role="tabpanel" data-example-id="togglable-tabs">
                                <ul class="nav nav-tabs bar_tabs" role="tablist">
                                    <li role="presentation" class="active" data-toggle="tooltip" title="Attachments">
                                        <a href="#attachments" role="tab" id="attachments-tab" data-toggle="tab"
                                           aria-expanded="false">A</a>
                                    </li>
                                    <li role="presentation" data-toggle="tooltip" title="Work Experience">
                                        <a href="#exp" role="tab" id="exp-tab" data-toggle="tab"
                                           aria-expanded="false">WE</a>
                                    </li>
                                    <li role="presentation" data-toggle="tooltip" title="Education">
                                        <a href="#edu" role="tab" id="edu-tab" data-toggle="tab"
                                           aria-expanded="false">E</a>
                                    </li>
                                    <li role="presentation" data-toggle="tooltip" title="Training / Certification">
                                        <a href="#cert" role="tab" id="cert-tab" data-toggle="tab"
                                           aria-expanded="false">T/C</a>
                                    </li>
                                    <li role="presentation" data-toggle="tooltip" title="Organization Experience">
                                        <a href="#org" role="tab" id="org-tab" data-toggle="tab"
                                           aria-expanded="false">OE</a>
                                    </li>
                                    <li role="presentation" data-toggle="tooltip" title="Language Skill">
                                        <a href="#lang" role="tab" id="lang-tab" data-toggle="tab"
                                           aria-expanded="false">LS</a>
                                    </li>
                                    <li role="presentation" data-toggle="tooltip" title="Skill">
                                        <a href="#skill" role="tab" id="skill-tab" data-toggle="tab"
                                           aria-expanded="false">S</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane fade active in" id="attachments"
                                         aria-labelledby="attachments-tab">
                                        @if(count($attachments) != 0)
                                            <div data-scrollbar>
                                                @foreach($attachments as $row)
                                                    <div class="media">
                                                        <div class="media-left media-middle">
                                                            <img width="100" class="media-object" src="{{strtolower
                                                            (pathinfo($row->files, PATHINFO_EXTENSION)) == "jpg" ||
                                                            strtolower(pathinfo($row->files, PATHINFO_EXTENSION)) == "jpeg" ||
                                                            strtolower(pathinfo($row->files, PATHINFO_EXTENSION)) == "png" ||
                                                            strtolower(pathinfo($row->files, PATHINFO_EXTENSION)) == "gif" ?
                                                            asset('storage/users/attachments/'.$row->files) :
                                                            asset('images/files.png')}}">
                                                        </div>
                                                        <div class="media-body">
                                                            @if(Auth::guard('admin')->check())
                                                                <form class="pull-right to-animate-2"
                                                                      id="form-download-attachments{{$row->id}}"
                                                                      action="{{route('download.seeker.attachments',[
                                                                      'files' => $row->files])}}" data-toggle="tooltip"
                                                                      data-placement="left"
                                                                      title="Download {{$row->files}}">{{csrf_field()}}
                                                                    <div class="anim-icon anim-icon-md download ld ld-breath"
                                                                         id="{{$row->id}}"
                                                                         onclick="downloadAttachments(id)"
                                                                         style="font-size: 25px">
                                                                        <input type="hidden" name="attachments_id"
                                                                               value="{{$row->id}}">
                                                                        <input type="checkbox">
                                                                        <label for="download"></label>
                                                                    </div>
                                                                </form>
                                                            @endif
                                                            <blockquote
                                                                    style="font-size: 12px;text-transform: none">{{$row->files}}</blockquote>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <blockquote><p style="font-size: 13px">(empty)</p></blockquote>
                                        @endif
                                    </div>
                                    <div role="tabpanel" class="tab-pane fade" id="exp" aria-labelledby="exp-tab">
                                        @if(count($experiences) != 0)
                                            <div data-scrollbar>
                                                @foreach($experiences as $row)
                                                    @php
                                                        $jl = $row->getJobLevel->name;
                                                        $jt = $row->jobtype_id != "" ?
                                                        $row->getJobType->name : '(empty)';
                                                        $jf = $row->getJobFunction->name;
                                                        $ind = $row->getIndustry->name;
                                                        $loc = substr($row->getCity->name,0,2) == "Ko" ?
                                                        substr($row->getCity->name,5) :
                                                        substr($row->getCity->name,10);
                                                        $sal = $row->salary_id != "" ? $row->getSalary
                                                        ->name : 'Rather not say';
                                                    @endphp
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="media">
                                                                <div class="media-left media-middle">
                                                                    <img width="100" class="media-object"
                                                                         src="{{asset('images/exp.png')}}">
                                                                </div>
                                                                <div class="media-body">
                                                                    <p class="media-heading">
                                                                        <i class="fa fa-briefcase">&nbsp;</i>
                                                                        <small style="text-transform: uppercase">
                                                                            {{$row->job_title}}
                                                                        </small>
                                                                    </p>
                                                                    <blockquote
                                                                            style="font-size: 14px;text-transform: none">
                                                                        <table style="font-size: 14px; margin-top: 0">
                                                                            <tr>
                                                                                <td><i class="fa fa-building"></i></td>
                                                                                <td>&nbsp;Agency Name</td>
                                                                                <td>&nbsp;:&nbsp;</td>
                                                                                <td>{{$row->company}}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><i class="fa fa-level-up-alt"></i>
                                                                                </td>
                                                                                <td>&nbsp;Job Level</td>
                                                                                <td>&nbsp;:&nbsp;</td>
                                                                                <td>{{$jl}}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>
                                                                                    <i class="fa fa-warehouse"></i>
                                                                                </td>
                                                                                <td>&nbsp;Job Function</td>
                                                                                <td>&nbsp;:&nbsp;</td>
                                                                                <td>{{$jf}}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><i class="fa fa-industry"></i></td>
                                                                                <td>&nbsp;Industry</td>
                                                                                <td>&nbsp;:&nbsp;</td>
                                                                                <td>{{$ind}}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><i class="fa fa-map-marked"></i>
                                                                                </td>
                                                                                <td>&nbsp;Location</td>
                                                                                <td>&nbsp;:&nbsp;</td>
                                                                                <td>{{$loc}}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>
                                                                                    <i class="fa fa-money-bill-wave"></i>
                                                                                </td>
                                                                                <td>&nbsp;Salary</td>
                                                                                <td>&nbsp;:&nbsp;</td>
                                                                                <td>{{$sal}}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><i class="fa fa-calendar-alt"></i>
                                                                                </td>
                                                                                <td>&nbsp;Since</td>
                                                                                <td>&nbsp;:&nbsp;</td>
                                                                                <td>{{\Carbon\Carbon::parse
                                                                                ($row->start_date)->format('j F Y')}}
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><i class="fa fa-calendar-check"></i>
                                                                                </td>
                                                                                <td>&nbsp;Until</td>
                                                                                <td>&nbsp;:&nbsp;</td>
                                                                                <td>{{$row->end_date != "" ?
                                                                                \Carbon\Carbon::parse($row->end_date)
                                                                                ->format('j F Y') : 'Present'}}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><i class="fa fa-user-clock"></i>
                                                                                </td>
                                                                                <td>&nbsp;Job Type</td>
                                                                                <td>&nbsp;:&nbsp;</td>
                                                                                <td>{{$jt}}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><i class="fa fa-user-tie"></i></td>
                                                                                <td>&nbsp;Report to</td>
                                                                                <td>&nbsp;:&nbsp;</td>
                                                                                <td>{{$row->report_to != "" ?
                                                                                $row->report_to : '(empty)'}}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><i class="fa fa-comments"></i></td>
                                                                                <td>&nbsp;Job Description</td>
                                                                                <td>&nbsp;:&nbsp;</td>
                                                                                <td>&nbsp;</td>
                                                                            </tr>
                                                                        </table>
                                                                        {!! $row->job_desc != "" ? $row->job_desc :
                                                                        '(empty)'!!}
                                                                    </blockquote>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr class="hr-divider">
                                                @endforeach
                                            </div>
                                        @else
                                            <blockquote><p style="font-size: 13px">(empty)</p></blockquote>
                                        @endif
                                    </div>
                                    <div role="tabpanel" class="tab-pane fade" id="edu" aria-labelledby="edu-tab">
                                        @if(count($educations) != 0)
                                            <div data-scrollbar>
                                                @foreach($educations as $row)
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="media">
                                                                <div class="media-left media-middle">
                                                                    <img width="100" class="media-object"
                                                                         src="{{asset('images/edu.png')}}">
                                                                </div>
                                                                <div class="media-body">
                                                                    <p class="media-heading">
                                                                        <i class="fa fa-school">&nbsp;</i>
                                                                        <small style="text-transform: uppercase">{{$row->school_name}}</small>
                                                                    </p>
                                                                    <blockquote
                                                                            style="font-size: 14px;text-transform: none">
                                                                        <table style="font-size: 14px; margin-top: 0">
                                                                            <tr>
                                                                                <td><i class="fa fa-graduation-cap"></i>
                                                                                </td>
                                                                                <td>&nbsp;Education Degree</td>
                                                                                <td>&nbsp;:&nbsp;</td>
                                                                                <td>{{\App\Models\Degrees::find
                                                                                ($row->degree_id)->name}}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><i class="fa fa-user-graduate"></i>
                                                                                </td>
                                                                                <td>&nbsp;Education Major</td>
                                                                                <td>&nbsp;:&nbsp;</td>
                                                                                <td>{{\App\Models\Majors::find
                                                                                ($row->major_id)->name}}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>
                                                                                    <i class="fa fa-hourglass-start"></i>
                                                                                </td>
                                                                                <td>&nbsp;Start Period</td>
                                                                                <td>&nbsp;:&nbsp;</td>
                                                                                <td>{{$row->start_period}}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><i class="fa fa-hourglass-end"></i>
                                                                                </td>
                                                                                <td>&nbsp;End Period</td>
                                                                                <td>&nbsp;:&nbsp;</td>
                                                                                <td>{{$row->end_period == "" ?
                                                                                'Present' : $row->end_period}}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><i class="fa fa-grin-stars"></i>
                                                                                </td>
                                                                                <td>&nbsp;GPA</td>
                                                                                <td>&nbsp;:&nbsp;</td>
                                                                                <td>{{$row->nilai != "" ? $row->nilai :
                                                                                '(-)'}}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><i class="fa fa-trophy"></i></td>
                                                                                <td>&nbsp;Honors/Awards</td>
                                                                                <td>&nbsp;:&nbsp;</td>
                                                                                <td>&nbsp;</td>
                                                                            </tr>
                                                                        </table>
                                                                        {!! $row->awards != "" ? $row->awards :
                                                                        '(empty)'!!}
                                                                    </blockquote>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr class="hr-divider">
                                                @endforeach
                                            </div>
                                        @else
                                            <blockquote><p style="font-size: 13px">(empty)</p></blockquote>
                                        @endif
                                    </div>
                                    <div role="tabpanel" class="tab-pane fade" id="cert" aria-labelledby="cert-tab">
                                        @if(count($trainings) != 0)
                                            <div data-scrollbar>
                                                @foreach($trainings as $row)
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="media">
                                                                <div class="media-left media-middle">
                                                                    <img width="100" class="media-object"
                                                                         src="{{asset('images/cert.png')}}">
                                                                </div>
                                                                <div class="media-body">
                                                                    <p class="media-heading">
                                                                        <i class="fa fa-certificate"></i>&nbsp;
                                                                        <small style="text-transform: uppercase">{{$row->name}}</small>
                                                                    </p>
                                                                    <blockquote
                                                                            style="font-size: 14px;text-transform: none">
                                                                        <table style="font-size: 14px; margin-top: 0">
                                                                            <tr>
                                                                                <td><i class="fa fa-university"></i>
                                                                                </td>
                                                                                <td>&nbsp;Issued by</td>
                                                                                <td>&nbsp;:&nbsp;</td>
                                                                                <td>{{$row->issuedby}}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><i class="fa fa-calendar-alt"></i>
                                                                                </td>
                                                                                <td>&nbsp;Issued Date
                                                                                </td>
                                                                                <td>&nbsp;:&nbsp;</td>
                                                                                <td>{{\Carbon\Carbon::parse
                                                                                ($row->isseuddate)->format('j F Y')}}
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><i class="fa fa-comments"></i></td>
                                                                                <td>&nbsp;Description</td>
                                                                                <td>&nbsp;:&nbsp;</td>
                                                                                <td>&nbsp;</td>
                                                                            </tr>
                                                                        </table>
                                                                        {!! $row->descript != "" ? $row->descript :
                                                                        '(empty)'!!}
                                                                    </blockquote>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr class="hr-divider">
                                                @endforeach
                                            </div>
                                        @else
                                            <blockquote><p style="font-size: 13px">(empty)</p></blockquote>
                                        @endif
                                    </div>
                                    <div role="tabpanel" class="tab-pane fade" id="org" aria-labelledby="org-tab">
                                        @if(count($organizations) != 0)
                                            <div data-scrollbar>
                                                @foreach($organizations as $row)
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="media">
                                                                <div class="media-left media-middle">
                                                                    <img width="100" class="media-object"
                                                                         src="{{asset('images/org.png')}}">
                                                                </div>
                                                                <div class="media-body">
                                                                    <p class="media-heading">
                                                                        <i class="fa fa-users">&nbsp;</i>
                                                                        <small style="text-transform: uppercase">{{$row->name}}</small>
                                                                    </p>
                                                                    <blockquote
                                                                            style="font-size: 14px;text-transform: none">
                                                                        <table style="font-size: 14px; margin-top: 0">
                                                                            <tr>
                                                                                <td>
                                                                                    <i class="fa fa-hourglass-start"></i>
                                                                                </td>
                                                                                <td>&nbsp;Start Period</td>
                                                                                <td>&nbsp;:&nbsp;</td>
                                                                                <td>{{$row->start_period}}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><i class="fa fa-hourglass-end"></i>
                                                                                </td>
                                                                                <td>&nbsp;End Period</td>
                                                                                <td>&nbsp;:&nbsp;</td>
                                                                                <td>{{$row->end_period == "" ?
                                                                                'Present' : $row->end_period}}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><i class="fa fa-comments"></i></td>
                                                                                <td>&nbsp;Description</td>
                                                                                <td>&nbsp;:&nbsp;</td>
                                                                                <td>&nbsp;</td>
                                                                            </tr>
                                                                        </table>
                                                                        {!! $row->descript != "" ? $row->descript :
                                                                        '(empty)'!!}
                                                                    </blockquote>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr class="hr-divider">
                                                @endforeach
                                            </div>
                                        @else
                                            <blockquote><p style="font-size: 13px">(empty)</p></blockquote>
                                        @endif
                                    </div>
                                    <div role="tabpanel" class="tab-pane fade" id="lang" aria-labelledby="lang-tab">
                                        @if(count($languages) != 0)
                                            <div data-scrollbar>
                                                @foreach($languages as $row)
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="media">
                                                                <div class="media-left media-middle">
                                                                    <img width="100" class="media-object"
                                                                         src="{{asset('images/lang.png')}}">
                                                                </div>
                                                                <div class="media-body">
                                                                    <p class="media-heading">
                                                                        <i class="fa fa-language"></i>&nbsp;
                                                                        <small style="text-transform: uppercase">{{$row->name}}</small>
                                                                    </p>
                                                                    <blockquote
                                                                            style="font-size: 14px;text-transform: none">
                                                                        <table style="font-size: 14px; margin-top: 0">
                                                                            <tr>
                                                                                <td><i class="fa fa-comments"></i></td>
                                                                                <td>&nbsp;Speaking Level</td>
                                                                                <td>&nbsp;:&nbsp;</td>
                                                                                <td>{{$row->spoken_lvl != "" ?
                                                                                $row->spoken_lvl : '-'}}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><i class="fa fa-pencil-alt"></i>
                                                                                </td>
                                                                                <td>&nbsp;Writing Level</td>
                                                                                <td>&nbsp;:&nbsp;</td>
                                                                                <td>{{$row->written_lvl != "" ?
                                                                                $row->written_lvl : '-'}}</td>
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
                                        @else
                                            <blockquote><p style="font-size: 13px">(empty)</p></blockquote>
                                        @endif
                                    </div>
                                    <div role="tabpanel" class="tab-pane fade" id="skill" aria-labelledby="skill-tab">
                                        @if(count($skills) != 0)
                                            <div data-scrollbar>
                                                @foreach($skills as $row)
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="media">
                                                                <div class="media-left media-middle">
                                                                    <img width="100" class="media-object"
                                                                         src="{{asset('images/lang.png')}}">
                                                                </div>
                                                                <div class="media-body">
                                                                    <p class="media-heading">
                                                                        <i class="fa fa-language"></i>&nbsp;
                                                                        <small style="text-transform: uppercase">{{$row->name}}</small>
                                                                    </p>
                                                                    <blockquote
                                                                            style="font-size: 14px;text-transform: none">
                                                                        <table style="font-size: 14px; margin-top: 0">
                                                                            <tr>
                                                                                <td><i class="fa fa-chart-line"></i>
                                                                                </td>
                                                                                <td>&nbsp;Level</td>
                                                                                <td>&nbsp;:&nbsp;</td>
                                                                                <td>{{$row->level != "" ? $row->level :
                                                                                '-'}}</td>
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
                                        @else
                                            <blockquote><p style="font-size: 13px">(empty)</p></blockquote>
                                        @endif
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
            <img class="img-responsive" src="{{$user->ava == "" || $user->ava == "seeker.png" ?
            asset('images/seeker.png') : asset('storage/users/ava/'.$user->ava)}}">
        </div>
    </div>
@endsection
@push("scripts")
    <script>
        function showAva() {
            $("#avaModal").modal('show');
        }

        function downloadAttachments(id) {
            $("#" + id).removeClass('ld ld-breath');
            $("#" + id + ' input[type=checkbox]').prop('checked', true);
            $("#form-download-attachments" + id)[0].submit();
        }
    </script>
@endpush
