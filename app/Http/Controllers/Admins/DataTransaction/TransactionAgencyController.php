<?php

namespace App\Http\Controllers\Admins\DataTransaction;

use App\Models\Agencies;
use App\Models\Gallery;
use App\Models\Industries;
use App\Models\Vacancies;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TransactionAgencyController extends Controller
{
    public function showAgenciesTable()
    {
        $agencies = Agencies::all();

        return view('_admins.tables._transactions.agencies.agency-table', compact('agencies'));
    }

    public function createAgencies(Request $request)
    {
        if ($request->hasfile('ava')) {
            $name = $request->file('ava')->getClientOriginalName();
            $request->file('ava')->storeAs('public/admins/agencies/ava', $name);

        } else {
            $name = 'agency.png';
        }

        $agency = Agencies::create([
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
            'hari_kerja' => $request->hari_kerja,
            'jam_kerja' => $request->jam_kerja,
            'lat' => $request->lat,
            'long' => $request->long
        ]);

        Gallery::create([
            'agency_id' => $agency->id,
            'image' => $request->image,
        ]);

        return back()->with('success', '' . $request->company . ' is successfully created!');
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
        $vacancies = Vacancies::all();

        return view('_admins.tables._transactions.agencies.vacancy-table', compact('vacancies'));
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

    public function deleteVacancies($id)
    {
        $vacancy = Vacancies::find(decrypt($id));
        $vacancy->delete();

        return back()->with('success', '' . $vacancy->judul . ' is successfully deleted!');
    }

}
