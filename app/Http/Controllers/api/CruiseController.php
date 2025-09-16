<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CruiseResource;
use App\Models\Cruise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class CruiseController extends Controller
{
    public function __construct() {
        Config::set('auth.defaults.guard','api');
        Config::set('auth.defaults.passwords','clients');
    }

    public function index()
    {
        $cruises = Cruise::where('is_active', true)->paginate(10);
        return CruiseResource::collection($cruises);
    }

    public function show($id)
    {
        $cruise = Cruise::where('is_active', true)->findOrFail($id);
        return new CruiseResource($cruise);
    }
}
