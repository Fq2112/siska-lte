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

    public function syncVacancies()
    {
        $vacancies = Vacancies::where('isPost', true)->orderBy('agency_id')->get()->toArray();

        $i = 0;
        foreach ($vacancies as $vacancy) {
            $agency = array('agency_id' => Agencies::find($vacancy['agency_id'])->toArray());
            $vacancies[$i] = array_replace($vacancies[$i], $agency);
            $i = $i + 1;
        }

        $response = $this->client->post($this->uri . '/api/partners/vacancies/sync', [
            'form_params' => [
                'key' => $this->key,
                'secret' => $this->secret,
                'vacancies' => $vacancies,
            ]
        ])->getBody()->getContents();

        $response = json_decode($response, true);

        foreach ($response['data'] as $row) {
            $checkAgency = Agencies::where('email', $row['agency']['email'])->first();
            if (!$checkAgency) {
                $agency = Agencies::firstOrCreate([
                    'ava' => 'agency.png',
                    'email' => $row['agency']['email'],
                    'company' => $row['agency']['company'],
                    'kantor_pusat' => $row['agency']['kantor_pusat'],
                    'industry_id' => $row['agency']['industry_id'],
                    'tentang' => $row['agency']['tentang'],
                    'alasan' => $row['agency']['alasan'],
                    'link' => $row['agency']['link'],
                    'alamat' => $row['agency']['alamat'],
                    'phone' => $row['agency']['phone'],
                    'hari_kerja' => $row['agency']['hari_kerja'],
                    'jam_kerja' => $row['agency']['jam_kerja'],
                    'lat' => $row['agency']['lat'],
                    'long' => $row['agency']['long'],
                ]);
            } else {
                $agency = $checkAgency;
            }

            Vacancies::create([
                'judul' => $row['judul'],
                'city_id' => $row['cities_id'],
                'syarat' => $row['syarat'],
                'tanggungjawab' => $row['tanggungjawab'],
                'pengalaman' => $row['pengalaman'],
                'jobtype_id' => $row['jobtype_id'],
                'joblevel_id' => $row['joblevel_id'],
                'industry_id' => $row['industry_id'],
                'salary_id' => $row['salary_id'],
                'agency_id' => $agency->id,
                'degree_id' => $row['tingkatpend_id'],
                'major_id' => $row['jurusanpend_id'],
                'jobfunction_id' => $row['fungsikerja_id'],
                'isPost' => true,
                'recruitmentDate_start' => $row['recruitmentDate_start'],
                'recruitmentDate_end' => $row['recruitmentDate_end'],
                'interview_date' => $row['interview_date'],
            ]);
        }

        $total = count($vacancies) + count($response['data']);
        $status = $total > 1 ? $total . ' vacancies!' : 'a vacancy!';

        return response()->json([
            'status' => "200 OK",
            'success' => true,
            'message' => 'Successfully synchronized ' . $status,
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
