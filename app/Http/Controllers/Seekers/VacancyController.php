<?php

namespace App\Http\Controllers\Seekers;

use App\Models\Applications;
use App\Models\Agencies;
use App\Models\Education;
use App\Models\Provinces;
use App\Models\User;
use App\Models\Vacancies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class VacancyController extends Controller
{
    public function __construct()
    {
        $this->middleware('seeker')->except(['detailAgency', 'detailVacancy']);
        $this->middleware('seeker.home')->only(['detailAgency', 'detailVacancy']);
    }

    public function detailAgency($id)
    {
        $agency = Agencies::findOrFail($id);
        $industry = $agency->getIndustry;
        $vacancies = Vacancies::where('agency_id', $agency->id)->orderByDesc('updated_at')->get();

        return view('_admins.detail-agency', compact('agency', 'industry', 'vacancies'));
    }

    public function detailVacancy($id)
    {
        $provinces = Provinces::all();
        $vacancy = Vacancies::findOrFail($id);
        $agency = $vacancy->getAgency;
        $city = substr($vacancy->getCity->name, 0, 2) == "Ko" ? substr($vacancy->getCity->name, 5) :
            substr($vacancy->getCity->name, 10);
        $salary = $vacancy->getSalary;
        $jobfunc = $vacancy->getJobFunction;
        $joblevel = $vacancy->getJobLevel;
        $jobtype = $vacancy->getJobType;
        $industry = $vacancy->getIndustry;
        $degree = $vacancy->getDegree;
        $major = $vacancy->getMajor;
        $applicants = Applications::where('vacancy_id', $vacancy->id)->where('isApply', true)->count();

        return view('_admins.detail-vacancy', compact('provinces', 'vacancy', 'agency',
            'city', 'salary', 'jobfunc', 'joblevel', 'jobtype', 'industry', 'degree', 'major', 'applicants'));
    }

    public function bookmarkVacancy(Request $request)
    {
        $vacancy = Vacancies::find($request->vacancy_id);
        $user = Auth::user();

        $acc = Applications::where('vacancy_id', $vacancy->id)->where('user_id', $user->id);

        if (!$acc->count()) {
            Applications::create([
                'user_id' => $user->id,
                'vacancy_id' => $vacancy->id,
                'isBookmark' => true,
            ]);

            return back()->with('vacancy', '' . $vacancy->judul . ' is successfully bookmarked!');

        } else {
            if ($acc->first()->isBookmark == true) {
                $acc->first()->update(['isBookmark' => false]);

                if ($acc->first()->isApply == false) {
                    $acc->first()->delete();
                }

                return back()->with('vacancy', '' . $vacancy->judul . ' is successfully unmarked!');

            } else {
                $acc->first()->update(['isBookmark' => true]);

                return back()->with('vacancy', '' . $vacancy->judul . ' is successfully bookmarked!');
            }
        }
    }

    public function applyVacancy(Request $request)
    {
        $vacancy = Vacancies::find($request->vacancy_id);
        $user = Auth::user();

        $acc = Applications::where('vacancy_id', $vacancy->id)->where('user_id', $user->id);

        if (count($acc->get()) == 0) {
            Applications::create([
                'user_id' => $user->id,
                'vacancy_id' => $vacancy->id,
                'isApply' => true,
            ]);

            return back()->with('vacancy', '' . $vacancy->judul . ' is successfully applied! Please check Application Status in your Dashboard.');

        } else {
            if ($acc->first()->isApply == true) {
                $acc->first()->update(['isApply' => false]);

                if ($acc->first()->isBookmark == false) {
                    $acc->first()->delete();
                }

                return back()->with('vacancy', 'Application for ' . $vacancy->judul . ' is successfully aborted!');

            } else {
                $acc->first()->update(['isApply' => true]);

                return back()->with('vacancy', '' . $vacancy->judul . ' is successfully applied! Please check Application Status in your Dashboard.');
            }
        }
    }

    public function getVacancyRequirement($id)
    {
        $vacancy = Vacancies::find($id);
        $user = User::find(Auth::user()->id);
        $edu = $user->getEducation->count();
        $exp = $user->getExperience->count();

        $reqExp = filter_var($vacancy->pengalaman, FILTER_SANITIZE_NUMBER_INT);
        $checkEdu = Education::where('user_id', $user->id)->where('degree_id', '>=', $vacancy->degree_id)
            ->wherenotnull('end_period')->count();

        if ($edu == 0 || $exp == 0 || $user->phone == "" || $user->address == "" || $user->birthday == "" ||
            $user->gender == "") {
            return 0;
        } else {
            if ($user->total_exp < $reqExp) {
                return 1;
            } elseif ($checkEdu < 1) {
                return 2;
            } else {
                return 3;
            }
        }

    }
}
