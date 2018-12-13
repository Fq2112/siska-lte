@extends('layouts.mst')
@section('title', 'Users Table &ndash; '.env('APP_NAME').' Admins | SISKA &mdash; Sistem Informasi Karier')
@section('content')
    <div class="right_col" role="main">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Users
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
                        <table id="datatable-buttons" class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Contact</th>
                                <th>Personal Data</th>
                                <th>Action</th>
                            </tr>
                            </thead>

                            <tbody>
                            @php $no = 1; @endphp
                            @foreach($users as $user)
                                @php
                                    $last_edu = \App\Models\Education::where('user_id', $user->id)
                                    ->wherenotnull('end_period')->orderby('degree_id', 'desc')->take(1)->get();
                                @endphp
                                <tr>
                                    <td style="vertical-align: middle" align="center">{{$no++}}</td>
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
                                                                    <strong>{{$user->name}}</strong></a> &ndash;
                                                                <span class="label label-{{$user->status == true ?
                                                                'success' : 'warning'}}">{{$user->status == true ?
                                                                'Active' : 'Inactive'}}</span>
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
                                                        <tr>
                                                            <td><strong>Member Since:</strong> {{\Carbon\Carbon::parse
                                                            ($user->created_at)->format('j F Y')}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Last Update:</strong> {{$user->updated_at
                                                            ->diffForHumans()}}</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td style="vertical-align: middle">
                                        <table style="margin: 0">
                                            <tr>
                                                <td><i class="fa fa-birthday-cake"></i></td>
                                                <td>&nbsp;</td>
                                                <td>
                                                    {{$user->birthday == "" ? 'Birthday (-)' : \Carbon\Carbon::parse
                                                    ($user->birthday)->format('j F Y')}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><i class="fa fa-transgender"></i></td>
                                                <td>&nbsp;</td>
                                                <td style="text-transform: capitalize">
                                                    {{$user->gender != "" ? $user->gender : 'Gender (-)'}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><i class="fa fa-hand-holding-heart"></i></td>
                                                <td>&nbsp;</td>
                                                <td style="text-transform: capitalize">
                                                    {{$user->relationship != "" ? $user->relationship : 'Relationship Status (-)'}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><i class="fa fa-flag"></i></td>
                                                <td>&nbsp;</td>
                                                <td>
                                                    {{$user->nationality != "" ? $user->nationality : 'Nationality (-)'}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><i class="fa fa-hand-holding-usd"></i></td>
                                                <td>&nbsp;</td>
                                                <td style="text-transform: none" id="expected_salary{{$user->id}}">
                                                    @if($user->lowest_salary != "")
                                                        <script>
                                                            var low = ("{{$user->lowest_salary}}") / 1000000,
                                                                high = ("{{$user->highest_salary}}") / 1000000;
                                                            document.getElementById("expected_salary{{$user->id}}").innerText = "IDR " + low + " to " + high + " millions";
                                                        </script>
                                                    @else
                                                        Expected Salary (anything)
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td style="vertical-align: middle" align="center">
                                        <a href="{{route('seeker.profile',['id' => $user->seekers])}}" target="_blank"
                                           class="btn btn-info btn-sm" style="font-size: 16px" data-toggle="tooltip"
                                           title="Details" data-placement="left"><i class="fa fa-info-circle"></i></a>
                                        <hr style="margin: 5px auto">
                                        <a href="{{route('delete.users',['id'=>encrypt($user->id)])}}"
                                           class="btn btn-danger btn-sm delete-data" style="font-size: 16px"
                                           data-toggle="tooltip"
                                           title="Delete" data-placement="left"><i class="fa fa-trash-alt"></i></a>
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
@endsection