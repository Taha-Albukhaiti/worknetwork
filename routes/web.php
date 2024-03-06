<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Hier werden die Web-Routen registriert und den Controllern zugewiesen.
| Diese Routen werden vom RouteServiceProvider geladen, der die Routen innerhalb einer Gruppe enthält.
| Diese Gruppe erhält den "web" Middleware, das CSRF-Schutz, Sitzungsstatus und Cookie-Authentifizierung enthält.
|
| @author Taha Al-Bukhaiti
| @version 1.0
| @since 1.0
*/

Route::get('/', [HomeController::class, 'index'])->name('welcome');
Route::get('/search/user', [HomeController::class, 'searchUser'])->name('search.user');
Route::get('/search/company', [HomeController::class, 'searchCompany'])->name('search.company');
Route::get('/user/{id}/profile-request', [HomeController::class, 'userProfileRequest'])->name('user.profile.request');
Route::get('/company/{id}/profile-view', [HomeController::class, 'companyProfileView'])->name('company.profile.view');

// Authentication
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        if (Auth::check()) {
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif (Auth::user()->role === 'user') {
                return redirect()->route('user.dashboard');
            } elseif (Auth::user()->role === 'company') {
                return redirect()->route('company.dashboard');
            }
        }
        return view('welcome');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

// Admin Routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/login', [AdminController::class, 'adminLogin'])->name('admin.login');
    Route::get('/admin/dashboard', [AdminController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::get('/admin/logout', [AdminController::class, 'adminLogout'])->name('admin.logout');
    Route::get('/admin/profile', [AdminController::class, 'adminProfile'])->name('admin.profile');
    Route::get('/admin/profile/edit', [AdminController::class, 'adminProfileEdit'])->name('admin.profile.edit');
    Route::get('/admin/profile/change/password', [AdminController::class, 'adminChangePassword'])->name('admin.profile.change.password');
    Route::post('/admin/profile/store', [AdminController::class, 'adminProfileStore'])->name('admin.profile.store');
    Route::post('/admin/update/password', [AdminController::class, 'adminUpdatePassword'])->name('admin.update.password');
    Route::get('/admin/companies', [AdminController::class, 'adminCompanies'])->name('admin.companies');
    Route::get('/admin/user/{id}', [AdminController::class, 'adminUserShow'])->name('admin.user.show');
    Route::delete('/admin/user/delete/{id}', [AdminController::class, 'adminUserDelete'])->name('admin.user.delete');
    Route::get('/admin/company/{id}', [AdminController::class, 'adminCompanyShow'])->name('admin.company.show');
    Route::delete('/admin/company/delete/{id}', [AdminController::class, 'adminCompanyDelete'])->name('admin.company.delete');
    Route::get('/admin/search/user', [AdminController::class, 'adminSearchUser'])->name('admin.search.user');
    Route::get('/admin/search/company', [AdminController::class, 'adminSearchCompany'])->name('admin.search.company');
    Route::get('/admin/load/more/users', [AdminController::class, 'loadMoreUsers'])->name('admin.load.more.users');
    Route::get('/admin/load/more/companies', [AdminController::class, 'loadMoreCompanies'])->name('admin.load.more.companies');
});

// User Routes
Route::get('/user/login', [UserController::class, 'userLogin'])->name('user.login');
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/user/dashboard', [UserController::class, 'userDashboard'])->name('user.dashboard');
    Route::get('/user/logout', [UserController::class, 'userLogout'])->name('user.logout');
    Route::get('/user/profile', [UserController::class, 'userProfile'])->name('user.profile');
    Route::get('/user/profile/edit', [UserController::class, 'userProfileEdit'])->name('user.profile.edit');
    Route::get('/user/profile/change/password', [UserController::class, 'userChangePassword'])->name('user.profile.change.password');
    Route::get('/user/portfolio', [UserController::class, 'userPortfolio'])->name('user.portfolio');
    Route::match(['post', 'put'], '/user/profile/store', [UserController::class, 'userProfileStore'])->name('user.profile.store');
    Route::post('/user/update/password', [UserController::class, 'userUpdatePassword'])->name('user.update.password');
    Route::post('/user/portfolio/store', [UserController::class, 'userPortfolioStore'])->name('user.portfolio.store');
    Route::delete('/user/portfolio/delete/{id}', [UserController::class, 'deletePortfolioDetail'])->name('user.detail.delete');
    Route::get('/profile-requests', [UserController::class, 'showProfileRequests'])->name('user.profile_requests');
    Route::post('/profile-request/{id}/accept', [UserController::class, 'acceptProfileRequest'])->name('user.accept_profile_request');
    Route::post('/profile-request/{id}/reject', [UserController::class, 'rejectProfileRequest'])->name('user.reject_profile_request');
    Route::get('/company/{id}/profile', [UserController::class, 'companyProfileView'])->name('user.company.profile.show');
});

// Company Routes
Route::get('/company/login', [CompanyController::class, 'companyLogin'])->name('company.login');
Route::middleware(['auth', 'role:company'])->group(function () {
    Route::get('/company/dashboard', [CompanyController::class, 'companyDashboard'])->name('company.dashboard');
    Route::get('/company/logout', [CompanyController::class, 'companyLogout'])->name('company.logout');
    Route::get('/company/profile', [CompanyController::class, 'companyProfile'])->name('company.profile');
    Route::get('/company/profile/edit', [CompanyController::class, 'companyProfileEdit'])->name('company.profile.edit');
    Route::get('/company/profile/change/password', [CompanyController::class, 'companyChangePassword'])->name('company.profile.change.password');
    Route::post('/company/profile/store', [CompanyController::class, 'companyProfileStore'])->name('company.profile.store');
    Route::post('/company/update/password', [CompanyController::class, 'companyUpdatePassword'])->name('company.update.password');
    Route::get('/company/accepted-users', [CompanyController::class, 'acceptedUsers'])->name('company.accepted.users');
    Route::get('/company/user/{id}', [CompanyController::class, 'acceptedUserProfileView'])->name('company.user.accepted.show');
});
