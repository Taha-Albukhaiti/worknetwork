<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
|
| These routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//__________ Authentication  __________ //
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
//__________ Authentication End __________ //


// Zuerst wird geprüft, ob der User authentifiziert ist, dann

// das ist hier, weil bei middleware('auth') die Authentifizierung geprüft wird, bevor die Route aufgerufen wird
Route::get('/admin/login', [AdminController::class, 'adminLogin'])->name('admin.login');

// Admin Group mit Middleware auth und role:admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    // wenn man die URL /admin/dashboard aufruft, wird die Methode adminDashboard() im AdminController aufgerufen
    Route::get('/admin/dashboard', [AdminController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::get('/admin/logout', [AdminController::class, 'adminLogout'])->name('admin.logout');
    Route::get('/admin/profile', [AdminController::class, 'adminProfile'])->name('admin.profile');
    Route::get('/admin/profile/edit', [AdminController::class, 'adminProfileEdit'])->name('admin.profile.edit');
    Route::get('/admin/profile/change/password', [AdminController::class, 'adminChangePassword'])->name('admin.profile.change.password');
    Route::post('/admin/profile/store', [AdminController::class, 'adminProfileStore'])->name('admin.profile.store');
    Route::post('/admin/update/password', [AdminController::class, 'adminUpdatePassword'])->name('admin.update.password');
});


//
Route::get('/user/login', [UserController::class, 'userLogin'])->name('user.login');

// User Group mit Middleware auth und role:user
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/user/dashboard', [UserController::class, 'userDashboard'])->name('user.dashboard');
    Route::get('/user/logout', [UserController::class, 'userLogout'])->name('user.logout');
    Route::get('/user/profile', [UserController::class, 'userProfile'])->name('user.profile');
    Route::get('/user/profile/edit', [UserController::class, 'userProfileEdit'])->name('user.profile.edit');
    Route::get('/user/profile/change/password', [UserController::class, 'userChangePassword'])->name('user.profile.change.password');
    Route::post('/user/profile/store', [UserController::class, 'userProfileStore'])->name('user.profile.store');
    Route::post('/user/update/password', [UserController::class, 'userUpdatePassword'])->name('user.update.password');

});

Route::get('/company/login', [CompanyController::class, 'companyLogin'])->name('company.login');
Route::middleware(['auth', 'role:company'])->group(function () {
    Route::get('/company/dashboard', [CompanyController::class, 'companyDashboard'])->name('company.dashboard');
    Route::get('/company/logout', [CompanyController::class, 'companyLogout'])->name('company.logout');
    Route::get('/company/profile', [CompanyController::class, 'companyProfile'])->name('company.profile');
    Route::get('/company/profile/edit', [CompanyController::class, 'companyProfileEdit'])->name('company.profile.edit');
    Route::get('/company/profile/change/password', [CompanyController::class, 'companyChangePassword'])->name('company.profile.change.password');
    Route::post('/company/profile/store', [CompanyController::class, 'companyProfileStore'])->name('company.profile.store');
    Route::post('/company/update/password', [CompanyController::class, 'companyUpdatePassword'])->name('company.update.password');
});

