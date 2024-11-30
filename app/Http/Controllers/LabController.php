<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
// use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class LabController extends Controller
{
    //
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }
    public function dashboard()
    {
        return view('Lab.dashboard');
    }
    public function patient_list()
    {
        return view('Lab.patient_list');
    }
    public function upload_reports()
    {
        return view('Lab.upload_reports');
    }
    public function upload_bills()
    {
        return view('Lab.upload_bills');
    }
}
