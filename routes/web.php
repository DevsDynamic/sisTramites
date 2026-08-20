<?php

use App\Http\Controllers\AreaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentAnalyticsController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DocumentFlowController;
use App\Http\Controllers\DocumentInboxController;
use App\Http\Controllers\DocumentReportController;
use App\Http\Controllers\DocumentSeriesController;
use App\Http\Controllers\DocumentTypeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SignatureController;
use App\Http\Controllers\SlaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkflowRuleController;

require __DIR__ . '/auth.php';

Route::get('/', function () {

    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return redirect()->route('login');
});

Route::middleware('auth')
    ->prefix('onboarding')
    ->name('onboarding.')
    ->controller(OnboardingController::class)
    ->group(function () {
        Route::get('/', 'welcome')
            ->name('welcome');
        Route::get('/company', 'company')
            ->name('company');
        Route::post('/company', 'companyStore')
            ->name('company.store');
        Route::get('/branding', 'branding')
            ->name('branding');
        Route::post('/branding', 'brandingStore')
            ->name('branding.store');
        Route::get('/completed', 'completed')
            ->name('completed');
    });

Route::middleware([
    'auth',
    'onboarding',
    'updatelastseen'
])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    /* ORGANIZACIÓN */
    Route::resource('areas', AreaController::class)->except(['create', 'edit', 'show']);
    Route::get('/areas/cards', [AreaController::class, 'cards'])->name('areas.cards');
    Route::patch('/areas/{area}/active', [AreaController::class, 'active'])->name('areas.active');
    Route::resource('users', UserController::class)->except(['create', 'edit', 'show']);
    Route::get('/users/cards', [UserController::class, 'cards'])->name('users.cards');
    Route::patch('/users/{user}/active', [UserController::class, 'active'])->name('users.active');
    Route::resource('roles', RoleController::class)->except(['create', 'edit', 'show']);
    Route::get('/roles/cards', [RoleController::class, 'cards'])->name('roles.cards');

    /* CONFIGURACIÓN DOCUMENTAL */
    Route::resource('document-types', DocumentTypeController::class)->except(['create', 'edit', 'show']);
    Route::patch('/document-types/{documentType}/status', [DocumentTypeController::class, 'changeStatus'])->name('document-types.change-status');
    Route::patch('/document-types/{documentType}/active', [DocumentTypeController::class, 'active'])->name('document-types.active');
    Route::get('/document-types/cards', [DocumentTypeController::class, 'cards'])->name('document-types.cards');

    Route::resource('document-series', DocumentSeriesController::class)->except(['create', 'edit', 'show']);
    Route::patch('/document-series/{documentSeries}/status', [DocumentSeriesController::class, 'changeStatus'])->name('document-series.change-status');
    Route::patch('/document-series/{documentSeries}/active', [DocumentSeriesController::class, 'active'])->name('document-series.active');
    Route::get('/document-series/cards', [DocumentSeriesController::class, 'cards'])->name('document-series.cards');

    Route::resource('signatures', SignatureController::class)->except(['create', 'edit', 'show']);
    Route::get('/signatures/cards', [SignatureController::class, 'cards'])->name('signatures.cards');
    Route::patch('/signatures/{signature}/active', [SignatureController::class, 'active'])->name('signatures.active');

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
            Route::get('/series-preview', 'seriesPreview')
                ->name('series-preview');
            Route::get('/cards', 'cards')
                ->name('cards');
            Route::get('/{document}/edit', 'edit')
                ->name('edit');
            Route::put('/{document}', 'update')
                ->name('update');
            Route::get('/{document}', 'show')
                ->name('show');
            Route::delete('/{document}', 'destroy')
                ->name('destroy');
            Route::post('/{document}/sign', 'sign')
                ->name('sign');
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
