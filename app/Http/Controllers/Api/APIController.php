<?php

namespace App\Http\Controllers\Api;

use App\Models\Cities;
use App\Models\JobFunction;
use App\Models\Industries;
use App\Models\JobLevel;
use App\Models\JobType;
use App\Models\Salaries;
use App\Models\Degrees;
use App\Models\Majors;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Models\Agencies;
use App\Models\Vacancies;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class APIController extends Controller
{
    protected $key, $secret, $client, $uri;

    public function __construct()
    {
        $this->key = env('SISKA_API_KEY');
        $this->secret = env('SISKA_API_SECRET');
        $this->uri = 'http://localhost:8000';

        $this->client = new Client([
            'base_uri' => $this->uri,
            'defaults' => [
                'exceptions' => false
            ]
        ]);
    }

    public function getCredentials()
    {
        $response = $this->client->get($this->uri . '/api/partners?key=' . $this->key . '&secret=' . $this->secret);

        return json_decode($response->getBody(), true);
    }

    public function createSeekers(Request $request)
    {
        $data = $request->seeker;
        $checkSeeker = User::where('email', $data['email'])->first();
        if (!$checkSeeker) {
            User::firstOrCreate([
                'ava' => 'seeker.png',
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
            ]);
        }
    }

    public function seekersSocialite($provider, Request $request)
    {
        $data = $request->seeker;
        $checkSeeker = User::where('email', $data['email'])->first();
        if (!$checkSeeker) {
            $user = User::firstOrCreate([
                'ava' => 'seeker.png',
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
            ]);

            $user->socialProviders()->create([
                'provider_id' => $data['provider_id'],
                'provider' => $provider
            ]);
        }
    }

    public function updateSeekers(Request $request)
    {
        $data = $request->seeker;
        $user = User::where('email', $data['email'])->first();
        if ($user != null) {
            if ($request->check_form == 'password') {
                $user->update(['password' => $data['password']]);

            } elseif ($request->check_form == 'contact') {
                $user->update([
                    'phone' => $data['input']['phone'],
                    'address' => $data['input']['address'],
                    'zip_code' => $data['input']['zip_code'],
                ]);

            } elseif ($request->check_form == 'personal') {
                $user->update([
                    'name' => $data['input']['name'],
                    'birthday' => $data['input']['birthday'],
                    'gender' => $data['input']['gender'],
                    'relationship' => $data['input']['relationship'],
                    'nationality' => $data['input']['nationality'],
                    'website' => $data['input']['website'],
                    'lowest_salary' => str_replace(',', '', $data['input']['lowest']),
                    'highest_salary' => str_replace(',', '', $data['input']['highest']),
                ]);

            } elseif ($request->check_form == 'summary') {
                $user->update(['summary' => $data['summary']]);
            }
        }
    }

    public function deleteSeekers(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user != null) {
            $user->forceDelete();
        }
    }

    public function createVacancies(Request $request)
    {
        $vacancies = $request->vacancies;
        foreach ($vacancies as $data) {
            $checkAgency = Agencies::where('email', $data['agency']['email'])->first();
            if (!$checkAgency) {
                $agency = Agencies::firstOrCreate([
                    'ava' => 'agency.png',
                    'email' => $data['agency']['email'],
                    'company' => $data['agency']['company'],
                    'kantor_pusat' => $data['agency']['kantor_pusat'],
                    'industry_id' => $data['agency']['industry_id'],
                    'tentang' => $data['agency']['tentang'],
                    'alasan' => $data['agency']['alasan'],
                    'link' => $data['agency']['link'],
                    'alamat' => $data['agency']['alamat'],
                    'phone' => $data['agency']['phone'],
                    'hari_kerja' => $data['agency']['hari_kerja'],
                    'jam_kerja' => $data['agency']['jam_kerja'],
                    'lat' => $data['agency']['lat'],
                    'long' => $data['agency']['long'],
                    'isSISKA' => $data['agency']['isSISKA']
                ]);
            } else {
                $agency = $checkAgency;
            }

            Vacancies::create([
                'judul' => $data['judul'],
                'city_id' => $data['cities_id'],
                'syarat' => $data['syarat'],
                'tanggungjawab' => $data['tanggungjawab'],
                'pengalaman' => $data['pengalaman'],
                'jobtype_id' => $data['jobtype_id'],
                'joblevel_id' => $data['joblevel_id'],
                'industry_id' => $data['industry_id'],
                'salary_id' => $data['salary_id'],
                'agency_id' => $agency->id,
                'degree_id' => $data['tingkatpend_id'],
                'major_id' => $data['jurusanpend_id'],
                'jobfunction_id' => $data['fungsikerja_id'],
                'isPost' => true,
            ]);
        }
    }

    public function updateVacancies(Request $request)
    {
        $data = $request->agencies;
        $agency = Agencies::where('email', $data['email'])->first();
        if ($agency != null) {
            if ($request->check_form == 'personal_data') {
                $agency->update([
                    'company' => $data['input']['name'],
                    'kantor_pusat' => $data['input']['kantor_pusat'],
                    'industry_id' => $data['input']['industri_id'],
                    'link' => $data['input']['link'],
                    'phone' => $data['input']['phone'],
                    'hari_kerja' => $data['input']['start_day'] . ' - ' . $data['input']['end_day'],
                    'jam_kerja' => $data['input']['start_time'] . ' - ' . $data['input']['end_time'],
                ]);

            } elseif ($request->check_form == 'address') {
                $agency->update([
                    'alamat' => $data['input']['alamat'],
                    'lat' => $data['input']['lat'],
                    'long' => $data['input']['long'],
                ]);

            } elseif ($request->check_form == 'about') {
                $agency->update([
                    'tentang' => $data['input']['tentang'],
                    'alasan' => $data['input']['alasan'],
                ]);

            } elseif ($request->check_form == 'vacancy') {
                $vacancy = Vacancies::where('agency_id', $agency->id)->where('judul', $data['judul'])->first();
                if ($vacancy != null) {
                    $vacancy->update([
                        'judul' => $data['input']['judul'],
                        'city_id' => $data['input']['cities_id'],
                        'syarat' => $data['input']['syarat'],
                        'tanggungjawab' => $data['input']['tanggungjawab'],
                        'pengalaman' => $data['input']['pengalaman'],
                        'jobtype_id' => $data['input']['jobtype_id'],
                        'joblevel_id' => $data['input']['joblevel_id'],
                        'industry_id' => $data['input']['industri_id'],
                        'salary_id' => $data['input']['salary_id'],
                        'degree_id' => $data['input']['tingkatpend_id'],
                        'major_id' => $data['input']['jurusanpend_id'],
                        'jobfunction_id' => $data['input']['fungsikerja_id'],
                    ]);
                }

            } elseif ($request->check_form == 'schedule') {
                $vacancy = Vacancies::where('agency_id', $agency->id)->where('judul', $data['judul'])->first();
                if ($vacancy != null) {
                    $vacancy->update([
                        'recruitmentDate_start' => $data['input']['isPost'] == 1 ? $data['input']['recruitmentDate_start'] : null,
                        'recruitmentDate_end' => $data['input']['isPost'] == 1 ? $data['input']['recruitmentDate_end'] : null,
                        'interview_date' => $data['input']['isPost'] == 1 ? $data['input']['interview_date'] : null,
                    ]);
                }
            }
        }
    }

    public function deleteVacancies(Request $request)
    {
        $data = $request->agencies;
        $agency = Agencies::where('email', $data['email'])->first();
        if ($agency != null) {
            if ($request->check_form == 'agency') {
                $agency->delete();

            } elseif ($request->check_form == 'vacancy') {
                $vacancy = Vacancies::where('agency_id', $agency->id)->where('judul', $data['judul'])->first();
                if ($vacancy != null) {
                    if ($vacancy->getAgency->getVacancy->count() > 0) {
                        $vacancy->delete();
                    } else {
                        $agency->delete();
                    }
                }
            }
        }
    }

    public function getSearchResult(Request $request)
    {
        $input = $request->all();

        if ($request->has(['q']) || $request->has(['loc'])) {
            $keyword = $input['q'];
            $location = $input['loc'];

            $city = Cities::where('name', 'like', '%' . $location . '%')->get()->pluck('id')->toArray();
            $agency = Agencies::where('company', 'like', '%' . $keyword . '%')->get()->pluck('id')->toArray();

            $result = Vacancies::where('judul', 'like', '%' . $keyword . '%')->whereIn('city_id', $city)
                ->where('isPost', true)
                ->orwhereIn('agency_id', $agency)->whereIn('city_id', $city)
                ->where('isPost', true)->paginate(12)->toArray();

        } else {
            $result = Vacancies::where('isPost', true)->paginate(12)->toArray();
        }

        $i = 0;
        foreach ($result['data'] as $row) {
            $cities = substr(Cities::find($row['city_id'])->name, 0, 2) == "Ko" ?
                substr(Cities::find($row['city_id'])->name, 5) :
                substr(Cities::find($row['city_id'])->name, 10);

            $agency = Agencies::findOrFail($row['agency_id']);

            $filename = "agency.png" || $agency->ava == "" ? asset('images/agency.png') :
                asset('storage/admins/agencies/' . $agency->ava);

            $city = array('city' => $cities);
            $degrees = array('degrees' => Degrees::findOrFail($row['degree_id'])->name);
            $majors = array('majors' => Majors::findOrFail($row['major_id'])->name);
            $jobfunc = array('job_func' => JobFunction::findOrFail($row['jobfunction_id'])->name);
            $industry = array('industry' => Industries::findOrFail($row['industry_id'])->name);
            $jobtype = array('job_type' => JobType::findOrFail($row['jobtype_id'])->name);
            $joblevel = array('job_level' => JobLevel::findOrFail($row['joblevel_id'])->name);
            $salary = array('salary' => Salaries::findOrFail($row['salary_id'])->name);
            $ava['user'] = array('ava' => $filename, 'name' => $agency->company);
            $update_at = array('updated_at' => Carbon::createFromFormat('Y-m-d H:i:s', $row['updated_at'])
                ->diffForHumans());

            $result['data'][$i] = array_replace($ava, $result['data'][$i], $city, $degrees, $majors, $jobfunc,
                $industry, $jobtype, $joblevel, $salary, $update_at);

            $i = $i + 1;
        }

        return $result;
    }
}
