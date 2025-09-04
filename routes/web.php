<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminPanelController;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

// Welcome
Route::view('/', 'welcome')->name('welcome');

// Dev helper: reset admin credentials (remove in production)
Route::get('/admin/dev/reset-admin', function () {
    Admin::updateOrCreate(
        ['email' => 'admin@example.com'],
        ['name' => 'Admin', 'password' => Hash::make('password')]
    );
    return 'Admin reset. Email: admin@example.com, Password: password';
})->name('admin.dev.reset');

// Dev helper: check admin record and hash (remove in production)
Route::get('/admin/dev/check', function () {
    $admin = Admin::where('email', 'admin@example.com')->first();
    if (!$admin) {
        return 'No admin found';
    }
    return 'Found admin id='.$admin->id.' email='.$admin->email;
})->name('admin.dev.check');

// ---- User auth ----
Route::middleware('guard.session:web')->group(function () {
    Route::get('/register', [UserAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [UserAuthController::class, 'register']);
    Route::get('/login', [UserAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [UserAuthController::class, 'login']);
    Route::post('/logout', [UserAuthController::class, 'logout'])->name('logout');
});

// ---- User area (must login) ----
Route::middleware(['guard.session:web','auth:web'])->group(function () {
    Route::view('/dashboard', 'user.dashboard')->name('dashboard');

    // profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // contacts
    Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index');
    Route::get('/contacts/create', [ContactController::class, 'create'])->name('contacts.create');
    Route::post('/contacts', [ContactController::class, 'store'])->name('contacts.store');
});

// ---- Admin auth ----
Route::prefix('admin')->middleware('guard.session:admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    // admin panel (must be admin)
    Route::middleware(['guard.session:admin','auth:admin'])->group(function () {
        Route::get('/dashboard', [AdminPanelController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/users', [AdminPanelController::class, 'users'])->name('admin.users');
        Route::get('/contacts', [AdminPanelController::class, 'contacts'])->name('admin.contacts');
        Route::get('/export/users', [AdminPanelController::class, 'exportUsers'])->name('admin.export.users');
        Route::get('/export/contacts', [AdminPanelController::class, 'exportContacts'])->name('admin.export.contacts');
    });
});
