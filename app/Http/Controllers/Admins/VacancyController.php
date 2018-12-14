<?php

namespace App\Http\Controllers\Admins;

use App\Models\Vacancies;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VacancyController extends Controller
{
    public function showVacanciesTable()
    {
        $vacancies = Vacancies::orderByDesc('id')->get();

        return view('_admins.vacancy-setup', compact('vacancies'));
    }

    public function createVacancies(Request $request)
    {
        Vacancies::create([
            'judul' => $request->judul,
            'city_id' => $request->city_id,
            'syarat' => $request->syarat,
            'tanggungjawab' => $request->tanggungjawab,
            'pengalaman' => $request->pengalaman,
            'jobtype_id' => $request->jobtype_id,
            'joblevel_id' => $request->joblevel_id,
            'industry_id' => $request->industry_id,
            'salary_id' => $request->salary_id,
            'agency_id' => $request->agency_id,
            'degree_id' => $request->degree_id,
            'major_id' => $request->major_id,
            'jobfunction_id' => $request->jobfunction_id,
            'recruitmentDate_start' => $request->recruitmentDate_start,
            'recruitmentDate_end' => $request->recruitmentDate_end,
            'interview_date' => $request->interview_date,
        ]);

        return back()->with('success', '' . $request->judul . ' is successfully created!');
    }

    public function editVacancies($id)
    {
        $findVacancy = Vacancies::find($id);
        return $findVacancy;
    }

    public function updateVacancies(Request $request)
    {
        $vacancy = Vacancies::find($request->id);
        $vacancy->update([
            'judul' => $request->judul,
            'city_id' => $request->city_id,
            'syarat' => $request->syarat,
            'tanggungjawab' => $request->tanggungjawab,
            'pengalaman' => $request->pengalaman,
            'jobtype_id' => $request->jobtype_id,
            'joblevel_id' => $request->joblevel_id,
            'industry_id' => $request->industry_id,
            'salary_id' => $request->salary_id,
            'agency_id' => $request->agency_id,
            'degree_id' => $request->degree_id,
            'major_id' => $request->major_id,
            'jobfunction_id' => $request->jobfunction_id,
            'recruitmentDate_start' => $request->recruitmentDate_start,
            'recruitmentDate_end' => $request->recruitmentDate_end,
            'interview_date' => $request->interview_date,
        ]);

        return back()->with('success', '' . $request->judul . ' is successfully updated!');
    }

    public function deleteVacancies($id)
    {
        $vacancy = Vacancies::find(decrypt($id));
        $vacancy->delete();

        return back()->with('success', '' . $vacancy->judul . ' is successfully deleted!');
    }
}
