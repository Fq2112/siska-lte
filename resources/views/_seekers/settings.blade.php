@extends('layouts.mst')
@section('title', ''.$user->name.'\'s Account Settings: Change Password &ndash; '.env("APP_NAME").' | SISKA &mdash; Sistem Informasi Karier')
@section('content')
    <div class="right_col" role="main">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Account Settings
                            <small>Change Password</small>
                        </h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a>&nbsp;</a></li>
                            <li><a>&nbsp;</a></li>
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="col-lg-4 text-center">@include('layouts.partials.seekers._form_ava')</div>
                        <div class="col-lg-8">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <form class="form-horizontal" role="form" method="POST"
                                              id="form-password">
                                            {{ csrf_field() }}
                                            {{ method_field('put') }}
                                            <div class="card-content">
                                                <div class="card-title">
                                                    <div class="row form-group">
                                                        <div class="col-lg-12">
                                                            <label for="email">Primary E-mail (verified)</label>
                                                            <div class="input-group">
                                                                <span class="input-group-addon">
                                                                    <i class="glyphicon glyphicon-envelope"></i>
                                                                </span>
                                                                <input id="email" type="email" class="form-control"
                                                                       value="{{ $user->email }}" disabled>
                                                                <span class="input-group-addon">
                                                                    <i class="glyphicon glyphicon-check"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row form-group" id="error_curr_pass">
                                                        <div class="col-lg-12">
                                                            <label style="cursor: pointer; color: #FA5555;text-transform: uppercase"
                                                                   for="check_password"
                                                                   id="show_password_settings">Change Password ?</label>
                                                            <div class="input-group password_settings"
                                                                 style="display: none">
                                                                <span class="input-group-addon">
                                                                    <i class="glyphicon glyphicon-lock"></i>
                                                                </span>
                                                                <input placeholder="Current password"
                                                                       id="check_password"
                                                                       type="password" class="form-control"
                                                                       name="password"
                                                                       minlength="6" required autofocus>
                                                            </div>
                                                            <span class="help-block password_settings"
                                                                  style="text-transform: none;display: none">
                                                                <strong class="aj_pass"></strong></span>
                                                        </div>
                                                    </div>

                                                    <div class="row form-group password_settings" id="error_new_pass"
                                                         style="display: none">
                                                        <div class="col-lg-6">
                                                            <div class="input-group">
                                                                <span class="input-group-addon">
                                                                    <i class="glyphicon glyphicon-lock"></i>
                                                                </span>
                                                                <input placeholder="New password" id="password"
                                                                       type="password" class="form-control"
                                                                       name="new_password" minlength="6" required>
                                                            </div>
                                                            @if($errors->has('new_password'))
                                                                <span class="help-block">
                                                                    <strong>{{$errors->first('new_password')}}</strong>
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="input-group">
                                                                <span class="input-group-addon">
                                                                    <i class="glyphicon glyphicon-lock"></i>
                                                                </span>
                                                                <input placeholder="Retype password"
                                                                       id="password-confirm" type="password"
                                                                       class="form-control" name="password_confirmation"
                                                                       minlength="6" required
                                                                       onkeyup="return checkPassword()">
                                                            </div>
                                                            <span class="help-block">
                                                                <strong class="aj_new_pass"
                                                                        style="text-transform: none"></strong>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-read-more">
                                                <button id="btn_save_password" class="btn btn-link btn-block"
                                                        data-placement="bottom" data-toggle="tooltip"
                                                        title="Click here to submit your changes!" disabled>
                                                    <i class="fa fa-lock"></i>&nbsp;SAVE CHANGES
                                                </button>
                                            </div>
                                        </form>
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
@push('scripts')
    @include('layouts.partials.seekers._scripts_auth')
    @include('layouts.partials.seekers._scripts_ajax')
@endpush