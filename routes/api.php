<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ScanController;
use App\Http\Controllers\Api\PointsController;
use App\Http\Controllers\Api\HistoryController;
use App\Http\Controllers\Api\RewardController;
use App\Http\Controllers\API\Admin\UserController as AdminUserController;
use App\Models\Scan;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

/*
|--------------------------------------------------------------------------
| API Routes - Manual Token Validation
|--------------------------------------------------------------------------
*/

// DEBUG ENDPOINT
Route::get('/debug-token', function (Request $request) {
    $token = $request->bearerToken();
    $token = trim($token, '"'); // Remove quotes
    $accessToken = PersonalAccessToken::findToken($token);

    return response()->json([
        'bearer_token' => $token ? substr($token, 0, 20) . '...' : null,
        'token_found' => $accessToken !== null,
        'user' => $accessToken ? $accessToken->tokenable : null,
        'auth_header' => $request->header('Authorization'),
    ]);
});

// PUBLIC - No Auth Required
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// PROTECTED - Require Token
Route::post('/scan', function (Request $request) {
    $token = $request->bearerToken();
    $token = trim($token, '"'); // Remove quotes
    $accessToken = PersonalAccessToken::findToken($token);

    if (!$accessToken) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $request->setUserResolver(fn() => $accessToken->tokenable);
    return app(ScanController::class)->store($request);
});

Route::get('/scans', function (Request $request) {
    $token = $request->bearerToken();
    $token = trim($token, '"'); // Remove quotes
    $accessToken = PersonalAccessToken::findToken($token);

    if (!$accessToken) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $request->setUserResolver(fn() => $accessToken->tokenable);
    return app(ScanController::class)->userScans($request);
});

Route::get('/scan/{scan}', function (Request $request, Scan $scan) {
    $token = $request->bearerToken();
    $token = trim($token, '"'); // Remove quotes
    $accessToken = PersonalAccessToken::findToken($token);

    if (!$accessToken) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $request->setUserResolver(fn() => $accessToken->tokenable);
    return app(ScanController::class)->getScanStatus($scan);
});

Route::get('/points', function (Request $request) {
    $token = $request->bearerToken();
    $token = trim($token, '"'); // Remove quotes
    $accessToken = PersonalAccessToken::findToken($token);

    if (!$accessToken) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $request->setUserResolver(fn() => $accessToken->tokenable);
    return app(PointsController::class)->index($request);
});

Route::get('/history', function (Request $request) {
    $token = $request->bearerToken();
    $token = trim($token, '"'); // Remove quotes
    $accessToken = PersonalAccessToken::findToken($token);

    if (!$accessToken) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $request->setUserResolver(fn() => $accessToken->tokenable);
    return app(HistoryController::class)->index($request);
});

Route::get('/rewards', function (Request $request) {
    $token = $request->bearerToken();
    $token = trim($token, '"'); // Remove quotes
    $accessToken = PersonalAccessToken::findToken($token);

    if (!$accessToken) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $request->setUserResolver(fn() => $accessToken->tokenable);
    return app(RewardController::class)->index();
});

Route::post('/redeem', function (Request $request) {
    $token = $request->bearerToken();
    $token = trim($token, '"'); // Remove quotes
    $accessToken = PersonalAccessToken::findToken($token);

    if (!$accessToken) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $request->setUserResolver(fn() => $accessToken->tokenable);
    return app(RewardController::class)->redeem($request);
});


Route::get('/me', function (Request $request) {
    $token = $request->bearerToken();
    $token = trim($token, '"'); // Remove quotes
    $accessToken = PersonalAccessToken::findToken($token);

    if (!$accessToken) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $request->setUserResolver(fn() => $accessToken->tokenable);
    return app(AuthController::class)->me($request);
});

Route::get('/dashboard', function (Request $request) {
    $token = $request->bearerToken();
    $token = trim($token, '"'); // Remove quotes
    $accessToken = PersonalAccessToken::findToken($token);

    if (!$accessToken) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $request->setUserResolver(fn() => $accessToken->tokenable);
    return app(DashboardController::class)->index($request);
});


Route::post('/update-profile', function (Request $request) {
    $token = $request->bearerToken();
    $token = trim($token, '"'); // Remove quotes
    $accessToken = PersonalAccessToken::findToken($token);

    if (!$accessToken) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $request->setUserResolver(fn() => $accessToken->tokenable);
    return app(AuthController::class)->updateProfile($request);
});

Route::post('/logout', function (Request $request) {
    $token = $request->bearerToken();
    $token = trim($token, '"'); // Remove quotes
    $accessToken = PersonalAccessToken::findToken($token);

    if (!$accessToken) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $request->setUserResolver(fn() => $accessToken->tokenable);
    $request->setUserResolver(fn() => $accessToken->tokenable);
    return app(AuthController::class)->logout($request);
});

// ADMIN - Dashboard
Route::get('/admin/dashboard', function (Request $request) {
    $token = $request->bearerToken();
    $token = trim($token, '"'); // Remove quotes
    $accessToken = PersonalAccessToken::findToken($token);

    if (!$accessToken) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $request->setUserResolver(fn() => $accessToken->tokenable);
    return app(App\Http\Controllers\Api\Admin\DashboardController::class)->index();
});

// ADMIN - User Management
Route::get('/admin/users', function (Request $request) {
    $token = $request->bearerToken();
    $token = trim($token, '"'); // Remove quotes
    $accessToken = PersonalAccessToken::findToken($token);

    if (!$accessToken) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $request->setUserResolver(fn() => $accessToken->tokenable);
    return app(App\Http\Controllers\Api\Admin\UserController::class)->index();
});

