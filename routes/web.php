<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'Laravel is running',
        'php_version' => PHP_VERSION,
        'env' => config('app.env')
    ]);
})->name('home');

Route::get('/app/logout', function () {
    auth()->logout();

    return redirect()->to('/');
});

Route::get('/healthz', function () {
    try {
        // Verificar conexión a base de datos
        \DB::connection()->getPdo();
        return response()->json(['status' => 'ok', 'database' => 'connected'], 200);
    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    }
});

Route::get('/test-session', function () {
    return [
        'authenticated' => auth()->check(),
        'user_id' => auth()->id(),
        'session_id' => session()->getId(),
        'session_data' => session()->all(),
    ];
})->middleware('web');

Route::get('/test-email-verification', function () {
    $user = auth()->user();

    return [
        'user_id' => $user?->id,
        'email' => $user?->email,
        'email_verified_at' => $user?->email_verified_at,
        'is_verified' => $user?->hasVerifiedEmail(),
    ];
})->middleware('web');

Route::get('/test-dashboard-access', function () {
    try {
        $dashboardClass = \Filament\Pages\Dashboard::class;

        return [
            'can_access_default' => $dashboardClass::canAccess(),
            'user_authenticated' => auth()->check(),
            'user_id' => auth()->id(),
        ];
    } catch (\Exception $e) {
        return [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ];
    }
})->middleware('web');

Route::get('/test-filament-access', function () {
    $user = auth()->user();

    // Test 1: Usuario básico
    $data = [
        'authenticated' => auth()->check(),
        'user_id' => $user?->id,
        'user_email' => $user?->email,
        'email_verified' => $user?->email_verified_at !== null,
    ];

    // Test 2: Intentar acceder al panel programáticamente
    try {
        $panel = \Filament\Facades\Filament::getPanel('app');
        $data['panel_found'] = true;
        $data['panel_id'] = $panel->getId();
        $data['panel_path'] = $panel->getPath();

        // Test 3: Ver si el panel tiene alguna restricción
        $data['panel_auth_guard'] = $panel->getAuthGuard();

    } catch (\Exception $e) {
        $data['panel_error'] = $e->getMessage();
    }

    return $data;
})->middleware('web');
