<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    protected $table = 'Claim';
    protected $primaryKey = 'ClaimID';
    public $timestamps = false;

    protected $fillable = [
        'File',
        'Filing_status',
        'Approval_status',
        'Reason_for_rejection',
        'InsuranceID',
        'PatientID',
        'LabID'
    ];
}
