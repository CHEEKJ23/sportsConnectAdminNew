<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ChatMessageController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\EquipmentRentalController;
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