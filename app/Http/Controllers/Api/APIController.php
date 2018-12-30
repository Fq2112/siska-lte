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
