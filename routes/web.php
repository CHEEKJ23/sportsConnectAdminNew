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

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

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
Route::get('/admin/redemptions', [RewardController::class, 'viewRedemptions']);

Route::post('/admin/redemptions/{id}/status', [RewardController::class, 'updateRedemptionStatus']);

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