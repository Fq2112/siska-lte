@auth
    <li><a href="{{route('home-seeker')}}"><i class="fa fa-briefcase"></i> Vacancies</a></li>
    <li><a><i class="fa fa-home"></i> Dashboard <span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
            <li><a href="{{route('seeker.dashboard')}}"> Application Status</a></li>
            <li><a href="{{route('seeker.bookmarked.vacancy')}}"> Bookmarked Vacancy</a></li>
        </ul>
    </li>
    <li><a><i class="fa fa-user"></i> Account Settings<span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
            <li><a href="{{route('seeker.edit.profile')}}">Edit Profile</a></li>
            <li><a href="{{route('seeker.settings')}}">Change Password</a></li>
        </ul>
    </li>
@endauth

@auth('admin')
    <li><a href="{{route('home-admin')}}"><i class="fa fa-home"></i> Dashboard</a></li>
    <li><a href="{{route('table.agencies')}}"><i class="fa fa-building"></i> Agencies</a></li>
    <li><a><i class="fa fa-briefcase"></i> Vacancies <span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
            <li><a href="{{route('home-seeker')}}">Search</a></li>
            <li><a href="{{route('table.vacancies')}}">List & Setup</a></li>
        </ul>
    </li>
    <li>
        <a><i class="fa fa-table"></i> Tables <span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
            <li><a>Data Master <span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu">
                    <li><a>Accounts <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{route('table.admins')}}">Admins</a></li>
                            <li><a href="{{route('table.users')}}">Users</a></li>
                        </ul>
                    </li>
                    <li><a>Requirements <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{route('table.degrees')}}">Education Degrees</a>
                            </li>
                            <li><a href="{{route('table.majors')}}">Education Majors</a>
                            </li>
                            <li><a href="{{route('table.industries')}}">Industries</a></li>
                            <li><a href="{{route('table.JobFunctions')}}">Job Functions</a>
                            </li>
                            <li><a href="{{route('table.JobLevels')}}">Job Levels</a></li>
                            <li><a href="{{route('table.JobTypes')}}">Job Types</a></li>
                            <li><a href="{{route('table.salaries')}}">Salaries</a></li>
                        </ul>
                    </li>
                    <li><a>Web Contents <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{route('table.nations')}}">Nations</a></li>
                            <li><a href="{{route('table.provinces')}}">Provinces</a></li>
                            <li><a href="{{route('table.cities')}}">Cities</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li><a>Data Transaction <span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu">
                    <li><a>Seekers <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{route('table.applications')}}">Applications</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>
    </li>
@endauth