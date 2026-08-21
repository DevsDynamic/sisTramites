<?php

use App\Http\Controllers\AreaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentAnalyticsController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DocumentReportController;
use App\Http\Controllers\DocumentSeriesController;
use App\Http\Controllers\DocumentTypeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SignatureController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkflowTemplateController;
use App\Http\Controllers\WorkflowInboxController;

require __DIR__ . '/auth.php';

Route::middleware('auth')->controller(ProfileController::class)->group(function () {
    Route::get('/profile', 'edit')->name('profile.edit');
    Route::patch('/profile', 'update')->name('profile.update');
    Route::put('/profile/password', 'updatePassword')->name('profile.password.update');
    Route::get('/profile/avatar', 'avatar')->name('profile.avatar');
});

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
        Route::post('/completed', 'completed')
            ->name('completed');
        Route::get('/license', 'license')->name('license');
        Route::post('/license', 'licenseStore')->name('license.store');
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
    Route::resource('plans', PlanController::class)->except(['show', 'destroy']);
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
    Route::get('/signatures/{signature}/image', [SignatureController::class, 'image'])->name('signatures.image');
    Route::get('/signatures/cards', [SignatureController::class, 'cards'])->name('signatures.cards');
    Route::patch('/signatures/{signature}/active', [SignatureController::class, 'active'])->name('signatures.active');

    Route::resource('workflow-templates', WorkflowTemplateController::class)
        ->only(['index', 'store', 'update']);
    Route::get('/workflow-inbox', [WorkflowInboxController::class, 'index'])->name('workflow-inbox.index');
    Route::post('/workflow-inbox/{step}/complete', [WorkflowInboxController::class, 'complete'])->name('workflow-inbox.complete');

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
            Route::get('/{document}/attachments/{attachment}/file', 'attachmentFile')
                ->name('attachments.file');
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

});
