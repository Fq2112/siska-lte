<?php

namespace App\Http\Controllers\Admins\DataTransaction;

use ZipArchive;
use App\Models\Applications;
use App\Models\Vacancies;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class TransactionSeekerController extends Controller
{
    public function showApplicationsTable(Request $request)
    {
        $vacancies = Vacancies::wherenotnull('recruitmentDate_start')->wherenotnull('recruitmentDate_end')->get();

        $applications = Applications::whereHas('getVacancy', function ($q) {
            $q->wherenotnull('recruitmentDate_start')->wherenotnull('recruitmentDate_end');
        })->where('isApply', true)->get();

        $findVac = $request->q;

        return view('_admins.tables._transactions.seekers.application-table', compact('vacancies',
            'applications', 'findVac'));
    }

    public function massGeneratePDF(Request $request)
    {
        $ids = explode(",", $request->applicant_ids);
        $vacancies = Vacancies::whereHas('getApplication', function ($acc) use ($ids) {
            $acc->whereIn('id', $ids);
        })->get();

        $files = [];
        $i = 0;
        foreach ($vacancies as $vacancy) {
            $applicants = Applications::where('vacancy_id', $vacancy->id)->where('isApply', true)->get()->toArray();
            $date = Carbon::parse($vacancy->recruitmentDate_start)->format('dmy') . '-' .
                Carbon::parse($vacancy->recruitmentDate_end)->format('dmy');

            $filename = 'ApplicationList_' . str_replace(' ', '_', $vacancy->judul) . '_' . $date . '.pdf';
            $pdf = PDF::loadView('reports.applicantList-pdf', compact('applicants', 'vacancy'));
            Storage::put('public/admins/agencies/reports/applications/' . $filename, $pdf->output());

            $files[$i] = $filename;
            $i = $i + 1;
        }

        $public_dir = public_path();
        $zipFileName = 'PDFs.zip';
        $zip = new ZipArchive;
        if ($zip->open($public_dir . '/' . $zipFileName, ZipArchive::CREATE) === TRUE) {
            foreach ($files as $file) {
                $zip->addFile(public_path('storage/admins/agencies/reports/applications/' . $file), $file);
            }
            $zip->close();
        }
        $headers = array('Content-Type' => 'application/octet-stream');
        $filetopath = $public_dir . '/' . $zipFileName;
        if (!file_exists($filetopath)) {
            return 0;
        } else {
            return response()->download($filetopath, $zipFileName, $headers)->deleteFileAfterSend(true);
        }
    }

    public function massDeleteApplications(Request $request)
    {
        $applicants = Applications::whereIn('id', explode(",", $request->applicant_ids))->get();

        foreach ($applicants as $applicant) {
            $applicant->delete();
        }

        return back()->with('success', '' . count($applicants) . ' application(s) is successfully deleted!');
    }

}
