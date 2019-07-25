<?php

namespace App\Http\Controllers\Admins;

use App\Models\Applications;
use App\Models\Admin;
use App\Models\Agencies;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vacancies;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function index()
    {
        $newUser = User::where('created_at', '>=', today()->subDays('3')->toDateTimeString())->count();
        $newApp = Applications::where('created_at', '>=', today()->subDays('3')->toDateTimeString())->count();

        $newAgency = Agencies::where('created_at', '>=', today()->subDays('3')->toDateTimeString())->count();
        $newVacancy = Vacancies::where('created_at', '>=', today()->subDays('3')->toDateTimeString())->count();

        $vacancies = Vacancies::wherenotnull('recruitmentDate_start')->wherenotnull('recruitmentDate_end')->count();
        $applications = Applications::where('isApply', true)->count();

        $admins = Admin::all();
        $users = User::all();
        $agencies = Agencies::count();

        return view('_admins.home-admin', compact('newUser', 'newApp', 'newAgency', 'newVacancy',
            'admins', 'users', 'vacancies', 'applications', 'agencies'));
    }

    public function updateProfile(Request $request)
    {
        $admin = Admin::find(Auth::guard('admin')->user()->id);
        $this->validate($request, [
            'myAva' => 'image|mimes:jpg,jpeg,gif,png|max:2048',
        ]);
        if ($request->hasFile('myAva')) {
            $name = $request->file('myAva')->getClientOriginalName();
            if ($admin->ava != '' || $admin->ava != 'avatar.png') {
                Storage::delete('public/admins/ava/' . $admin->ava);
            }
            $request->file('myAva')->storeAs('public/admins/ava', $name);

        } else {
            $name = $admin->ava;
        }
        $admin->update([
            'ava' => $name,
            'name' => $request->myName
        ]);

        return back()->with('success', 'Successfully update your profile!');
    }

    public function updateAccount(Request $request)
    {
        $admin = Admin::find(Auth::guard('admin')->user()->id);

        if (!Hash::check($request->myPassword, $admin->password)) {
            return back()->with('error', 'Your current password is incorrect!');

        } else {
            if ($request->myNew_password != $request->myPassword_confirmation) {
                return back()->with('error', 'Your password confirmation doesn\'t match!');

            } else {
                $admin->update([
                    'email' => $request->myEmail,
                    'password' => bcrypt($request->myNew_password)
                ]);
                return back()->with('success', 'Successfully update your account!');
            }
        }
    }

    /**
     * Mohon tidak melakukan perubahan apapun pada
     * kedua synchronize function berikut!
     * Terimakasih :)
     */

    public function showSynchronize()
    {
        return view('_admins.synchronize', compact('vacancies'));
    }

    public function submitSynchronize(Request $request)
    {
        $client = new Client([
            'base_uri' => env('SISKA_URI'),
            'defaults' => [
                'exceptions' => false
            ]
        ]);

        $x = 0;
        $seekers = User::where('status', true)->get();
        $arr = $seekers->toArray();
        foreach ($seekers as $seeker) {
            $acc = array('email' => $seeker->email, 'password' => $seeker->password);
            $arr[$x] = array_replace($acc, $arr[$x]);
            $x = $x + 1;
        }

        $i = 0;
        $vacancies = Vacancies::where('isPost', true)->orderBy('agency_id')->get()->toArray();
        foreach ($vacancies as $vacancy) {
            $agency = array('agency_id' => Agencies::find($vacancy['agency_id'])->toArray());
            $vacancies[$i] = array_replace($vacancies[$i], $agency);
            $i = $i + 1;
        }

        $response = $client->post(env('SISKA_URI') . '/api/partners/sync', [
            'form_params' => [
                'key' => $request->key,
                'secret' => $request->secret,
                'seekers' => $arr,
                'vacancies' => $vacancies,
            ]
        ])->getBody()->getContents();

        $response = json_decode($response, true);

        if ($response['success'] == true) {
            foreach ($response['vacancies'] as $row) {
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
                        'isSISKA' => $row['agency']['isSISKA']
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

            $totalVac = count($vacancies) + count($response['vacancies']);
            $statusVac = $totalVac > 1 ? $totalVac . ' vacancies' : 'a vacancy';
            $statusSeeker = count($arr) > 1 ? count($arr) . ' seekers' : 'a seeker';

            return 'Successfully synchronized ' . $statusSeeker . ' and ' . $statusVac . '!';
        }

        return $response['message'];
    }
}
