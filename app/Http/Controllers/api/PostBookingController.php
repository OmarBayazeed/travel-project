<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CarBookingResource;
use App\Http\Resources\CruiseBookingResource;
use App\Http\Resources\PackageBookingResource;
use App\Http\Resources\TourBookingResource;
use App\Models\Car;
use App\Models\CarBooking;
use App\Models\Cruise;
use App\Models\CruiseBooking;
use App\Models\Package;
use App\Models\PackageBooking;
use App\Models\Tour;
use App\Models\TourBooking;
use App\Services\PayPalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostBookingController extends Controller
{
    public function bookTour(Request $request, PayPalService $paypal)
    {
        $validator = Validator::make($request->all(), [
            'tour_id' => 'required|exists:tours,id',
            'number_of_guests' => 'required|integer|min:1',
            'full_name' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20',
            'nationality' => 'required|string', // ISO 3166-1 alpha-2
            'total_price' => 'required|numeric|min:0',
            'special_requests' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $tour = Tour::where('id', $request->tour_id)->where('is_active', true)->first();

        if (!$tour) {
            return response()->json(['message' => 'Tour not found or inactive'], 404);
        }

        // Create PayPal order
        $order = $paypal->createOrder($request->total_price, $tour->currency);

        if (isset($order['id']) && in_array($order['status'], ['CREATED', 'APPROVED'])) {
            $booking = TourBooking::create([
                'tour_id' => $request->tour_id,
                'client_id' => auth('api')->user()->id,
                'number_of_guests' => $request->number_of_guests,
                'full_name' => $request->full_name,
                'phone' => $request->phone,
                'nationality' => $request->nationality,
                'special_requests' => $request->special_requests,
                'total_price' => $request->total_price,
                'currency' => $tour->currency,
                'payment_status' => 'pending',
            ]);
            $booking->payment()->create([
                'paypal_order_id' => $order['id'],
                'amount' => $request->total_price,
                'currency' => $tour->currency,
            ]);
            foreach ($order['links'] as $link) {
                if ($link['rel'] === 'approve') {
                    // Send PayPal approval URL back to frontend
                    return response()->json([
                        'approval_url' => $link['href']
                    ]);
                }
            }
        }
        return response()->json(['message' => 'Payment creation failed'], 500);
    }

    public function bookPackage(Request $request, PayPalService $paypal)
    {
        $validator = Validator::make($request->all(), [
            'package_id' => 'required|exists:packages,id',
            'number_of_guests' => 'required|integer|min:1',
            'full_name' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20',
            'nationality' => 'required|string',
            'special_requests' => 'nullable|string|max:1000',
            'total_price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $package = Package::where('id', $request->package_id)->where('is_active', true)->first();

        if (!$package) {
            return response()->json(['message' => 'Package not found or inactive'], 404);
        }

        // Create PayPal order
        $order = $paypal->createOrder($request->total_price, $package->currency);

        if (isset($order['id']) && $order['status'] === 'CREATED') {
            $booking = PackageBooking::create([
                'package_id' => $request->package_id,
                'client_id' => auth('api')->user()->id,
                'number_of_guests' => $request->number_of_guests,
                'full_name' => $request->full_name,
                'phone' => $request->phone,
                'nationality' => $request->nationality,
                'special_requests' => $request->special_requests,
                'total_price' => $request->total_price,
                'currency' => $package->currency,
                'payment_status' => 'pending',
            ]);
            $booking->payment()->create([
                'paypal_order_id' => $order['id'],
                'amount' => $request->total_price,
                'currency' => $package->currency,
            ]);
            foreach ($order['links'] as $link) {
                if ($link['rel'] === 'approve') {
                    // Send PayPal approval URL back to frontend
                    return response()->json([
                        'approval_url' => $link['href']
                    ]);
                }
            }
        }
        return response()->json(['message' => 'Payment creation failed'], 500);
    }

    public function bookCruise(Request $request, PayPalService $paypal)
    {
        $validator = Validator::make($request->all(), [
            'cruise_id' => 'required|exists:cruises,id',
            'number_of_guests' => 'required|integer|min:1',
            'full_name' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20',
            'nationality' => 'required|string',
            'special_requests' => 'nullable|string|max:1000',
            'total_price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $cruise = Cruise::where('id', $request->cruise_id)->where('is_active', true)->first();

        if (!$cruise) {
            return response()->json(['message' => 'Cruise not found or inactive'], 404);
        }

        // Create PayPal order
        $order = $paypal->createOrder($request->total_price, $cruise->currency);

        if (isset($order['id']) && $order['status'] === 'CREATED') {
            $booking = CruiseBooking::create([
                'cruise_id' => $request->cruise_id,
                'client_id' => auth('api')->user()->id,
                'number_of_guests' => $request->number_of_guests,
                'full_name' => $request->full_name,
                'phone' => $request->phone,
                'nationality' => $request->nationality,
                'special_requests' => $request->special_requests,
                'total_price' => $request->total_price,
                'currency' => $cruise->currency,
                'payment_status' => 'pending',
            ]);
            $booking->payment()->create([
                'paypal_order_id' => $order['id'],
                'amount' => $request->total_price,
                'currency' => $cruise->currency,
            ]);
            foreach ($order['links'] as $link) {
                if ($link['rel'] === 'approve') {
                    // Send PayPal approval URL back to frontend
                    return response()->json([
                        'approval_url' => $link['href']
                    ]);
                }
            }
        }
        return response()->json(['message' => 'Payment creation failed'], 500);
    }

    public function rentCar(Request $request, PayPalService $paypal)
    {
        $validator = Validator::make($request->all(), [
            'car_id' => 'required|exists:cars,id',
            'pickup_date' => 'required|date|after_or_equal:today',
            'dropoff_date' => 'required|date|after:pickup_date',
            'pickup_location' => 'required|string|max:255',
            'dropoff_location' => 'required|string|max:255',
            'total_price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $car = Car::where('id', $request->car_id)->where('is_active', true)->where('availability', true)->first();

        if (!$car) {
            return response()->json(['message' => 'Car not available'], 404);
        }

        $pickup = now()->parse($request->pickup_date);
        $dropoff = now()->parse($request->dropoff_date);
        $total_days = $pickup->diffInDays($dropoff);

        if ($total_days < 1) {
            return response()->json(['message' => 'Minimum rental is 1 day'], 400);
        }

        // $total_price = $car->price_per_day * $total_days;

        // Create PayPal order
        $order = $paypal->createOrder($request->total_price, $car->currency);

        if (isset($order['id']) && $order['status'] === 'CREATED') {
            $booking = CarBooking::create([
                'car_id' => $request->car_id,
                'client_id' => auth('api')->user()->id,
                'pickup_date' => $request->pickup_date,
                'dropoff_date' => $request->dropoff_date,
                'pickup_location' => $request->pickup_location,
                'dropoff_location' => $request->dropoff_location,
                'total_days' => $total_days,
                'full_name' => $request->full_name,
                'phone' => $request->phone,
                'nationality' => $request->nationality,
                'special_requests' => $request->special_requests,
                'total_price' => $request->total_price,
                'currency' => $car->currency,
                'status' => 'pending',
                'payment_status' => 'pending',
            ]);
            $booking->payment()->create([
                'paypal_order_id' => $order['id'],
                'amount' => $request->total_price,
                'currency' => $car->currency,
            ]);
            foreach ($order['links'] as $link) {
                if ($link['rel'] === 'approve') {
                    // Send PayPal approval URL back to frontend
                    return response()->json([
                        'approval_url' => $link['href']
                    ]);
                }
            }
        }
        return response()->json(['message' => 'Payment creation failed'], 500);
    }
}
