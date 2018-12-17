<style>
    .image-upload > input {
        display: none;
    }

    .image-upload label {
        cursor: pointer;
        width: 100%;
    }
</style>
<form class="form-horizontal" role="form" method="POST" id="form-ava"
      enctype="multipart/form-data">
    {{ csrf_field() }}
    {{ method_field('put') }}
    <div class="card">
        <div class="img-card image-upload">
            <label for="file-input">
                <img style="width: 100%" class="show_ava" alt="AVA is here..."
                     src="{{$user->ava == 'seeker.png' || $user->ava == '' ? asset('images/seeker.png') :
                         asset('storage/users/ava/'.$user->ava)}}"
                     data-placement="bottom" data-toggle="tooltip"
                     title="Click here to change your AVA!">
            </label>
            <input id="file-input" name="ava" type="file" accept="image/*">
            <div id="progress-upload-ava">
                <div class="progress-bar progress-bar-danger progress-bar-striped active"
                     role="progressbar" aria-valuenow="0"
                     aria-valuemin="0" aria-valuemax="100">
                </div>
            </div>
        </div>
        <div class="card-content">
            <div class="card-title text-center">
                <a href="{{route('seeker.edit.profile')}}">
                    <h4 class="aj_name" style="color: #35495d">{{$user->name}}</h4></a>
                <small>{{count($job_title->get()) != 0 ? $job_title->first()->job_title : 'Current Job Title (-)'}}
                </small>
            </div>
            <div class="card-title">
                <table class="stats">
                    <tr>
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
                                Expected Salary (Anything)
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><i class="fa fa-briefcase"></i></td>
                        <td>&nbsp;</td>
                        <td style="text-transform: none">
                            Total Exp: {{$user->total_exp != "" ? $user->total_exp.' years' : '0 year'}}
                        </td>
                    </tr>
                    <tr>
                        <td><i class="fa fa-graduation-cap"></i></td>
                        <td>&nbsp;</td>
                        <td>{{count($last_edu->get()) != 0 ? \App\Models\Degrees::find($last_edu->first()->degree_id)
                        ->name : 'Latest Education Degree (-)'}}</td>
                    </tr>
                    <tr>
                        <td><i class="fa fa-user-graduate"></i></td>
                        <td>&nbsp;</td>
                        <td>{{count($last_edu->get()) != 0 ? \App\Models\Majors::find($last_edu->first()->major_id)
                            ->name : 'Latest Education Major (-)'}}</td>
                    </tr>
                    <tr>
                        <td><i class="fa fa-grin-stars"></i></td>
                        <td>&nbsp;</td>
                        <td>{{count($last_edu->get()) != 0 && $last_edu->first()->nilai != "" ? $last_edu->first()
                            ->nilai : 'Latest GPA (-)'}}</td>
                    </tr>
                </table>
                <hr>
                <table class="stats" style="font-size: 14px">
                    <tr>
                        <td><i class="fa fa-calendar-check"></i></td>
                        <td>&nbsp;Member Since</td>
                        <td>
                            : {{$user->created_at->format('j F Y')}}
                        </td>
                    </tr>
                    <tr>
                        <td><i class="fa fa-clock"></i></td>
                        <td>&nbsp;Last Update</td>
                        <td>
                            : {{$user->updated_at->diffForHumans()}}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</form>
