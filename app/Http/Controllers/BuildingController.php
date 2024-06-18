<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReturnResource;
use App\Models\Building;
use Illuminate\Http\Request;

class BuildingController extends Controller
{
    /**
     * Display a listing of buildings
     */
    public function index()
    {
        $buildings = Building::all();
        return response()->json(['data' => ReturnResource::collection($buildings)], 201);
    }
}
