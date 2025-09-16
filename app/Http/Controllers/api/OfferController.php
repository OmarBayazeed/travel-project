<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\CarResource;
use App\Http\Resources\TourResource;
use App\Http\Resources\PackageResource;
use App\Http\Resources\CruiseResource;
use App\Models\Car;
use App\Models\Tour;
use App\Models\Package;
use App\Models\Cruise;

class OfferController extends Controller
{
    public function index()
    {
        $cars = Car::where('is_active', true)
            ->where('is_on_offer', true)
            ->get();

        $tours = Tour::where('is_active', true)
            ->where('is_on_offer', true)
            ->get();

        $packages = Package::where('is_active', true)
            ->where('is_on_offer', true)
            ->get();

        $cruises = Cruise::where('is_active', true)
            ->where('is_on_offer', true)
            ->get();

        return response()->json([
            'cars'     => CarResource::collection($cars),
            'tours'    => TourResource::collection($tours),
            'packages' => PackageResource::collection($packages),
            'cruises'  => CruiseResource::collection($cruises),
        ]);
    }
}
