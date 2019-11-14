<?php

namespace App\Http\Controllers\Admins\DataMaster;

use App\Models\JobFunction;
use App\Models\Industries;
use App\Models\JobLevel;
use App\Models\JobType;
use App\Models\Salaries;
use App\Models\Degrees;
use App\Models\Majors;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RequirementsController extends Controller
{
    public function showDegreesTable()
    {
        $degrees = Degrees::all();

        return view('_admins.tables.requirements.degree-table', compact('degrees'));
    }

    public function createDegrees(Request $request)
    {
        Degrees::create(['name' => $request->name]);

        return back()->with('success', '' . $request->name . ' is successfully created!');
    }

    public function updateDegrees(Request $request)
    {
        $degree = Degrees::find($request->id);
        $degree->update(['name' => $request->name]);

        return back()->with('success', '' . $degree->name . ' is successfully updated!');
    }

    public function deleteDegrees($id)
    {
        $degree = Degrees::find(decrypt($id));
        $degree->delete();

        return back()->with('success', '' . $degree->name . ' is successfully deleted!');
    }

    public function showMajorsTable()
    {
        $majors = Majors::all();

        return view('_admins.tables.requirements.major-table', compact('majors'));
    }

    public function createMajors(Request $request)
    {
        Majors::create(['name' => $request->name]);

        return back()->with('success', '' . $request->name . ' is successfully created!');
    }

    public function updateMajors(Request $request)
    {
        $major = Majors::find($request->id);
        $major->update(['name' => $request->name]);

        return back()->with('success', '' . $major->name . ' is successfully updated!');
    }

    public function deleteMajors($id)
    {
        $major = Majors::find(decrypt($id));
        $major->delete();

        return back()->with('success', '' . $major->name . ' is successfully deleted!');
    }

    public function showIndustriesTable()
    {
        $industries = Industries::all();

        return view('_admins.tables.requirements.industry-table', compact('industries'));
    }

    public function createIndustries(Request $request)
    {
        $this->validate($request, [
            'icon' => 'required|image|mimes:jpg,jpeg,gif,png,svg|max:200',
            'nama' => 'required|string|max:191',
        ]);

        $name = $request->file('icon')->getClientOriginalName();
        $request->file('icon')->move(public_path('images/industries'), $name);

        Industries::create([
            'icon' => $name,
            'name' => $request->nama
        ]);

        return back()->with('success', '' . $request->nama . ' is successfully created!');
    }

    public function updateIndustries(Request $request)
    {
        $industry = Industries::find($request->id);

        $this->validate($request, [
            'icon' => 'image|mimes:jpg,jpeg,gif,png,svg|max:200',
            'nama' => 'required|string|max:191',
        ]);

        if ($request->hasfile('icon')) {
            $name = $request->file('icon')->getClientOriginalName();
            if ($industry->icon != '') {
                unlink(public_path('images/industries/' . $industry->icon));
            }
            $request->file('icon')->move(public_path('images/industries'), $name);

        } else {
            $name = $industry->icon;
        }

        $industry->update([
            'icon' => $name,
            'name' => $request->nama
        ]);

        return back()->with('success', '' . $industry->nama . ' is successfully updated!');
    }

    public function deleteIndustries($id)
    {
        $industry = Industries::find(decrypt($id));
        if ($industry->icon != '') {
            unlink(public_path('images/industries/' . $industry->icon));
        }
        $industry->delete();

        return back()->with('success', '' . $industry->nama . ' is successfully deleted!');
    }

    public function showJobFunctionsTable()
    {
        $functions = JobFunction::all();

        return view('_admins.tables.requirements.jobFunction-table', compact('functions'));
    }

    public function createJobFunctions(Request $request)
    {
        JobFunction::create(['name' => $request->nama]);

        return back()->with('success', '' . $request->nama . ' is successfully created!');
    }

    public function updateJobFunctions(Request $request)
    {
        $function = JobFunction::find($request->id);
        $function->update(['name' => $request->nama]);

        return back()->with('success', '' . $function->name . ' is successfully updated!');
    }

    public function deleteJobFunctions($id)
    {
        $function = JobFunction::find(decrypt($id));
        $function->delete();

        return back()->with('success', '' . $function->name . ' is successfully deleted!');
    }

    public function showJobLevelsTable()
    {
        $levels = JobLevel::all();

        return view('_admins.tables.requirements.jobLevel-table', compact('levels'));
    }

    public function createJobLevels(Request $request)
    {
        JobLevel::create(['name' => $request->nama]);

        return back()->with('success', '' . $request->nama . ' is successfully created!');
    }

    public function updateJobLevels(Request $request)
    {
        $level = JobLevel::find($request->id);
        $level->update(['name' => $request->nama]);

        return back()->with('success', '' . $level->name . ' is successfully updated!');
    }

    public function deleteJobLevels($id)
    {
        $level = JobLevel::find(decrypt($id));
        $level->delete();

        return back()->with('success', '' . $level->name . ' is successfully deleted!');
    }

    public function showJobTypesTable()
    {
        $types = JobType::all();

        return view('_admins.tables.requirements.jobType-table', compact('types'));
    }

    public function createJobTypes(Request $request)
    {
        JobType::create(['name' => $request->nama]);

        return back()->with('success', '' . $request->nama . ' is successfully created!');
    }

    public function updateJobTypes(Request $request)
    {
        $type = JobType::find($request->id);
        $type->update(['name' => $request->nama]);

        return back()->with('success', '' . $type->name . ' is successfully updated!');
    }

    public function deleteJobTypes($id)
    {
        $type = JobType::find(decrypt($id));
        $type->delete();

        return back()->with('success', '' . $type->name . ' is successfully deleted!');
    }

    public function showSalariesTable()
    {
        $salaries = Salaries::all();

        return view('_admins.tables.requirements.salary-table', compact('salaries'));
    }

    public function createSalaries(Request $request)
    {
        Salaries::create(['name' => $request->nama]);

        return back()->with('success', '' . $request->nama . ' is successfully created!');
    }

    public function updateSalaries(Request $request)
    {
        $salary = Salaries::find($request->id);
        $salary->update(['name' => $request->nama]);

        return back()->with('success', '' . $salary->name . ' is successfully updated!');
    }

    public function deleteSalaries($id)
    {
        $salary = Salaries::find(decrypt($id));
        $salary->delete();

        return back()->with('success', '' . $salary->name . ' is successfully deleted!');
    }
}
