<?php

use Xcelerate\Http\Controllers\V1\Admin\Auth\LoginController;
use Xcelerate\Http\Controllers\V1\Admin\Expense\ShowReceiptController;
use Xcelerate\Http\Controllers\V1\Admin\Report\CustomerSalesReportController;
use Xcelerate\Http\Controllers\V1\Admin\Report\ExpensesReportController;
use Xcelerate\Http\Controllers\V1\Admin\Report\ItemSalesReportController;
use Xcelerate\Http\Controllers\V1\Admin\Report\ProfitLossReportController;
use Xcelerate\Http\Controllers\V1\Admin\Report\TaxSummaryReportController;
use Xcelerate\Http\Controllers\V1\Customer\Auth\LoginController as CustomerLoginController;
use Xcelerate\Http\Controllers\V1\Customer\EstimatePdfController as CustomerEstimatePdfController;
use Xcelerate\Http\Controllers\V1\Customer\InvoicePdfController as CustomerInvoicePdfController;
use Xcelerate\Http\Controllers\V1\Customer\PaymentPdfController as CustomerPaymentPdfController;
use Xcelerate\Http\Controllers\V1\Modules\ScriptController;
use Xcelerate\Http\Controllers\V1\Modules\StyleController;
use Xcelerate\Http\Controllers\V1\PDF\DownloadReceiptController;
use Xcelerate\Http\Controllers\V1\PDF\EstimatePdfController;
use Xcelerate\Http\Controllers\V1\PDF\InvoicePdfController;
use Xcelerate\Http\Controllers\V1\PDF\PaymentPdfController;
use Xcelerate\Http\Controllers\ZohoController;
use Xcelerate\Models\Company;
use Illuminate\Support\Facades\Route;
use Xcelerate\Models\Crm\Providers\Zoho\ZohoCrm;
use Xcelerate\Models\CrmConnector;
use Xcelerate\Http\Controllers\SetupDefaultSettingController;

Route::get('/generate-zoho-token', [ZohoController::class, 'oAuth']);

// Route::get('/oauth2callback', [ZohoController::class, 'oAuthCallback']);

Route::get('/oauth2callback', [CrmConnector::class, 'oAuthCallback']);

Route::get('/test-file', [ZohoController::class, 'testFile']);
Route::get('/get-refresh-token', [ZohoController::class, 'generateRefreshToken']);

Route::get('/sync-zoho-products', [ZohoController::class, 'syncProducts']);

Route::get('/sync-zoho-users', [ZohoController::class, 'syncZohoUsers']);
Route::get('/zoho-users', [ZohoController::class, 'zohoUsers']);


Route::get('/get-zoho-leads', [ZohoController::class, 'getLeads']);
Route::get('/get-zoho-lead/{id}', [ZohoController::class, 'getZohoLead']);

Route::get('/add-zoho-lead', [ZohoController::class, 'addZohoLead']);
Route::get('/delete-zoho-lead', [ZohoController::class, 'deleteLead']);

Route::get('/super-admin-permissions', [SetupDefaultSettingController::class, 'superAdminPermissions']);
// Module Asset Includes
// ----------------------------------------------

Route::get('/modules/styles/{style}', StyleController::class);

Route::get('/modules/scripts/{script}', ScriptController::class);


// Admin Auth
// ----------------------------------------------

Route::post('login', [LoginController::class, 'login']);


Route::get('logout', function () {
    Auth::guard('web')->logout();
});


Route::post('auth/logout', function () {
    Auth::guard('web')->logout();
});


// Customer auth
// ----------------------------------------------

//Route::post('/{company:slug}/customer/login', CustomerLoginController::class);

Route::post('/{company:slug}/customer/logout', function () {
    Auth::guard('customer')->logout();
});


// Report PDF & Expense Endpoints
// ----------------------------------------------

Route::middleware('auth:sanctum')->prefix('reports')->group(function () {

    // sales report by customer
    //----------------------------------
    Route::get('/sales/customers/{hash}', CustomerSalesReportController::class);

    // sales report by items
    //----------------------------------
    Route::get('/sales/items/{hash}', ItemSalesReportController::class);

    // report for expenses
    //----------------------------------
    Route::get('/expenses/{hash}', ExpensesReportController::class);

    // report for tax summary
    //----------------------------------
    Route::get('/tax-summary/{hash}', TaxSummaryReportController::class);

    // report for profit and loss
    //----------------------------------
    Route::get('/profit-loss/{hash}', ProfitLossReportController::class);


    // download expense receipt
    // -------------------------------------------------
    Route::get('/expenses/{expense}/download-receipt', DownloadReceiptController::class);
    Route::get('/expenses/{expense}/receipt', ShowReceiptController::class);
});


// PDF Endpoints
// ----------------------------------------------

Route::middleware('pdf-auth')->group(function () {

    //  invoice pdf
    // -------------------------------------------------
    Route::get('/invoices/pdf/{invoice:unique_hash}', InvoicePdfController::class);

    // estimate pdf
    // -------------------------------------------------
    Route::get('/estimates/pdf/{estimate:unique_hash}', EstimatePdfController::class);

    // payment pdf
    // -------------------------------------------------
    Route::get('/payments/pdf/{payment:unique_hash}', PaymentPdfController::class);
});


// customer pdf endpoints for invoice, estimate and Payment
// -------------------------------------------------

Route::prefix('/customer')->group(function () {
    Route::get('/invoices/{email_log:token}', [CustomerInvoicePdfController::class, 'getInvoice']);
    Route::get('/invoices/view/{email_log:token}', [CustomerInvoicePdfController::class, 'getPdf'])->name('invoice');

    Route::get('/estimates/{email_log:token}', [CustomerEstimatePdfController::class, 'getEstimate']);
    Route::get('/estimates/view/{email_log:token}', [CustomerEstimatePdfController::class, 'getPdf'])->name('estimate');

    Route::get('/payments/{email_log:token}', [CustomerPaymentPdfController::class, 'getPayment']);
    Route::get('/payments/view/{email_log:token}', [CustomerPaymentPdfController::class, 'getPdf'])->name('payment');
});


// Move other http requests to the Vue App
// -------------------------------------------------

Route::get('/admin/{vue?}', function () {
    return view('app');
})->where('vue', '[\/\w\.-]*')->name('admin.dashboard')->middleware(['redirect-if-unauthenticated']);

Route::get('{company:slug}/customer/{vue?}', function (Company $company) {
    return view('app')->with([
        'customer_logo' => get_company_setting('customer_portal_logo', $company->id),
        'current_theme' => get_company_setting('customer_portal_theme', $company->id),
        'customer_page_title' => get_company_setting('customer_portal_page_title', $company->id)
    ]);
})->where('vue', '[\/\w\.-]*')->name('customer.dashboard')->middleware(['guest']);

Route::get('/', function () {

    return view('app');
})->where('vue', '[\/\w\.-]*')->name('home')->middleware(['guest']);

Route::get('/reset-password/{token}', function () {
    return view('app');
})->where('vue', '[\/\w\.-]*')->name('reset-password')->middleware([ 'guest']);

Route::get('/forgot-password', function () {
    return view('app');
})->where('vue', '[\/\w\.-]*')->name('forgot-password')->middleware(['guest']);

Route::get('/login', function () {
    return view('app');
})->where('vue', '[\/\w\.-]*')->name('login')->middleware(['guest']);
