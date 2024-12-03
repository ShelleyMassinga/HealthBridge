<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Claim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function requestClaim(Request $request)
    {
        try {

            $search = $request->input('search');

            $patients = Patient::search($search)
                ->select('Patient.*')
                ->leftJoin('Claim', 'Patient.PatientID', '=', 'Claim.PatientID')
                ->where(function($query) {
                    $query->whereNull('Claim.Filing_status')
                          ->orWhere('Claim.Filing_status', '=', 'Not_filled');
                })
                ->orderBy('Patient.Pt_Name')
                ->get();

            return view('admin.request-claim', compact('patients', 'search'));

        } catch (\Exception $e) {
            Log::error('Database connection failed: ' . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function submitClaim(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:Patient,PatientID',
            'claim_details' => 'required|string',
            'claim_file' => 'required|file|mimes:png,jpg,jpeg,pdf|max:10240'
        ]);

        try {
            if ($request->hasFile('claim_file')) {
                $file = $request->file('claim_file');
                $fileName = time() . '_' . $file->getClientOriginalName();

                // $claimsPath = public_path('Claims');
                // if (!file_exists($claimsPath)) {
                //     mkdir($claimsPath, 0777, true);
                // }

                // $file->move($claimsPath, $fileName);

                $patient = Patient::find($request->patient_id);


                $claim = new Claim();
                $claim->File = 'Claims/' . $fileName;
                $claim->Filing_status = 'Filled';
                $claim->Approval_status = 'None';
                $claim->PatientID = $request->patient_id;
                $claim->InsuranceID = $patient->InsuranceID;
                $claim->LabID = 1; // should I make this dynamic?
                $claim->save();

                return redirect()->back()->with('success', 'Claim submitted successfully');
            }

            return redirect()->back()->with('error', 'No file uploaded');
        } catch (\Exception $e) {
            Log::error('Error submitting claim: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error submitting claim: ' . $e->getMessage());
        }
    }

    public function submittedClaims()
    {
        $claims = Claim::where('Filing_status', 'Filled')
            ->join('Patient', 'Claim.PatientID', '=', 'Patient.PatientID')
            ->select('Claim.*', 'Patient.Pt_Name')
            ->orderBy('Claim.ClaimID', 'desc')
            ->get();

        return view('admin.submitted-claims', compact('claims'));
    }
    public function approvedClaims()
    {
        $claims = Claim::where('Approval_status', 'Approved')
            ->join('Patient', 'Claim.PatientID', '=', 'Patient.PatientID')
            ->join('Lab', 'Claim.LabID', '=', 'Lab.LabID')
            ->select('Claim.*', 'Patient.Pt_Name', 'Lab.Lab_Name')
            ->orderBy('Claim.ClaimID', 'desc')
            ->get();

        return view('admin.approved-claims', compact('claims'));
    }

    public function rejectedClaims()
    {
        $claims = Claim::where('Approval_status', 'Reject')
            ->join('Patient', 'Claim.PatientID', '=', 'Patient.PatientID')
            ->join('Lab', 'Claim.LabID', '=', 'Lab.LabID')
            ->select('Claim.*', 'Patient.Pt_Name', 'Lab.Lab_Name', 'Claim.Reason_for_rejection')
            ->orderBy('Claim.ClaimID', 'desc')
            ->get();

        return view('admin.rejected-claims', compact('claims'));
    }
}
