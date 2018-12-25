<?php

namespace App\Http\Controllers\Admins;

use App\Models\Applications;
use App\Models\Admin;
use App\Models\Agencies;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vacancies;
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
                    'password' => bcrypt($request->myPassword)
                ]);
                return back()->with('success', 'Successfully update your account!');
            }
        }
    }

    public function showSynchronize()
    {
        return view('_admins.synchronize', compact('vacancies'));
    }

}
