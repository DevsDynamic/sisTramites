<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Http\Controllers\Tenant\OnboardingController;
use App\Http\Controllers\Tenant\AreaController;
use App\Http\Controllers\Tenant\DashboardController;
use App\Http\Controllers\Tenant\TenantUserController;
use App\Http\Controllers\Tenant\RoleController;
use App\Http\Controllers\Tenant\DocumentTypeController;
use App\Http\Controllers\Tenant\Documents\DocumentController;
use App\Http\Controllers\Tenant\Documents\DocumentInboxController;
use App\Http\Controllers\Tenant\Documents\DocumentReportController;
use App\Http\Controllers\Tenant\Documents\DocumentSeriesController;
use App\Http\Controllers\Tenant\Workflow\DocumentFlowController;
use App\Http\Controllers\Tenant\Workflow\SlaController;
use App\Http\Controllers\Tenant\Workflow\WorkflowRuleController;
use App\Http\Controllers\Tenant\Analytics\DocumentAnalyticsController;
use App\Http\Controllers\Tenant\Notifications\NotificationController;

use App\Http\Controllers\Tenant\Signature\SignatureController;

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
            Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
            /* ORGANIZACIÓN */
            Route::resource('areas', AreaController::class)->except(['create', 'edit', 'show']);
            Route::resource('users', TenantUserController::class);
            Route::resource('roles', RoleController::class);

            /* CONFIGURACIÓN DOCUMENTAL */
            Route::resource('document-types', DocumentTypeController::class);
            Route::resource('document-series', DocumentSeriesController::class);

            /* BANDEJAS */
            Route::controller(DocumentInboxController::class)
                ->prefix('documents')
                ->name('documents.')
                ->group(function () {
                    Route::get('inbox', 'index')->name('inbox');
                    Route::get('outbox', 'outbox')->name('outbox');
                    Route::get('tracking', 'tracking')->name('tracking');
                    Route::get('archived', 'archived')->name('archived');
                    Route::get('search', 'search')->name('search');
                });

            /* DOCUMENTOS */
            Route::prefix('documents')
                ->name('documents.')
                ->controller(DocumentController::class)
                ->group(function () {
                    Route::get('/', 'index')
                        ->name('index');
                    Route::get('/create', 'create')
                        ->name('create');
                    Route::post('/', 'store')
                        ->name('store');
                    Route::get('/{document}', 'show')
                        ->name('show');
                    Route::delete('/{document}', 'destroy')
                        ->name('destroy');
                    Route::post(
                        '/{document}/sign',
                        'sign'
                    )->name('sign');
                });

            /* WORKFLOW */
            Route::prefix('workflow')
                ->name('workflow.')
                ->controller(DocumentFlowController::class)
                ->group(function () {
                    Route::get('flows', 'flows')
                        ->name('flows');
                    Route::get('sla', 'sla')
                        ->name('sla');
                    Route::get('rules', 'rules')
                        ->name('rules');
                    /* ACCIONES */
                    Route::post('{flow}/receive', 'receive')
                        ->name('receive');
                    Route::post('{flow}/approve', 'approve')
                        ->name('approve');
                    Route::post('{flow}/reject', 'reject')
                        ->name('reject');
                    Route::post('{flow}/observe', 'observe')
                        ->name('observe');
                    Route::post('{flow}/reassign', 'reassign')
                        ->name('reassign');
                });

            /* NOTIFICACIONES */
            Route::controller(NotificationController::class)
                ->prefix('notifications')
                ->name('notifications.')
                ->group(function () {
                    Route::get('/', 'index')
                        ->name('index');
                    Route::post('{notification}/read', 'markAsRead')
                        ->name('read');
                    Route::get('unread-count', 'unreadCount')
                        ->name('unread-count');
                });
            /* REPORTES */
            Route::prefix('reports')
                ->name('reports.')
                ->group(function () {
                    Route::get(
                        'documents/{document}',
                        [DocumentReportController::class, 'document']
                    )->name('document');
                });

            /* ANALYTICS */
            Route::prefix('analytics')
                ->name('analytics.')
                ->group(function () {
                    Route::get(
                        'documents',
                        [DocumentAnalyticsController::class, 'index']
                    )->name('documents');
                });
            Route::prefix('signatures')
                ->name('signatures.')
                ->controller(SignatureController::class)
                ->group(function () {

                    Route::get('/', 'index')
                        ->name('index');
                    Route::post('/', 'store')
                        ->name('store');
                    Route::put(
                        '/{signature}',
                        'update'
                    )->name('update');
                    Route::delete(
                        '/{signature}',
                        'destroy'
                    )->name('destroy');
                });

            // Route::prefix('signatures')
            //     ->name('signatures.')
            //     ->controller(SignatureController::class)
            //     ->group(function () {
            //         Route::get('/', 'index')
            //             ->name('index');
            //     });

            /* SLA */
            Route::prefix('workflow')
                ->name('workflow.')
                ->controller(SlaController::class)
                ->group(function () {

                    Route::get('sla', 'index')
                        ->name('sla');
                });

            /* RULES */
            Route::prefix('workflow')
                ->name('workflow.')
                ->controller(WorkflowRuleController::class)
                ->group(function () {

                    Route::get('rules', 'index')
                        ->name('rules');
                });
        });
});
