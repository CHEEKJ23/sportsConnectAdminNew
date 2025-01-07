<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ChatMessageController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\EquipmentRentalController;
use App\Http\Controllers\DealsController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\GiftController;
use App\Http\Controllers\SportCenterController;
use App\Http\Controllers\CourtController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();
Route::group(['middleware' => ['auth', 'admin']], function () {
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


});

// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Route::get('/home',[App\Http\Controllers\HomeController::class,'index1']);

// Route::get('/home',[App\Http\Controllers\adminHomeController::class, 'adminHome'])->name('adminHome');

Route::get('/user/edit',[App\Http\Controllers\UserController::class, 'edit'])->name('usernameEdit');

Route::put('/user/update',[App\Http\Controllers\UserController::class, 'update'])->name('usernameUpdate');

Route::get('/admin/user-list',[App\Http\Controllers\UserController::class, 'showUser'])->name('userList');

Route::post('/admin/user-list/search',[App\Http\Controllers\UserController::class, 'userSearch'])->name('userSearch');

Route::get('/admin/user-list/remove/User/{id}', [App\Http\Controllers\UserController::class, 'deleteUser'])->name('deleteUser');





Route::post('/admin/rentals/complete-return', [EquipmentRentalController::class, 'completeReturn'])->name('completeReturn');

//manage deposit paid by user when renting at counter
// Route::post('/admin/rentals/receive-return-request', [EquipmentRentalController::class, 'updateDepositReturned'])->name('updateDepositReturned');

//manage return (deposit and returned quantity)
Route::post('/rentals/{rentalID}/process-return', [EquipmentRentalController::class, 'processReturn'])->name('rentals.processReturn');



Route::get('/admin/rental-returns', [EquipmentRentalController::class, 'showReturnRequests'])->name('rentalReturns');

Route::put('/admin/deals/{dealID}/approve', [DealsController::class, 'approveDeal'])->name('approveDeal');

Route::put('/admin/deals/{dealID}/reject', [DealsController::class, 'rejectDeal'])->name('rejectDeal');

Route::get('/admin/manage-deals', [DealsController::class, 'showDeals'])->name('showDeals');

// Admin can view all feedback
Route::get('/admin/feedback', [FeedbackController::class, 'index'])->name('feedback.index');
// Admin can reply to a specific feedback
Route::post('/admin/feedback/{id}/reply', [FeedbackController::class, 'reply'])->name('feedback.reply');


//reward
//reward
//reward
//reward
//reward
//reward
//reward
//reward
//reward
//reward
Route::get('/admin/redemptions', [RewardController::class, 'viewRedemptions'])->name('admin.redemptions.index');

Route::post('/admin/redemptions/{id}/status', [RewardController::class, 'updateRedemptionStatus'])->name('admin.redemptions.updateStatus');

//gift
//gift
//gift
//gift
//gift
//gift
//gift
//gift
//gift
//gift
Route::get('/admin/view/gifts', [GiftController::class, 'index'])->name('admin.gifts.index');

Route::get('/admin/create/gifts', [GiftController::class, 'create'])->name('admin.gifts.create');

Route::post('/admin/store/gifts', [GiftController::class, 'store'])->name('admin.gifts.store');

Route::get('/admin/gifts/edit/{id}', [GiftController::class, 'edit'])->name('admin.gifts.edit');

Route::put('/admin/gifts/update/{id}', [GiftController::class, 'update'])->name('admin.gifts.update');

Route::delete('/admin/gifts/delete/{id}', [GiftController::class, 'destroy'])->name('admin.gifts.destroy');

//add sport center and court
//add sport center and court
//add sport center and court
//add sport center and court
//add sport center and court
//add sport center and court
//add sport center and court
//add sport center and court
//add sport center and court
//add sport center and court

// SportCenter Routes
Route::prefix('sportcenters')->group(function () {
    Route::get('/', [SportCenterController::class, 'index'])->name('sportcenters.index'); // List all sport centers
    Route::get('/create', [SportCenterController::class, 'create'])->name('sportcenters.create'); // Show form to create a sport center
    Route::post('/', [SportCenterController::class, 'store'])->name('sportcenters.store'); // Save a new sport center
    Route::get('/{sportcenter}/edit', [SportCenterController::class, 'edit'])->name('sportcenters.edit'); // Show form to edit a sport center
    Route::post('/{sportcenter}', [SportCenterController::class, 'update'])->name('sportcenters.update'); // Update a sport center
    Route::delete('/{sportcenter}', [SportCenterController::class, 'destroy'])->name('sportcenters.destroy'); // Delete a sport center

    // Nested Court Routes
    Route::prefix('{sportcenter}/courts')->group(function () {
        Route::get('/', [CourtController::class, 'index'])->name('sportcenters.courts.index'); // List all courts for a sport center
        Route::get('/create', [CourtController::class, 'create'])->name('sportcenters.courts.create'); // Show form to create a court
        Route::post('/', [CourtController::class, 'store'])->name('sportcenters.courts.store'); // Save a new court
        Route::get('/{court}/edit', [CourtController::class, 'edit'])->name('sportcenters.courts.edit'); // Show form to edit a court
        Route::put('/{court}', [CourtController::class, 'update'])->name('sportcenters.courts.update'); // Update a court
        Route::delete('/{court}', [CourtController::class, 'destroy'])->name('sportcenters.courts.destroy'); // Delete a court
    });
});
// List all equipment
Route::get('equipment', [EquipmentRentalController::class, 'index'])->name('equipment.index');

// Show form to create new equipment
Route::get('equipment/create', [EquipmentRentalController::class, 'create'])->name('equipment.create');

// Store a newly created equipment
Route::post('equipment', [EquipmentRentalController::class, 'store'])->name('equipment.store');

// Show a specific equipment (optional, if needed)
Route::get('equipment/{equipment}', [EquipmentRentalController::class, 'show'])->name('equipment.show');

// Show form to edit existing equipment
Route::get('equipment/{equipment}/edit', [EquipmentRentalController::class, 'edit'])->name('equipment.edit');

// Update existing equipment
Route::put('equipment/{equipment}', [EquipmentRentalController::class, 'update'])->name('equipment.update');

// Delete existing equipment
Route::delete('equipment/{equipment}', [EquipmentRentalController::class, 'destroy'])->name('equipment.destroy');


//manage rentals (deposit) and update returned deposit
Route::get('rentals', [EquipmentRentalController::class, 'indexForRentals'])->name('admin.rentals.index');

Route::post('rentals/{rentalID}/update-deposit', [EquipmentRentalController::class, 'updateDeposit'])->name('admin.rentals.updateDeposit');

//calendar
//calendar
//calendar
//calendar
//calendar
//calendar
//calendar
//calendar
//calendar
//calendar
//manage court booking
Route::get('manageCourtBooking', [BookingController::class, 'showBookingCalendar'])->name('manageCourtBooking');

Route::post('/admin/bookings/create', [BookingController::class, 'adminCreateBooking'])->name('admin.bookings.create');

Route::get('/admin/sport-centers/{sportCenterId}/court-types', [BookingController::class, 'getCourtTypes']);
Route::get('/admin/sport-centers/{sportCenterId}/courts/{courtType}', [BookingController::class, 'getCourts']);




// Display the search form
Route::get('/admin/overview', [BookingController::class, 'showCourtAvailabilityForm'])->name('admin.overviewForm');

// Process the search request
Route::post('/admin/overview', [BookingController::class, 'searchCourtAvailability'])->name('admin.overview');

//cancel booking 
Route::post('/admin/bookings/{id}/cancel', [BookingController::class, 'cancel'])->name('admin.bookings.cancel');
