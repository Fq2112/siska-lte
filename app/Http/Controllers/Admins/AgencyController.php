<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Api\APIController as Credential;
use App\Models\Vacancies;
use App\Models\Agencies;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AgencyController extends Controller
{
    protected $key, $secret, $client, $uri;

    public function __construct()
    {
        $this->key = env('SISKA_API_KEY');
        $this->secret = env('SISKA_API_SECRET');
        $this->uri = env('SISKA_URI');

        $this->client = new Client([
            'base_uri' => $this->uri,
            'defaults' => [
                'exceptions' => false
            ]
        ]);
    }

    public function showAgenciesTable()
    {
        $agencies = Agencies::where('isSISKA', false)->get();

        return view('_admins.agency-setup', compact('agencies'));
    }

    public function createAgencies(Request $request)
    {
        $address = str_replace(" ", "+", $request->address);
        $json = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=" .
            $address . "&key=AIzaSyBIljHbKjgtTrpZhEiHum734tF1tolxI68");

        $request->request->add([
            'lat' => json_decode($json)->{'results'}[0]->{'geometry'}->{'location'}->{'lat'},
            'long' => json_decode($json)->{'results'}[0]->{'geometry'}->{'location'}->{'lng'}
        ]);

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
            'lat' => $request->lat,
            'long' => $request->long
        ]);

        return back()->with('success', '' . $request->company . ' is successfully created!');
    }

    public function editAgencies($id)
    {
        $findAgency = Agencies::where('isSISKA', false)->where('id', $id)->firstOrFail();
        return $findAgency;
    }

    public function updateAgencies(Request $request)
    {
        $agency = Agencies::where('isSISKA', false)->where('id', $request->id)->firstOrFail();

        $address = str_replace(" ", "+", $request->address);
        $json = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=" .
            $address . "&key=AIzaSyBIljHbKjgtTrpZhEiHum734tF1tolxI68");

        $request->request->add([
            'lat' => json_decode($json)->{'results'}[0]->{'geometry'}->{'location'}->{'lat'},
            'long' => json_decode($json)->{'results'}[0]->{'geometry'}->{'location'}->{'lng'}
        ]);

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

        $response = app(Credential::class)->getCredentials();
        if ($response['isSync'] == true) {
            $this->client->put($this->uri . '/api/partners/vacancies/agency/update', [
                'form_params' => [
                    'key' => $this->key,
                    'secret' => $this->secret,
                    'agency' => $agency->toArray(),
                    'data' => $request->toArray(),
                ]
            ]);
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
            'lat' => $request->lat,
            'long' => $request->long
        ]);

        return back()->with('success', '' . $request->company . ' is successfully updated!');
    }

    public function deleteAgencies($id)
    {
        $agency = Agencies::where('isSISKA', false)->where('id', decrypt($id))->firstOrFail();

        if ($agency->ava != '' || $agency->ava != 'agency.png') {
            Storage::delete('public/admins/agencies/ava/' . $agency->ava);
        }

        $agency->delete();

        $response = app(Credential::class)->getCredentials();
        if ($response['isSync'] == true) {
            $this->client->delete($this->uri . '/api/partners/vacancies/agency/delete', [
                'form_params' => [
                    'key' => $this->key,
                    'secret' => $this->secret,
                    'agency' => $agency->toArray(),
                ]
            ]);
        }

        return back()->with('success', '' . $agency->company . ' is successfully deleted!');
    }

    public function showVacanciesTable()
    {
        $vacancies = Vacancies::whereHas('getAgency', function ($q) {
            $q->where('isSISKA', false);
        })->get();

        return view('_admins.vacancy-setup', compact('vacancies'));
    }

    public function createVacancies(Request $request)
    {
        $vacancy = Vacancies::create([
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
            'isPost' => true,
            'recruitmentDate_start' => $request->recruitmentDate_start,
            'recruitmentDate_end' => $request->recruitmentDate_end,
            'interview_date' => $request->interview_date,
        ]);

        $response = app(Credential::class)->getCredentials();
        if ($response['isSync'] == true) {
            $this->client->post($this->uri . '/api/partners/vacancies/create', [
                'form_params' => [
                    'key' => $this->key,
                    'secret' => $this->secret,
                    'vacancy' => $vacancy->toArray(),
                    'agency' => $vacancy->getAgency->toArray(),
                ]
            ]);
        }

        return back()->with('success', '' . $vacancy->judul . ' is successfully created!');
    }

    public function editVacancies($id)
    {
        $findVacancy = Vacancies::whereHas('getAgency', function ($q) {
            $q->where('isSISKA', false);
        })->where('id', $id)->firstOrFail();

        return $findVacancy;
    }

    public function updateVacancies(Request $request)
    {
        $vacancy = Vacancies::whereHas('getAgency', function ($q) {
            $q->where('isSISKA', false);
        })->where('id', $request->id)->firstOrFail();

        $response = app(Credential::class)->getCredentials();
        if ($response['isSync'] == true) {
            $this->client->put($this->uri . '/api/partners/vacancies/update', [
                'form_params' => [
                    'key' => $this->key,
                    'secret' => $this->secret,
                    'agency' => $vacancy->getAgency->toArray(),
                    'vacancy' => $vacancy->toArray(),
                    'data' => $request->toArray(),
                ]
            ]);
        }
        if ($request->isPost == 1) {
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
                'degree_id' => $request->degree_id,
                'major_id' => $request->major_id,
                'jobfunction_id' => $request->jobfunction_id,
                'isPost' => $request->isPost,
                'recruitmentDate_start' => $request->recruitmentDate_start,
                'recruitmentDate_end' => $request->recruitmentDate_end,
                'interview_date' => $request->interview_date,
            ]);
        } else {
            $vacancy->update([
                'isPost' => $request->isPost,
                'recruitmentDate_start' => null,
                'recruitmentDate_end' => null,
                'interview_date' => null,
            ]);
        }

        return back()->with('success', '' . $vacancy->judul . ' is successfully updated!');
    }

    public function deleteVacancies($id)
    {
        $vacancy = Vacancies::whereHas('getAgency', function ($q) {
            $q->where('isSISKA', false);
        })->where('id', decrypt($id))->firstOrFail();
        $vacancy->delete();

        $response = app(Credential::class)->getCredentials();
        if ($response['isSync'] == true) {
            if ($vacancy->getAgency->getVacancy->count() > 0) {
                $this->client->delete($this->uri . '/api/partners/vacancies/delete', [
                    'form_params' => [
                        'key' => $this->key,
                        'secret' => $this->secret,
                        'agency' => $vacancy->getAgency->toArray(),
                        'vacancy' => $vacancy->toArray(),
                    ]
                ]);

            } else {
                $this->client->delete($this->uri . '/api/partners/vacancies/agency/delete', [
                    'form_params' => [
                        'key' => $this->key,
                        'secret' => $this->secret,
                        'agency' => $vacancy->getAgency->toArray(),
                    ]
                ]);
            }
        }

        return back()->with('success', '' . $vacancy->judul . ' is successfully deleted!');
    }
}
