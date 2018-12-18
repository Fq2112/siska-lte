<?php

namespace App\Http\Controllers\Admins;

use App\Models\Vacancies;
use App\Models\Agencies;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AgencyController extends Controller
{
    public function showAgenciesTable()
    {
        $agencies = Agencies::orderByDesc('id')->get();

        return view('_admins.agency-setup', compact('agencies'));
    }

    public function createAgencies(Request $request)
    {
        $address = str_replace(" ", "+", $request->address);
        $json = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=" .
            $address . "&key=AIzaSyBIljHbKjgtTrpZhEiHum734tF1tolxI68");

        $lat = json_decode($json)->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
        $long = json_decode($json)->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};

        if ($request->hasfile('ava')) {
            $this->validate($request, [
                'ava' => 'image|mimes:jpg,jpeg,gif,png|max:2048',
            ]);

            $name = $request->file('ava')->getClientOriginalName();
            $request->file('ava')->storeAs('public/admins/agencies/ava', $name);

        } else {
            $name = 'agency.png';
        }

        Agencies::create([
            'ava' => $name,
            'email' => $request->email,
            'company' => $request->company,
            'kantor_pusat' => $request->kantor_pusat,
            'industry_id' => $request->industry_id,
            'tentang' => $request->tentang,
            'alasan' => $request->alasan,
            'link' => $request->link,
            'alamat' => $request->address,
            'phone' => $request->phone,
            'hari_kerja' => $request->start_day . ' - ' . $request->end_day,
            'jam_kerja' => $request->start_time . ' - ' . $request->end_time,
            'lat' => $lat,
            'long' => $long
        ]);

        return back()->with('success', '' . $request->company . ' is successfully created!');
    }

    public function editAgencies($id)
    {
        $findAgency = Agencies::find($id);
        return $findAgency;
    }

    public function updateAgencies(Request $request)
    {
        $agency = Agencies::find($request->id);

        $address = str_replace(" ", "+", $request->address);
        $json = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=" .
            $address . "&key=AIzaSyBIljHbKjgtTrpZhEiHum734tF1tolxI68");

        $lat = json_decode($json)->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
        $long = json_decode($json)->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};

        if ($request->hasFile('ava')) {
            $this->validate($request, [
                'ava' => 'image|mimes:jpg,jpeg,gif,png|max:2048',
            ]);

            $name = $request->file('ava')->getClientOriginalName();
            if ($agency->ava != '' || $agency->ava != 'agency.png') {
                Storage::delete('public/admins/agencies/ava/' . $agency->ava);
            }
            $request->file('ava')->storeAs('public/admins/agencies/ava', $name);

        } else {
            $name = $agency->ava;
        }

        $agency->update([
            'ava' => $name,
            'email' => $request->email,
            'company' => $request->company,
            'kantor_pusat' => $request->kantor_pusat,
            'industry_id' => $request->industry_id,
            'tentang' => $request->tentang,
            'alasan' => $request->alasan,
            'link' => $request->link,
            'alamat' => $request->address,
            'phone' => $request->phone,
            'hari_kerja' => $request->start_day . ' - ' . $request->end_day,
            'jam_kerja' => $request->start_time . ' - ' . $request->end_time,
            'lat' => $lat,
            'long' => $long
        ]);

        return back()->with('success', '' . $request->company . ' is successfully updated!');
    }

    public function deleteAgencies($id)
    {
        $agency = Agencies::find(decrypt($id));
        if ($agency->ava != '' || $agency->ava != 'agency.png') {
            Storage::delete('public/admins/agencies/ava/' . $agency->ava);
        }
        $agency->delete();

        return back()->with('success', '' . $agency->company . ' is successfully deleted!');
    }

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
        if ($request->check_form == 'vacancy') {
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

        } elseif ($request->check_form == 'schedule') {
            $vacancy->update([
                'isPost' => $request->isPost,
                'recruitmentDate_start' => $request->isPost == 1 ? $request->recruitmentDate_start : null,
                'recruitmentDate_end' => $request->isPost == 1 ? $request->recruitmentDate_end : null,
                'interview_date' => $request->isPost == 1 ? $request->interview_date : null,
            ]);
        }

        return back()->with('success', '' . $vacancy->judul . ' is successfully updated!');
    }

    public function deleteVacancies($id)
    {
        $vacancy = Vacancies::find(decrypt($id));
        $vacancy->delete();

        return back()->with('success', '' . $vacancy->judul . ' is successfully deleted!');
    }

}
