<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;

// ADMIN CONTROLLER
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TransactionController as AdminTransactionController;
use App\Http\Controllers\Admin\ScanConfirmationController;
use App\Http\Controllers\Admin\RewardController;
use App\Http\Controllers\Admin\SawController;

// USER CONTROLLER
use App\Http\Controllers\User\TransactionController;
use App\Http\Controllers\User\ScanController;

Route::get('/scan-sampah', function () {
    return view('scan');
});
Route::get('/', function () {
    return view('welcome');
});



/*
|--------------------------------------------------------------------------
| GUEST ROUTE (LOGIN & REGISTER)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {

    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::post('/login', function (Request $request) {

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return Auth::user()->role === 'admin'
                ? redirect()->route('admin.dashboard')
                : redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    })->name('login.store');

    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');

    Route::post('/register', function (Request $request) {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'terms' => 'required',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        return redirect()->route('login')
            ->with('success', 'Pendaftaran berhasil, silakan login.');
    })->name('register.store');
});

/*
|--------------------------------------------------------------------------
| USER AUTH ROUTE
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [\App\Http\Controllers\User\DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/scan-sampah', function () {
        return view('scan-sampah');
    })->name('scan-sampah');

    Route::get('/marketplace', [\App\Http\Controllers\User\MarketplaceController::class, 'index'])
        ->name('marketplace');

    Route::get('/profile', [\App\Http\Controllers\User\ProfileController::class, 'index'])
        ->name('profile');

    Route::put('/profile', [\App\Http\Controllers\User\ProfileController::class, 'update'])
        ->name('profile.update');

    /*
    |--------------------------------------------------------------------------
    | USER SCAN
    |--------------------------------------------------------------------------
    */
    Route::get('/scan', [ScanController::class, 'create'])
        ->name('user.scan.create');

    Route::post('/scan', [ScanController::class, 'store'])
        ->name('user.scan.store');

    Route::get('/scan/{scan}', [ScanController::class, 'show'])
        ->name('user.scan.show');

    Route::post('/marketplace/tukar', [TransactionController::class, 'tukar'])
        ->name('marketplace.tukar');


    /*
    |--------------------------------------------------------------------------
    | USER TRANSAKSI
    |--------------------------------------------------------------------------
    */
    Route::get('/transaksi/create', [TransactionController::class, 'create'])
        ->name('transaksi.create');

    Route::post('/transaksi', [TransactionController::class, 'store'])
        ->name('transaksi.store');

    Route::get('/riwayat-transaksi', function () {
        $transactions = \App\Models\Transaction::where('user_id', Auth::id())
            ->latest()
            ->get();

        $scans = \App\Models\Scan::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('riwayat-transaksi', compact('transactions', 'scans'));
    })->name('riwayat-transaksi');

    // Notifications
    Route::get('/notifications/data', [App\Http\Controllers\User\NotificationController::class, 'getData'])->name('notifications.data');
    Route::get('/notifications', [App\Http\Controllers\User\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [App\Http\Controllers\User\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [App\Http\Controllers\User\NotificationController::class, 'markAllRead'])->name('notifications.readAll');

    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/users', [UserController::class, 'index'])
            ->name('users.index');

        Route::get('/users/{user}/edit', [UserController::class, 'edit'])
            ->name('users.edit');

        Route::put('/users/{user}', [UserController::class, 'update'])
            ->name('users.update');

        Route::delete('/users/{user}', [UserController::class, 'destroy'])
            ->name('users.destroy');

        /*
        |--------------------------------------------------------------------------
        | ADMIN TRANSAKSI
        |--------------------------------------------------------------------------
        */
        Route::get('/transaksi', [AdminTransactionController::class, 'index'])
            ->name('transaksi.index');

        Route::get('/riwayat-transaksi', [AdminTransactionController::class, 'riwayat'])
            ->name('transaksi.riwayat');

        Route::post('/transaksi/{id}/approve', [AdminTransactionController::class, 'approve'])
            ->name('transaksi.approve');

        Route::post('/transaksi/{id}/reject', [AdminTransactionController::class, 'reject'])
            ->name('transaksi.reject');

        /*
        |--------------------------------------------------------------------------
        | ADMIN SCAN CONFIRMATION
        |--------------------------------------------------------------------------
        */
        Route::get('/scans', [ScanConfirmationController::class, 'index'])
            ->name('scans.index');

        Route::get('/scans/{scan}', [ScanConfirmationController::class, 'show'])
            ->name('scans.show');

        Route::post('/scans/{scan}/approve', [ScanConfirmationController::class, 'approve'])
            ->name('scans.approve');

        Route::post('/scans/{scan}/reject', [ScanConfirmationController::class, 'reject'])
            ->name('scans.reject');

        /*
        |--------------------------------------------------------------------------
        | ADMIN REWARDS
        |--------------------------------------------------------------------------
        */
        Route::resource('rewards', RewardController::class);

        /*
        |--------------------------------------------------------------------------
        | ADMIN SPK (SAW METHOD)
        |--------------------------------------------------------------------------
        */
        Route::match(['get', 'post'], '/saw', [SawController::class, 'index'])
            ->name('saw.index');

        /*
        |--------------------------------------------------------------------------
        | ADMIN SETTINGS (FIXED)
        |--------------------------------------------------------------------------
        */
        Route::get('/settings', function () {
            return view('admin.settings');
        })->name('settings');
    });
