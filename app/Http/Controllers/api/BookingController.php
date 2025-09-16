<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use App\Http\Resources\CarBookingResource;
use App\Http\Resources\CruiseBookingResource;
use App\Http\Resources\PackageBookingResource;
use App\Http\Resources\TourBookingResource;
use App\Models\CarBooking;
use App\Models\CruiseBooking;
use App\Models\PackageBooking;
use App\Models\TourBooking;

class BookingController extends Controller
{
    public function __construct() {
        Config::set('auth.defaults.guard','api');
        Config::set('auth.defaults.passwords','clients');
    }

    /**
     * Get all bookings for the authenticated client,
     * only including those where the related item is active.
     */
    public function index(Request $request)
    {
        $client = auth('api')->user();

        // Load tour bookings where the tour is active
        $tours = TourBooking::where('client_id', $client->id)
            ->whereHas('tour', function ($query) {
                $query->where('is_active', true);
            })
            ->with('tour') // eager load tour data
            ->get();

        // Load package bookings where the package is active
        $packages = PackageBooking::where('client_id', $client->id)
            ->whereHas('package', function ($query) {
                $query->where('is_active', true);
            })
            ->with('package')
            ->get();

        // Load cruise bookings where the cruise is active
        $cruises = CruiseBooking::where('client_id', $client->id)
            ->whereHas('cruise', function ($query) {
                $query->where('is_active', true);
            })
            ->with('cruise')
            ->get();

        // Load car bookings where the car is active
        $cars = CarBooking::where('client_id', $client->id)
            ->whereHas('car', function ($query) {
                $query->where('is_active', true);
            })
            ->with('car')
            ->get();

        return response()->json([
            'tours'    => TourBookingResource::collection($tours),
            'packages' => PackageBookingResource::collection($packages),
            'cruises'  => CruiseBookingResource::collection($cruises),
            'cars'     => CarBookingResource::collection($cars),
        ], 200);
    }

    public function bookedTours(Request $request)
    {
        $client = auth('api')->user();

        // Load tour bookings where the tour is active
        $tours = TourBooking::where('client_id', $client->id)
            ->whereHas('tour', function ($query) {
                $query->where('is_active', true);
            })
            ->with('tour') // eager load tour data
            ->get();

        return response()->json([
            'tours'    => TourBookingResource::collection($tours),
        ], 200);
    }

    public function bookedPackages(Request $request)
    {
        $client = auth('api')->user();

        // Load package bookings where the package is active
        $packages = PackageBooking::where('client_id', $client->id)
            ->whereHas('package', function ($query) {
                $query->where('is_active', true);
            })
            ->with('package')
            ->get();

        return response()->json([
            'packages' => PackageBookingResource::collection($packages),
        ], 200);
    }

    public function bookedCruises(Request $request)
    {
        $client = auth('api')->user();

        // Load cruise bookings where the cruise is active
        $cruises = CruiseBooking::where('client_id', $client->id)
            ->whereHas('cruise', function ($query) {
                $query->where('is_active', true);
            })
            ->with('cruise')
            ->get();

        return response()->json([
            'cruises'  => CruiseBookingResource::collection($cruises),
        ], 200);
    }

    public function bookedCars(Request $request)
    {
        $client = auth('api')->user();

        // Load car bookings where the car is active
        $cars = CarBooking::where('client_id', $client->id)
            ->whereHas('car', function ($query) {
                $query->where('is_active', true);
            })
            ->with('car')
            ->get();

        return response()->json([
            'cars'     => CarBookingResource::collection($cars),
        ], 200);
    }
}
