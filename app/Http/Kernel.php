<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
        ],

        'api' => [
            'throttle:60,1',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'can' => \Illuminate\Foundation\Http\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'admin' => \App\Http\Middleware\CheckAdminAuthorized::class,
        'entrypoint_role' => \App\Http\Middleware\CheckIfUserIsEntryPoint::class,
        'receiption_role' => \App\Http\Middleware\CheckIfUserIsReceiption::class,
        'doctor_role' => \App\Http\Middleware\CheckIfUserIsDoctor::class,
        'entrypoint_receiption_role' => \App\Http\Middleware\CheckIfUserIsEntryOrReceiption::class,
		'desk_role' => \App\Http\Middleware\CheckIfUserIsDesk::class,
		'desk_receiption_role' => \App\Http\Middleware\CheckIfUserIsDeskOrReceiption::class,
		'entry_desk_receiption_role' => \App\Http\Middleware\CheckIfUserIsEntryOrDeskOReceiption::class,
        'hasmedical' => \App\Http\Middleware\CheckIfDoctorHasMedicalUnit::class,
		
    ];
}
