<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocationController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('locations')->middleware('throttle:60,1')->group(function () {
    Route::post('/', [LocationController::class, 'store']); // Konum Ekleme
    Route::get('/', [LocationController::class, 'index']); // Konum Listeleme
    Route::get('/{id}', [LocationController::class, 'show']); // Konum Detayı
    Route::put('/{id}', [LocationController::class, 'update']); // Konum Güncelleme
    Route::delete('/{id}', [LocationController::class, 'destroy']); // Konum Silme
    Route::post('/calculate-distance', [LocationController::class, 'calculateDistanceWithCoordinates']); // Kullanıcıdan gelen koordinatlara göre mesafe hesaplama
    Route::get('/calculate-distance/{departure}/{destination}', [LocationController::class, 'calculateDistanceWithID']); // departure alanına mevcut konumunuzun ID'si, destination alanına hedef konumun ID'sini yazmalısınız...
    Route::post('multi/create', [LocationController::class, 'multiLocationCreate']);
});


// Tüm apilere dakikada maksimum 60 istek atılabilecek şekilde sınırlandırıldı dilerseniz bu sayıyı değiştirerek istek sayısını kendiniz belirleyebilirsiniz.