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
                                <th style="width: 5%">No</th>
                                <th style="width: 50%">Contact</th>
                                <th style="width: 20%">Personal Data</th>
                                <th style="width: 5%">Status</th>
                                <th style="width: 20%">Action</th>
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
                                                                    <strong>{{$user->name.' ['.$user->nim.']'}}</strong></a>
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
                                    <td style="vertical-align: middle;text-transform: uppercase" align="center">
                                        @if($user->isValid != "")
                                            @if($user->isValid == false)
                                                <span class="label label-danger">Invalid</span>
                                            @else
                                                @if($user->status == true)
                                                    <span class="label label-success">Active</span>
                                                @else
                                                    <span class="label label-warning">Inactive</span>
                                                @endif
                                            @endif
                                        @else
                                            <span class="label label-default">Pending</span>
                                        @endif
                                    </td>
                                    <td style="vertical-align: middle" align="center">
                                        <div class="btn-group">
                                            <button onclick="validateUser('{{$user->id}}','{{$user->nim}}','{{$user->name}}')"
                                                    class="btn btn-primary btn-sm" data-toggle="tooltip"
                                                    title="{{$user->isValid == true ? 'Validated' : 'Validate'}}"
                                                    {{$user->isValid == true ? 'disabled' : ''}}>
                                                <i class="fa fa-check-circle"></i></button>
                                            <a href="{{route('seeker.profile',['id'=>$user->id])}}" target="_blank"
                                               class="btn btn-info btn-sm" data-toggle="tooltip" title="Details">
                                                <i class="fa fa-info-circle"></i></a>
                                            <a href="{{route('delete.users',['id'=>encrypt($user->id)])}}"
                                               class="btn btn-danger btn-sm delete-data" data-toggle="tooltip"
                                               title="Delete"><i class="fa fa-trash-alt"></i></a>
                                        </div>
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

    <div id="validateModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="width: 60%">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Validate Account: <strong></strong></h4>
                </div>
                <form method="post" action="{{route('validate.users')}}" id="form-validate-user">
                    {{csrf_field()}}
                    {{method_field('PUT')}}
                    <div class="modal-body">
                        <input type="hidden" name="user_id">
                        <div class="row form-group">
                            <div class="col-lg-2 col-md-2 col-sm-4">
                                <label class="control-label" for="rb_invalid">Status</label><br>
                                <input id="rb_invalid" type="radio" class="flat" name="isValid" value="0"> <b>INVALID</b>
                                <br><input id="rb_valid" type="radio" class="flat" name="isValid" value="1"> <b>VALID</b>
                            </div>
                            <div class="col-lg-10 col-md-10 col-sm-8">
                                <label class="control-label" for="note">Note</label>
                                <textarea name="note" id="note" class="form-control" style="resize: vertical"
                                          placeholder="Write something here..."></textarea>
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
@push('scripts')
    <script>
        $(function () {
            @if($find != "")
            $(".dataTables_filter input[type=search]").val('{{$find}}').trigger('keyup');
            @endif
        });

        $('#rb_invalid').on('ifChecked', function (){
            $('#note').attr('required', 'required').removeAttr('disabled');
        });

        $('#rb_valid').on('ifChecked', function () {
            $('#note').val('').removeAttr('required').attr('disabled', 'disabled');
        });

        function validateUser(id, nim, name) {
            $("#validateModal .modal-title strong").text(name + ' [' + nim + ']');
            $("#form-validate-user input[name=user_id]").val(id);
            $("#rb_invalid, #rb_valid").iCheck('uncheck');
            $("#note").val('').removeAttr('required').attr('disabled', 'disabled');
            $("#validateModal").modal('show');
        }
    </script>
@endpush