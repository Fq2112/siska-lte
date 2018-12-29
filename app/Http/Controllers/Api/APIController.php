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
        $seeker = $request->seeker;
        User::firstOrCreate([
            'name' => $seeker['name'],
            'email' => $seeker['email'],
            'password' => $seeker['password'],
        ]);

        return response()->json([
            'status' => "200 OK",
            'success' => true,
            'message' => $seeker['name'] . ' is successfully created!'
        ], 200);
    }

    public function updateSeekers(Request $request)
    {
        $seeker = $request->seeker;
        $user = User::where('email', $seeker['email'])->first();
        if ($user != null) {
            $user->update([
                'password' => $seeker['new_password']
            ]);
        }

        return response()->json([
            'status' => "200 OK",
            'success' => true,
            'message' => $seeker['name'] . ' is successfully updated!'
        ], 200);
    }

    public function deleteSeekers(Request $request)
    {
        $seeker = $request->seeker;
        $user = User::where('email', $seeker['email'])->first();
        if ($user != null) {
            $user->forceDelete();
        }

        return response()->json([
            'status' => "200 OK",
            'success' => true,
            'message' => $seeker['name'] . ' is successfully deleted!'
        ], 200);
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
