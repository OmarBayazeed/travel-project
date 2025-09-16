<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TourResource;
use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class TourController extends Controller
{
    public function __construct() {
        Config::set('auth.defaults.guard','api');
        Config::set('auth.defaults.passwords','clients');
    }

    public function index()
    {
        $tours = Tour::where('is_active', true)->paginate(10);
        return TourResource::collection($tours);
    }

    public function show($id)
    {
        $tour = Tour::where('is_active', true)->findOrFail($id);
        return new TourResource($tour);
    }
}
