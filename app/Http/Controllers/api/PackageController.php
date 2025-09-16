<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PackageResource;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class PackageController extends Controller
{
    public function __construct() {
        Config::set('auth.defaults.guard','api');
        Config::set('auth.defaults.passwords','clients');
    }

    public function index()
    {
        $packages = Package::where('is_active', true)->paginate(10);
        return PackageResource::collection($packages);
    }

    public function show($id)
    {
        $package = Package::where('is_active', true)->findOrFail($id);
        return new PackageResource($package);
    }
}
