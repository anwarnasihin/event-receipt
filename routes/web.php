<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventItemController;
use App\Http\Controllers\EventParticipantController;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\EventParticipantImportController;
use App\Http\Controllers\ParticipantReceiptController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ParticipantCheckinController;
use App\Http\Controllers\UserManagementController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::middleware(['role:Administrator'])->group(function () {

    Route::post('events/{event}/toggle-status',[EventController::class, 'toggleStatus'])->name('events.toggle-status');

    Route::resource('events', EventController::class);

    Route::resource('events.items', EventItemController::class);

    Route::get(
        'events/{event}/participants/import',
        [EventParticipantImportController::class, 'create']
    )->name('events.participants.import');

    Route::post(
        'events/{event}/participants/import',
        [EventParticipantImportController::class, 'store']
    )->name('events.participants.import.store');

    Route::get(
        'events/{event}/participants/template',
        [EventParticipantImportController::class, 'template']
    )->name('events.participants.template');

    Route::resource(
        'events.participants',
        EventParticipantController::class
    );

    //update 22/08/2026
    Route::post(
    'events/{event}/participants/{participant}/resend-receipt',
        [EventParticipantController::class, 'resendReceipt']
    )->name('events.participants.resend-receipt');
    //end update 22/08/2026

});

    Route::middleware(['role:Administrator'])->group(function () {

    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');

});

    Route::get('/qr-test', function () {
        return QrCode::size(250)
            ->generate('Hello BINUS');
    });

    Route::middleware(['role:Administrator|Petugas'])->group(function () {
    Route::get('/receipt',[ParticipantReceiptController::class, 'index'])->name('receipt.index');
    Route::get('/receipt/{event}',[ParticipantReceiptController::class, 'show'])->name('receipt.show');
    Route::get('/receipt/{event}/search',[ParticipantReceiptController::class, 'search'])->name('receipt.search');
    Route::post('/receipt/store',[ParticipantReceiptController::class, 'store'])->name('receipt.store');
    Route::get('/receipt/{event}/items', [ParticipantReceiptController::class, 'items'])->name('receipt.items');
    // PDF tanda terima souvenir
    Route::get('/receipt/{receipt}/pdf',[ParticipantReceiptController::class, 'pdf'])->name('receipt.pdf');
    });

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');



    // =====================================
// ABSENSI
// =====================================
Route::middleware(['role:Administrator|Petugas'])->group(function () {
Route::get('/checkin',[ParticipantCheckinController::class, 'index'])->name('checkin.index');
Route::get('/checkin/event/{event}',[ParticipantCheckinController::class, 'show'])->name('checkin.show');
Route::get('/checkin/event/{event}/search',[ParticipantCheckinController::class, 'search'])->name('checkin.search');
Route::get('/checkin/event/{event}/participants',[ParticipantCheckinController::class, 'participants'])->name('checkin.participants');
Route::post('/checkin/store',[ParticipantCheckinController::class, 'store'])->name('checkin.store');
Route::post('/checkin/event/{event}/manual',[ParticipantCheckinController::class, 'storeManual'])->name('checkin.manual');
/*
|--------------------------------------------------------------------------
| EXPORT LAPORAN ABSENSI
|--------------------------------------------------------------------------
*/
Route::get('/checkin/event/{event}/export/excel',[ParticipantCheckinController::class, 'exportExcel'])->name('checkin.export.excel');
Route::get('/checkin/event/{event}/export/pdf',[ParticipantCheckinController::class, 'exportPdf'])->name('checkin.export.pdf');
});
    Route::get('/reports/export/excel', [ReportController::class, 'exportExcel'])->name('reports.export.excel');
    Route::get('/reports/export/pdf', [ReportController::class, 'exportPdf'])->name('reports.export.pdf');

Route::middleware(['role:Administrator'])->group(function () {
Route::resource('users', UserManagementController::class)->except(['create','show','edit',]);});

}); // <-- group auth selesai
require __DIR__.'/auth.php';
use App\Http\Controllers\CheckinController;
Route::get('/checkin/{code}',[CheckinController::class,'show'])->name('participant.checkin');
Route::post('/checkin/{code}',[CheckinController::class,'store'])->name('participant.checkin.store');




