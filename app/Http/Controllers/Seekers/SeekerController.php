<?php

namespace App\Http\Controllers\Seekers;

use App\Models\Applications;
use App\Models\Agencies;
use App\Models\Attachments;
use App\Models\Cities;
use App\Models\Education;
use App\Models\Experience;
use App\Models\JobFunction;
use App\Models\Industries;
use App\Models\JobLevel;
use App\Models\JobType;
use App\Models\Degrees;
use App\Models\Majors;
use App\Models\Languages;
use App\Models\Organization;
use App\Models\Salaries;
use App\Models\Skills;
use App\Models\Training;
use App\Models\User;
use App\Models\Provinces;
use App\Models\Vacancies;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class SeekerController extends Controller
{
    public function __construct()
    {
        $this->middleware('seeker')->except(['index', 'showProfile', 'downloadSeekerAttachments']);
        $this->middleware('seeker.home')->only('index');
        $this->middleware('seeker.profile')->only('showProfile');
        $this->middleware('admin')->only('downloadSeekerAttachments');
    }

    public function index(Request $request)
    {
        $provinces = Provinces::all();
        $keyword = $request->q;
        $location = $request->loc;
        $page = $request->page;

        return view('_seekers.home-seeker', compact('provinces', 'keyword', 'location', 'page'));
    }

    public function detailSeeker($id)
    {
        $user = User::find($id);
        $attachments = Attachments::where('user_id', $id)->orderByDesc('created_at')->get();
        $experiences = Experience::where('user_id', $id)->orderByDesc('id')->get();
        $educations = Education::where('user_id', $id)->orderByDesc('degree_id')->get();
        $trainings = Training::where('user_id', $id)->orderByDesc('id')->get();
        $organizations = Organization::where('user_id', $id)->orderByDesc('id')->get();
        $languages = Languages::where('user_id', $id)->orderByDesc('id')->get();
        $skills = Skills::where('user_id', $id)->orderByDesc('id')->get();

        $job_title = Experience::where('user_id', $id)->where('end_date', null)
            ->orderby('id', 'desc')->take(1);

        $last_edu = Education::where('user_id', $id)->wherenotnull('end_period')
            ->orderby('degree_id', 'desc')->take(1);

        return view('_seekers.detail-seeker', compact('user', 'attachments', 'experiences', 'educations',
            'trainings', 'organizations', 'languages', 'skills', 'job_title', 'last_edu'));
    }

    public function downloadSeekerAttachments($files)
    {
        $file_path = public_path('storage/users/attachments/' . $files);
        return response()->download($file_path);
    }

    public function showDashboard()
    {
        $provinces = Provinces::all();
        $user = Auth::user();

        $job_title = Experience::where('user_id', $user->id)->where('end_date', null)
            ->orderby('id', 'desc')->take(1);

        $last_edu = Education::where('user_id', $user->id)->wherenotnull('end_period')
            ->orderby('degree_id', 'desc')->take(1);

        return view('auth.seekers.dashboard', compact('user', 'provinces', 'seeker', 'job_title',
            'last_edu'));
    }

    public function getAccVacancies(Request $request)
    {
        $start = $request->start_date;
        $end = $request->end_date;

        $result = Applications::where('user_id', $request->user_id)->whereBetween('created_at', [$start, $end])
            ->where('isApply', true)->orderByDesc('id')->paginate(6)->toArray();

        $result = $this->array_applications($result);

        return $result;
    }

    public function showCompare($id)
    {
        $user = Auth::user();

        $vacancy = Vacancies::find($id)->toArray();
        $agency = Agencies::findOrFail($vacancy['agency_id']);

        $reqEdu = $vacancy['degree_id'];
        $reqExp = filter_var($vacancy['pengalaman'], FILTER_SANITIZE_NUMBER_INT);

        $acc = Applications::where('vacancy_id', $vacancy['id'])->where('isApply', true);
        $totalApp = array('total_app' => $acc->count());

        $cities = substr(Cities::find($vacancy['cities_id'])->name, 0, 2) == "Ko" ?
            substr(Cities::find($vacancy['cities_id'])->name, 5) :
            substr(Cities::find($vacancy['cities_id'])->name, 10);

        $filename = $agency->ava == "agency.png" || $agency->ava == "" ? asset('images/agency.png') :
            asset('storage/admins/agencies/ava/' . $agency->ava);

        $city = array('city' => $cities);
        $degrees = array('degrees' => Degrees::findOrFail($vacancy['degree_id'])->name);
        $majors = array('majors' => Majors::findOrFail($vacancy['major_id'])->name);
        $jobfunc = array('job_func' => JobFunction::findOrFail($vacancy['jobfunction_id'])->name);
        $industry = array('industry' => Industries::findOrFail($vacancy['industry_id'])->name);
        $jobtype = array('job_type' => JobType::findOrFail($vacancy['jobtype_id'])->name);
        $joblevel = array('job_level' => JobLevel::findOrFail($vacancy['joblevel_id'])->name);
        $salary = array('salary' => Salaries::findOrFail($vacancy['salary_id'])->name);
        $ava['user'] = array('ava' => $filename, 'name' => $agency->company);
        $update_at = array('updated_at' => Carbon::createFromFormat('Y-m-d H:i:s',
            $vacancy['updated_at'])->diffForHumans());

        $accSeeker = $acc->where('user_id', $user->id);
        $applied = array('applied_on' => $accSeeker->count() > 0 ? Carbon::parse($accSeeker->first()->created_at)
            ->format('j F Y') : '');

        // compare education
        $eduEqual = array('edu_equal' => User::wherehas('getEducation', function ($query) use ($reqEdu) {
            $query->where('degree_id', $reqEdu)->wherenotnull('end_period');
        })->wherehas('getApplication', function ($query) use ($vacancy) {
            $query->where('vacancy_id', $vacancy['id']);
        })->count());
        $eduHigher = array('edu_higher' => User::wherehas('getEducation', function ($query) use ($reqEdu) {
            $query->where('degree_id', '>', $reqEdu)->wherenotnull('end_period');
        })->wherehas('getApplication', function ($query) use ($vacancy) {
            $query->where('vacancy_id', $vacancy['id']);
        })->count());

        // compare experience
        $expEqual = array('exp_equal' => User::wherehas('getApplication', function ($query) use ($vacancy) {
            $query->where('vacancy_id', $vacancy['id']);
        })->where('total_exp', $reqExp)->count());
        $expHigher = array('exp_higher' => User::wherehas('getApplication', function ($query) use ($vacancy) {
            $query->where('vacancy_id', $vacancy['id']);
        })->where('total_exp', '>', $reqExp)->count());

        $result = array_replace($ava, $vacancy, $city, $degrees, $majors, $jobfunc, $industry, $jobtype, $joblevel,
            $salary, $update_at, $applied, $totalApp, $eduEqual, $eduHigher, $expEqual, $expHigher);

        return $result;
    }

    public function showBookmark()
    {
        $user = Auth::user();
        $provinces = Provinces::all();

        $job_title = Experience::where('user_id', $user->id)->where('end_date', null)
            ->orderby('id', 'desc')->take(1);

        $last_edu = Education::where('user_id', $user->id)->wherenotnull('end_period')
            ->orderby('degree_id', 'desc')->take(1);

        $bookmark = Applications::where('user_id', $user->id)->where('isBookmark', true)->paginate(5);

        return view('auth.seekers.dashboard-bookmarked', compact('user', 'provinces',
            'job_title', 'last_edu', 'bookmark'));
    }

    public function getBookmarkedVacancies(Request $request)
    {
        $start = $request->start_date;
        $end = $request->end_date;

        $result = Applications::where('user_id', $request->user_id)->whereBetween('created_at', [$start, $end])
            ->where('isBookmark', true)->orderByDesc('id')->paginate(6)->toArray();

        $result = $this->array_applications($result);

        return $result;
    }

    private function array_applications($result)
    {
        $i = 0;
        foreach ($result['data'] as $row) {
            $vacancy = Vacancies::find($row['vacancy_id']);

            $cities = substr(Cities::find($vacancy->cities_id)->name, 0, 2) == "Ko" ?
                substr(Cities::find($vacancy->cities_id)->name, 5) :
                substr(Cities::find($vacancy->cities_id)->name, 10);

            $agency = $vacancy->getAgency;
            $filename = $agency->ava == "agency.png" || $agency->ava == "" ?
                asset('images/agency.png') : asset('storage/admins/agencies/ava/' . $agency->ava);

            $judul['vacancy'] = array('id' => $vacancy->id, 'judul' => $vacancy->judul, 'city' => $cities,
                'degrees' => Degrees::findOrFail($vacancy->degree_id)->name,
                'majors' => Majors::findOrFail($vacancy->major_id)->name,
                'job_func' => JobFunction::findOrFail($vacancy->jobfunction_id)->name,
                'industry' => Industries::findOrFail($vacancy->industry_id)->name,
                'job_type' => JobType::findOrFail($vacancy->jobtype_id)->name,
                'job_level' => JobLevel::findOrFail($vacancy->joblevel_id)->name,
                'salary' => Salaries::findOrFail($vacancy->salary_id)->name,
                'interview_date' => is_null($vacancy->interview_date) ? '-' :
                    Carbon::parse($vacancy->interview_date)->format('l, j F Y'),
                'recruitmentDate_start' => is_null($vacancy->recruitmentDate_start) ? '-' :
                    Carbon::parse($vacancy->recruitmentDate_start)->format('j F Y'),
                'recruitmentDate_end' => is_null($vacancy->recruitmentDate_end) ? '-' :
                    Carbon::parse($vacancy->recruitmentDate_end)->format('j F Y'),
                'quizDate' => is_null($vacancy->quizDate_start) || is_null($vacancy->quizDate_end) ? '-' :
                    Carbon::parse($vacancy->quizDate_start)->format('j F Y') . ' - ' .
                    Carbon::parse($vacancy->quizDate_end)->format('j F Y'),
                'psychoTestDate' => is_null($vacancy->psychoTestDate_start) || is_null($vacancy->psychoTestDate_end) ?
                    '-' : Carbon::parse($vacancy->psychoTestDate_start)->format('j F Y') . ' - ' .
                    Carbon::parse($vacancy->psychoTestDate_end)->format('j F Y'),
                'total_app' => Applications::where('vacancy_id', $vacancy->id)->where('isApply', true)->count(),
                'checkDate_end' => is_null($vacancy->recruitmentDate_end) ? '-' : $vacancy->recruitmentDate_end,
                'created_at' => Carbon::parse($vacancy->created_at)->format('j F Y'),
                'updated_at' => Carbon::parse($vacancy->updated_at)->diffForHumans()
            );

            $ava['user'] = array('ava' => $filename, 'name' => $agency->name, 'id' => $vacancy->agency_id);

            $created_at = array('created_at' => Carbon::parse($row['created_at'])->format('j F Y'));
            $update_at = array('updated_at' => Carbon::parse($row['updated_at'])->diffForHumans());

            $result['data'][$i] = array_replace($ava, $result['data'][$i], $judul, $created_at, $update_at);
            $i = $i + 1;
        }

        return $result;
    }

}
