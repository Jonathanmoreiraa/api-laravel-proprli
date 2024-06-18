<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\FilterRequest;
use App\Http\Resources\ReturnResource;
use App\Models\Building;
use App\Models\Filter;
use App\Models\Task;
use App\Models\TasksStatuses;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FiltersController extends Controller
{
    /* 
    * This GET method uses query string to search the results, the possible parameters are 
    * "date_start", "date_end", "status", "creator", "responsible", "building"
    */
    public function index(FilterRequest $request)
    {
        $query = Task::query();

        /* 
        * Tasks that was created after date_start
        */
        if ($request->has('date_start')) {
            $query->where('created_at', '>=', Carbon::parse($request->query('date_end'))->startOfDay());
        }

        /* 
        * Tasks that was created before date_end
        */
        if ($request->has('date_end')) {
            $query->where('created_at', '<=', Carbon::parse($request->query('date_end'))->endOfDay());
        }

        /* 
        * Tasks with the informed status
        */
        if ($request->has('status')) {
            $status = $request->query('status');
            if (!is_numeric($request->query('status'))) {
                $status = TasksStatuses::where("name", "ilike", "%" . $status . "%")->first();
                $status = $status->id;
            }
            $query->where('status_id', '=', $status);
        }

        /* 
        * Tasks with the informed creator
        */
        if ($request->has('creator')) {
            $creatorId = $request->query('creator');
        
            if (!is_numeric($creatorId)) {
                return response()->json(['errors' => "The creator query must be a number!"], 422);
            }
        
            $user = User::find($creatorId);
        
            if (!$user) {
                return response()->json(['errors' => "The creator with the given id was not found!"], 422);
            }
        
            $query->where('creator_id', $creatorId);
        }
        
        /* 
        * Tasks with the informed responsible
        */
        if ($request->has('responsible')) {
            if (!is_numeric($request->query('responsible'))) {
                return response()->json(['errors' => "The responsible query must be a number!"], 422);
            }
        
            $user = User::find($request->query('responsible'));
        
            if (!$user) {
                return response()->json(['errors' => "The responsible with the given id was not found!"], 422);
            }

            $query->where('assignee_id', '=', $request->query('responsible'));
        }

        /* 
        * Tasks with the informed building
        */
        if ($request->has('building')) {
            if (!is_numeric($request->query('building'))) {
                return response()->json(['errors' => "The building query must be a number!"], 422);
            }
        
            $building = Building::find($request->query('building'));
        
            if (!$building) {
                return response()->json(['errors' => "The building with the given id was not found!"], 422);
            }

            $query->where('building_id', '=', $request->query('building'));
        }

        $datas = $query->get();

        return response()->json(['data' => ReturnResource::collection($datas)], 200);
    }
}
