<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CarResource;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class CarController extends Controller
{
    public function __construct() {
        Config::set('auth.defaults.guard','api');
        Config::set('auth.defaults.passwords','clients');
    }
    /**
     * Get all active cars.
     */
    public function index()
    {
        $cars = Car::where('is_active', true)->paginate(10);
        return CarResource::collection($cars);
    }

    /**
     * Get a single car by ID.
     */
    public function show($id)
    {
        $car = Car::where('is_active', true)->findOrFail($id);
        return new CarResource($car);
    }
}
