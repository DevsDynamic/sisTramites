<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Http\Controllers\Tenant\OnboardingController;
use App\Http\Controllers\Tenant\AreaController;
use App\Http\Controllers\Tenant\DashboardController;
use App\Http\Controllers\Tenant\DocumentController;
use App\Http\Controllers\Tenant\DocumentFlowController;
use App\Http\Controllers\Tenant\DocumentInboxController;
use App\Http\Controllers\Tenant\DocumentReportController;
use App\Http\Controllers\Tenant\DocumentSearchController;
use App\Http\Controllers\Tenant\TenantUserController;
use App\Http\Controllers\Tenant\RoleController;
use App\Http\Controllers\Tenant\DocumentTypeController;
use App\Http\Controllers\Tenant\NotificationController;

/* TENANT ROUTES */

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
    // 'tenant.connection',
])->group(function () {

    /* AUTH TENANT */
    require __DIR__ . '/auth-tenant.php';

    /* ONBOARDING */
    Route::prefix('onboarding')
        ->name('tenant.onboarding.')
        ->group(function () {

            Route::get(
                '/',
                [OnboardingController::class, 'welcome']
            )->name('welcome');

            /* COMPANY */
            Route::get(
                '/company',
                [OnboardingController::class, 'company']
            )->name('company');

            Route::post(
                '/company',
                [OnboardingController::class, 'companyStore']
            )->name('company.store');

            /* BRANDING */
            Route::get(
                '/branding',
                [OnboardingController::class, 'branding']
            )->name('branding');

            Route::post(
                '/branding',
                [OnboardingController::class, 'brandingStore']
            )->name('branding.store');

            /* SUNAT */
            Route::get(
                '/sunat',
                [OnboardingController::class, 'sunat']
            )->name('sunat');

            Route::post(
                '/sunat',
                [OnboardingController::class, 'sunatStore']
            )->name('sunat.store');

            /* COMPLETED */
            Route::get(
                '/completed',
                [OnboardingController::class, 'completed']
            )->name('completed');
        });

    /* PROTECTED TENANT */
    Route::middleware([
        'auth:tenant',
        'tenant.status',
        'onboarding',
        'updatelastseen'
    ])
        ->as('tenant.')
        ->group(function () {
            Route::get('/', function () {
                return redirect()->route('tenant.dashboard');
            });
            Route::get('/dashboard', [DashboardController::class, 'index'])
                ->name('dashboard');
            Route::resource('areas', AreaController::class)
                ->except([
                    'create',
                    'edit',
                    'show',
                ]);
            Route::resource(
                'users',
                TenantUserController::class
            );
            Route::resource('roles', RoleController::class);

            Route::resource('document-types', DocumentTypeController::class);
            Route::get('tenant/documents/{document}', [DocumentController::class, 'show'])->name('documents.show');
            Route::get('tenant/inbox', [DocumentInboxController::class, 'index'])->name('inbox');

            Route::post('flows/{flow}/receive', [DocumentFlowController::class, 'receive']);
            Route::post('flows/{flow}/approve', [DocumentFlowController::class, 'approve']);
            Route::post('flows/{flow}/reject', [DocumentFlowController::class, 'reject']);
            Route::post('flows/{flow}/observe', [DocumentFlowController::class, 'observe']);
            Route::post('flows/{flow}/reassign', [DocumentFlowController::class, 'reassign']);

            Route::get('notifications', [NotificationController::class, 'index'])->name('notifications');
            Route::post('notifications/{notification}/read', [NotificationController::class, 'markAsRead']);
            Route::get('notifications/unread-count', [NotificationController::class, 'unreadCount']);

            Route::get('reports/documents/{document}', [DocumentReportController::class, 'document'])->name('reports.document');

            Route::get('documents/search', [DocumentSearchController::class, 'index'])->name('documents.search');
        });
});
