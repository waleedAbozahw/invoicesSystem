<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomersReportController;
use App\Http\Controllers\InvoiceAttachmentsController;
use App\Http\Controllers\InvoiceDetailsController;
use App\Http\Controllers\InvoicesArchiveController;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\InvoicesReportController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SectionsController;
use App\Http\Controllers\UserController;
use App\Models\Invoice_details;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

//Route::get('/{page}', 'AdminController@index');



// Auth::routes();


// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

//Auth::routes(['register' => true]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('invoices',InvoicesController::class);

Route::resource('invoices_archive',InvoicesArchiveController::class);

Route::resource('Archive',InvoicesArchiveController::class);

Route::get('back',[InvoicesController::class,'index'])->name('back');

Route::get('paid_invoices',[InvoicesController::class,'paid_invoices']);
Route::get('non_paid_invoices',[InvoicesController::class,'non_paid_invoices']);
Route::get('partial_paid_invoices',[InvoicesController::class,'partial_paid_invoices']);

Route::resource('InvoiceAttachments',InvoiceAttachmentsController::class);

Route::get('/section/{id}',[InvoicesController::class,'getProducts']);

Route::get('/InvoicesDetails/{id}',[InvoiceDetailsController::class,'edit']);

Route::get('View_file/{invoice_number}/{file_name}',[InvoiceDetailsController::class,'open_file']);

Route::get('download/{invoice_number}/{file_name}',[InvoiceDetailsController::class,'get_file']);

Route::get('delete_file',[InvoiceDetailsController::class,'destroy'])->name('delete_file');

Route::get('/edit_invoice/{id}',[InvoicesController::class,'edit']);

Route::get('/status_show/{id}',[InvoicesController::class,'show'])->name('status_show');

Route::get('MarkAsRead_all',[InvoicesController::class,'MarkAsRead_all'])->name('MarkAsRead_all');

Route::get('/status_update/{id}',[InvoicesController::class,'status_update'])->name('status_update');

Route::get('/delete_invoice/{id}',[InvoicesController::class,'destroy']);

Route::get('Print_invoice/{id}',[InvoicesController::class,'print_invoice']);

Route::get('/update',[InvoicesController::class,'update']);

Route::resource('sections',SectionsController::class);

Route::resource('products',ProductController::class);

Route::get('/invoicesExport', [InvoicesController::class,'export']);

Route::get('invoices_report', [InvoicesReportController::class,'index']);

Route::get('customer_report', [CustomersReportController::class,'index']);

Route::post('Search_invoices', [InvoicesReportController::class,'search_invoices']);

Route::post('Search_customers', [CustomersReportController::class,'search_customers']);

Route::group(['middleware' => ['auth']], function() {
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
});

Route::get('/{page}',[AdminController::class,'index']);
