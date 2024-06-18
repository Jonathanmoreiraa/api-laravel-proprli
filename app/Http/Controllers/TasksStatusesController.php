<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReturnResource;
use App\Models\TasksStatuses;

class TasksStatusesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $status = TasksStatuses::all();        
        return response()->json(['data' => ReturnResource::collection($status)], 200);

    }
}
