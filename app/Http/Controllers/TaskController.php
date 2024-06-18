<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\ReturnResource;
use App\Models\Building;
use App\Models\Task;
use App\Models\TasksStatuses;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Return a list of tasks related to a building
     */
    public function tasksByBuilding(string $id)
    {
        $tasks = Task::with([
            'task_status:id,name',
            'task_creator:id,name', 
            'task_responsible:id,name', 
            'comments:id,comment,task_id,user_id', 
            'comments.user:id,name'])
        ->where('building_id', '=', $id)
        ->orderBy('id')
        ->get();

        return response()->json(['data' => ReturnResource::collection($tasks)], 200);
    }

    /**
     * Create a new task
     */
    public function store(StoreTaskRequest $request)
    {
        $data = $request->validated();
        
        $creator = User::with([
            'user_types'
        ])->find($data['creator_id']);

        if (!$creator || strtolower($creator->user_types->name) != "owner") {
            throw new HttpResponseException(response()->json(['errors' => "Error creating task!", "message" => "The task creator must be of the type owner!"], 422));
        }

        $building = Building::findOrFail($data['building_id']);

        if (!$building || ($building->user_id != $creator->id)) {
            throw new HttpResponseException(response()->json(['errors' => "Error creating task!", "message" => "The building related to the task must belong to the creator informed in the task payload."], 422));
        }

        try {
            $task = Task::create($data);
        } catch (\Exception $e) {
            throw new HttpResponseException(response()->json(['errors' => "Error creating task!", "message" => "Verify the data of the payload and try again!"], 422));
        }
        return response()->json(['data' => $task], 201);
    }
    
    /**
     * Update a task using the id
     */
    public function update(UpdateTaskRequest $request, $id)
    {
        $data = $request->validated();
        $taskUpdated = Task::findOrFail($id);
        $status = TasksStatuses::where("name", 'ilike', 'completed')->first();

        if (isset($data['status_id']) && $data['status_id'] == $status->id) {
            $data['completed_on'] = now();
        }

        if ($request->has('completed_on')) {
            $data['completed_on'] = Carbon::parse($request->input('completed_on'))->format('Y-m-d H:i:s');
        }

        try {
            $taskUpdated->update($data);
        } catch (\Exception $e) {
            throw new HttpResponseException(response()->json(['errors' => "Error updating task!", "message" => "Verify the data of the payload and try again!"], 422));
        }
        
        return response()->json(['data' => $taskUpdated], 200);
    }
}
