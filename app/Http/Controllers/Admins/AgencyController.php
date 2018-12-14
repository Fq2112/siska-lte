<?php

namespace App\Http\Controllers\Admins;

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
}
