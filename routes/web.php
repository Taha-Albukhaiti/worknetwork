<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
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

Route::middleware(['auth', 'role:admin'])->group(function () {
    // wenn man die URL /admin/dashboard aufruft, wird die Methode adminDashboard() im AdminController aufgerufen
    Route::get('/admin/dashboard', [AdminController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::get('/admin/logout', [AdminController::class, 'adminLogout'])->name('admin.logout');
});

Route::middleware(['auth', 'role:company'])->group(function () {
    Route::get('/company/dashboard', [CompanyController::class, 'companyDashboard'])->name('company.dashboard');
});

Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/user/dashboard', [UserController::class, 'userDashboard'])->name('user.dashboard');
});

