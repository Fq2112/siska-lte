<?php

namespace App\Http\Controllers\Admins\DataMaster;

use App\Models\Cities;
use App\Models\Nations;
use App\Models\Provinces;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WebContentsController extends Controller
{
    public function showNationsTable()
    {
        $nations = Nations::all();

        return view('_admins.tables.webContents.nation-table', compact('nations'));
    }

    public function createNations(Request $request)
    {
        Nations::create(['name' => $request->name]);

        return back()->with('success', '' . $request->name . ' is successfully created!');
    }

    public function updateNations(Request $request)
    {
        $nation = Nations::find($request->id);
        $nation->update(['name' => $request->name]);

        return back()->with('success', '' . $nation->name . ' is successfully updated!');
    }

    public function deleteNations($id)
    {
        $nation = Nations::find(decrypt($id));
        $nation->delete();

        return back()->with('success', '' . $nation->name . ' is successfully deleted!');
    }

    public function showProvincesTable()
    {
        $provinces = Provinces::all();

        return view('_admins.tables.webContents.province-table', compact('provinces'));
    }

    public function createProvinces(Request $request)
    {
        Provinces::create(['name' => $request->name]);

        return back()->with('success', '' . $request->name . ' is successfully created!');
    }

    public function updateProvinces(Request $request)
    {
        $province = Provinces::find($request->id);
        $province->update(['name' => $request->name]);

        return back()->with('success', '' . $province->name . ' is successfully updated!');
    }

    public function deleteProvinces($id)
    {
        $province = Provinces::find(decrypt($id));
        $province->delete();

        return back()->with('success', '' . $province->name . ' is successfully deleted!');
    }

    public function showCitiesTable()
    {
        $cities = Cities::all();

        return view('_admins.tables.webContents.city-table', compact('cities'));
    }

    public function createCities(Request $request)
    {
        Cities::create([
            'province_id' => $request->province_id,
            'name' => $request->name
        ]);

        return back()->with('success', '' . $request->name . ' is successfully created!');
    }

    public function updateCities(Request $request)
    {
        $city = Cities::find($request->id);
        $city->update([
            'province_id' => $request->province_id,
            'name' => $request->name
        ]);

        return back()->with('success', '' . $city->name . ' is successfully updated!');
    }

    public function deleteCities($id)
    {
        $city = Cities::find(decrypt($id));
        $city->delete();

        return back()->with('success', '' . $city->name . ' is successfully deleted!');
    }
}
