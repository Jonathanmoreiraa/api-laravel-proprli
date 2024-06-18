<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Comment;
use Carbon\Carbon;
use Illuminate\Http\Exceptions\HttpResponseException;

class CommentController extends Controller
{
    public function store(StoreCommentRequest $request)
    {
        $data = $request->validated();

        /* 
        * Created and the date of comment can changed in the update method
        */
        $data['date'] = now();

        try {
            $comment = Comment::create($data);
            return response()->json(['data' => $comment], 201);
        } catch (\Exception $th) {
            throw new HttpResponseException(response()->json(['errors' => "Error creating comment!", "message" => "Verify the data of the payload and try again!"], 422));
        }
    }

    public function update(UpdateCommentRequest $request, $id)
    {
        $dataUpdate = Comment::findOrFail($id);
        $data = $request->validated();

        /*
        * Verifies if task_id was used in the payload and returns an error.
        * The parameter is not because a submitted comment cannot change the task.
        */
        if (isset($data['task_id'])) {
            throw new HttpResponseException(response()->json(['errors' => "The task_id cannot be changed!", "message" => "Verify the data of the payload and try again!"], 422));
        } 

        /*
        * Verifies if user_id was used in the payload and returns an error.
        * The parameter is not because a submitted comment cannot change the user that made it.
        */
        if (isset($data['user_id'])) {
            throw new HttpResponseException(response()->json(['errors' => "The user_id cannot be changed!", "message" => "Verify the data of the payload and try again!"], 422));
        } 
        
        /*
        * The date related to the comment can be changed, but this parameter is optional.
        */
        if ($request->has('date')) {
            $data['date'] = Carbon::parse($request->input('date'))->format('Y-m-d H:i:s');
        }

        try {
            $dataUpdate->update($data);
        } catch (\Exception $e) {
            throw new HttpResponseException(response()->json(['errors' => "Error updating comment!", "message" => "Verify the data of the payload and try again!"], 422));
        }
        return response()->json(['data' => $dataUpdate], 200);
    }
}