Route::get('/admin/users/{id}', function (Request $request, $id) {
    $token = $request->bearerToken();
    $token = trim($token, '"'); // Remove quotes
    $accessToken = PersonalAccessToken::findToken($token);

    if (!$accessToken) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $request->setUserResolver(fn() => $accessToken->tokenable);
    return app(App\Http\Controllers\Api\Admin\UserController::class)->show($id);
});

Route::post('/admin/users/{id}/update', function (Request $request, $id) {
    $token = $request->bearerToken();
    $token = trim($token, '"'); // Remove quotes
    $accessToken = PersonalAccessToken::findToken($token);

    if (!$accessToken) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $request->setUserResolver(fn() => $accessToken->tokenable);
    return app(App\Http\Controllers\Api\Admin\UserController::class)->update($request, $id);
});

Route::delete('/admin/users/{id}', function (Request $request, $id) {
    $token = $request->bearerToken();
    $token = trim($token, '"'); // Remove quotes
    $accessToken = PersonalAccessToken::findToken($token);

    if (!$accessToken) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $request->setUserResolver(fn() => $accessToken->tokenable);
    return app(App\Http\Controllers\Api\Admin\UserController::class)->destroy($request, $id);
});

// ADMIN - Transaction Management
Route::get('/admin/transactions', function (Request $request) {
    $token = $request->bearerToken();
    $token = trim($token, '"'); // Remove quotes
    $accessToken = PersonalAccessToken::findToken($token);

    if (!$accessToken) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $request->setUserResolver(fn() => $accessToken->tokenable);
    return app(App\Http\Controllers\Api\Admin\TransactionController::class)->index($request);
});

Route::post('/admin/transactions/{id}/approve', function (Request $request, $id) {
    $token = $request->bearerToken();
    $token = trim($token, '"'); // Remove quotes
    $accessToken = PersonalAccessToken::findToken($token);

    if (!$accessToken) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $request->setUserResolver(fn() => $accessToken->tokenable);
    return app(App\Http\Controllers\Api\Admin\TransactionController::class)->approve($id);
});

// ADMIN - Scan Confirmation
Route::get('/admin/scans', function (Request $request) {
    $token = $request->bearerToken();
    $token = trim($token, '"'); // Remove quotes
    $accessToken = PersonalAccessToken::findToken($token);

    if (!$accessToken) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $request->setUserResolver(fn() => $accessToken->tokenable);
    return app(App\Http\Controllers\Api\Admin\ScanController::class)->index($request);
});

Route::get('/admin/scans/{id}', function (Request $request, $id) {
    $token = $request->bearerToken();
    $token = trim($token, '"'); // Remove quotes
    $accessToken = PersonalAccessToken::findToken($token);

    if (!$accessToken) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $request->setUserResolver(fn() => $accessToken->tokenable);
    return app(App\Http\Controllers\Api\Admin\ScanController::class)->show($id);
});

Route::post('/admin/scans/{id}/approve', function (Request $request, $id) {
    $token = $request->bearerToken();
    $token = trim($token, '"'); // Remove quotes
    $accessToken = PersonalAccessToken::findToken($token);

    if (!$accessToken) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $request->setUserResolver(fn() => $accessToken->tokenable);
    return app(App\Http\Controllers\Api\Admin\ScanController::class)->approve($request, $id);
});

Route::post('/admin/scans/{id}/reject', function (Request $request, $id) {
    $token = $request->bearerToken();
    $token = trim($token, '"'); // Remove quotes
    $accessToken = PersonalAccessToken::findToken($token);

    if (!$accessToken) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $request->setUserResolver(fn() => $accessToken->tokenable);
    return app(App\Http\Controllers\Api\Admin\ScanController::class)->reject($request, $id);
});

// ADMIN - Reward Management
Route::get('/admin/rewards', function (Request $request) {
    $token = $request->bearerToken();
    $token = trim($token, '"'); // Remove quotes
    $accessToken = PersonalAccessToken::findToken($token);

    if (!$accessToken) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $request->setUserResolver(fn() => $accessToken->tokenable);
    return app(App\Http\Controllers\Api\Admin\RewardController::class)->index();
});

Route::post('/admin/rewards', function (Request $request) {
    $token = $request->bearerToken();
    $token = trim($token, '"'); // Remove quotes
    $accessToken = PersonalAccessToken::findToken($token);

    if (!$accessToken) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $request->setUserResolver(fn() => $accessToken->tokenable);
    return app(App\Http\Controllers\Api\Admin\RewardController::class)->store($request);
});

Route::get('/admin/rewards/{id}', function (Request $request, $id) {
    $token = $request->bearerToken();
    $token = trim($token, '"'); // Remove quotes
    $accessToken = PersonalAccessToken::findToken($token);

    if (!$accessToken) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $request->setUserResolver(fn() => $accessToken->tokenable);
    return app(App\Http\Controllers\Api\Admin\RewardController::class)->show($id);
});

Route::post('/admin/rewards/{id}/update', function (Request $request, $id) {
    $token = $request->bearerToken();
    $token = trim($token, '"'); // Remove quotes
    $accessToken = PersonalAccessToken::findToken($token);

    if (!$accessToken) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $request->setUserResolver(fn() => $accessToken->tokenable);
    return app(App\Http\Controllers\Api\Admin\RewardController::class)->update($request, $id);
});

Route::delete('/admin/rewards/{id}', function (Request $request, $id) {
    $token = $request->bearerToken();
    $token = trim($token, '"'); // Remove quotes
    $accessToken = PersonalAccessToken::findToken($token);

    if (!$accessToken) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $request->setUserResolver(fn() => $accessToken->tokenable);
    return app(App\Http\Controllers\Api\Admin\RewardController::class)->destroy($id);
});

