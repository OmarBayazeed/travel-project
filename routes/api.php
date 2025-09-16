<?php

use App\Http\Controllers\api\BookingController;
use App\Http\Controllers\api\CarController;
use App\Http\Controllers\api\ClientAuthController;
use App\Http\Controllers\api\CruiseController;
use App\Http\Controllers\api\OfferController;
use App\Http\Controllers\api\PackageController;
use App\Http\Controllers\api\PayPalController;
use App\Http\Controllers\api\PostBookingController;
use App\Http\Controllers\api\TourController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [ClientAuthController::class, 'login']);
Route::post('/register', [ClientAuthController::class, 'register']);
Route::post('/verify', [ClientAuthController::class, 'verify_email']);
Route::post('/google', [ClientAuthController::class, 'google']);
Route::post('/facebook', [ClientAuthController::class, 'facebook']);
Route::post('/forgot_password', [ClientAuthController::class, 'send_email']);
Route::post('/reset_password_code', [ClientAuthController::class, 'reset_password_code']);
Route::post('/reset_password', [ClientAuthController::class, 'reset_password']);

// Cars
Route::get('/cars', [CarController::class, 'index']);
Route::get('/cars/{id}', [CarController::class, 'show']);

// Cruises
Route::get('/cruises', [CruiseController::class, 'index']);
Route::get('/cruises/{id}', [CruiseController::class, 'show']);

// Packages
Route::get('/packages', [PackageController::class, 'index']);
Route::get('/packages/{id}', [PackageController::class, 'show']);

// Tours
Route::get('/tours', [TourController::class, 'index']);
Route::get('/tours/{id}', [TourController::class, 'show']);

// offers
Route::get('/offers', [OfferController::class, 'index']);


// payment
Route::get('paypal/success', [PayPalController::class, 'paypalSuccess'])->name('paypal.success');
Route::get('paypal/cancel', [PayPalController::class, 'paypalCancel'])->name('paypal.cancel');





Route::group([
    'middleware' => ['jwt.verify'],
], function ($router) {
    #####auth
    Route::post('/logout', [ClientAuthController::class, 'logout']);
    Route::post('/refresh', [ClientAuthController::class, 'refresh']);
    Route::get('/user-profile', [ClientAuthController::class, 'userProfile']);

    // client get booking routes
    Route::get('/bookings', [BookingController::class, 'index']);
    Route::get('/bookedTours', [BookingController::class, 'bookedTours']);
    Route::get('/bookedPackages', [BookingController::class, 'bookedPackages']);
    Route::get('/bookedCruises', [BookingController::class, 'bookedCruises']);
    Route::get('/bookedCars', [BookingController::class, 'bookedCars']);

    // client post booking routes
    Route::post('/book/tour', [PostBookingController::class, 'bookTour']);
    Route::post('/book/package', [PostBookingController::class, 'bookPackage']);
    Route::post('/book/cruise', [PostBookingController::class, 'bookCruise']);
    Route::post('/rent/car', [PostBookingController::class, 'rentCar']);
});













Route::get('/img/{path}/{name}', function(String $path, String $name){
    // Assuming $name contains the name of the uploaded file
    $filePath = storage_path('app/public/'. $path. '/' . $name);

    // Check if the file exists
    if (file_exists($filePath)) {
        return response()->file("$filePath");
    } else {
        return 'file not found';
    }
    // if (file_exists($path . '/' . $name)) {
    //     return response()->file("$path/$name");
    // }
});
