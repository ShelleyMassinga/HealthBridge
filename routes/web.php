<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NavigationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LabController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;



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

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

//Route::get('/login','LoginController@showLoginForm')->name('login');
//Route::post('/admin/dashboard','LoginController@login')->name('login.submit');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/admin/dashboard', [LoginController::class, 'login'])->name('login.submit');
Route::post('/Lab/dashboard', [LoginController::class, 'login'])->name('login.submit');
//Route::post('/patient/dashboard', [LoginController::class, 'login'])->name('login.submit');
Route::post('/insurance/dashboard', [LoginController::class, 'login'])->name('login.submit');

Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');


//Route::post('/Lab/dashboard', [RegisterController::class, 'register_insurance'])->name('insuranceRegister.submit');

Route::post('/register/insurance', [RegisterController::class, 'register_insurance'])->name('insuranceRegister.submit');



Route::get('/', [NavigationController::class, 'home'])->name('home');
Route::post('/login', [NavigationController::class, 'login'])->name('login');
//Route::get('/signup', [NavigationController::class, 'signup'])->name('signup');

// Route::prefix('admin')->middleware(['auth'])->group(function () { removed middleware for testing
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/request-claim', [AdminController::class, 'requestClaim'])->name('admin.request-claim');
    Route::get('/approved-claims', [AdminController::class, 'approvedClaims'])->name('admin.approved-claims');
    Route::get('/rejected-claims', [AdminController::class, 'rejectedClaims'])->name('admin.rejected-claims');
});

Route::prefix('lab')->group(function(){
    Route::get('/dashboard', [LabController::class, 'dashboard'])->name('Lab.dashboard');
    Route::get('/patient_list', [LabController::class, 'patient_list'])->name('Lab.patient_list');
    Route::get('/upload_reports', [LabController::class, 'upload_reports'])->name('Lab.upload_reports');
    Route::get('/upload_bills', [LabController::class, 'upload_bills'])->name('Lab.upload_bills');
});

Route::post('/logout', function () {
    // Auth::logout(); commented out for testing
    return redirect()->route('login');
})->name('logout');
