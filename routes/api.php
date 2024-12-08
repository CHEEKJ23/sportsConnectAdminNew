<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ChatMessageController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\EquipmentRentalController;




Broadcast::routes(['middleware' => ['auth:sanctum']]);
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::post('/auth/register', [App\Http\Controllers\AuthController::class, 'register']);
// Route::post('/auth/login', [App\Http\Controllers\AuthController::class, 'login']);
Route::prefix('auth')
    ->as('auth.')
    ->group(function () {

        Route::post('login', [AuthController::class, 'login'])->name('login');
        Route::post('register', [AuthController::class, 'register'])->name('register');
        Route::post('login_with_token', [AuthController::class, 'loginWithToken'])
            ->middleware('auth:sanctum')
            ->name('login_with_token');
        Route::get('logout', [AuthController::class, 'logout'])
            ->middleware('auth:sanctum')
            ->name('logout');

    });

    Route::middleware('auth:sanctum')->group(function (){
        //chat
        Route::apiResource('chat', ChatController::class)->only(['index','store','show']);
        Route::apiResource('chat_message', ChatMessageController::class)->only(['index','store']);
        Route::apiResource('user', UserController::class)->only(['index']);
        //book court
        Route::post('/search-sport-centers', [BookingController::class, 'searchSportCenters']);
        Route::post('/sport-center/{sportCenterId}/available-courts', [BookingController::class, 'getAvailableCourts']);
        Route::post('/book-court', [BookingController::class, 'bookCourt']);
        Route::get('/myBookings', [BookingController::class, 'getMyBookings']);
        Route::put('/modifyBookings/{bookingId}', [BookingController::class, 'updateBooking']);
        //equipment rental
        Route::post('/equipment-rental/check-availability', [EquipmentRentalController::class, 'checkAvailability']);
        Route::get('/equipment-rental/get-sports-centers', [EquipmentRentalController::class, 'getSportsCentersByLocation']);
        Route::get('/equipment-rental/equipment/{equipmentID}', [EquipmentRentalController::class, 'getEquipmentDetails']);
        Route::post('/equipment-rental/rent', [EquipmentRentalController::class, 'rentEquipment']);
    });



