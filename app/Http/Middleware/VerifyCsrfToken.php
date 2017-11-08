<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
		'patients/showPID',
		'patients/showSID',
		'visits/getDiagnoses',
		'visits/getComplaints',
		'visits/getProcedures',
		'visits/getRadiology',
		'visits/getDrRecommendation',
		'visits/getMedicine',
		'visits/getVisits',
		'visits/getNewVisits',
		'patients/getDepartmentDoctors',
		'patients/getPatient',
    ];
}
