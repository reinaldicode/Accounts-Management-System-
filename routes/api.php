<?php
// routes/api.php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

/*
|--------------------------------------------------------------------------
| API Routes for SSO Handshake
|--------------------------------------------------------------------------
| Route ini hanya bisa diakses jika Client mengirim Header:
| X-System-Code dan X-API-Key yang valid (sesuai middleware ams.api_key)
*/

Route::middleware(['ams.api_key'])->group(function () {
    
    // Endpoint Utama: Docoline kirim Token -> AMS balas Data User & Role JSON
    Route::post('/sso/validate-token', [ApiController::class, 'validateToken']);

    // Endpoint 2 (Opsional): Cek Status Session (Untuk logout otomatis jika AMS logout)
    Route::post('/sso/check-session', [ApiController::class, 'checkSession']);

});