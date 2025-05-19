<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Use Eloquent to get all tasks from the database
        $tasks = Task::orderBy('created_at', 'desc')->get(); // Get all tasks, newest first

        // Return the tasks as a JSON response with a 200 OK status
        return response()->json($tasks, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255', // Title is required, must be a string, max 255 chars
            'description' => 'nullable|string',    // Description is optional, must be a string if provided
            'is_completed' => 'nullable|boolean',  // is_completed is optional, must be a boolean if provided
        ]);

        // If validation fails, return the errors
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422); // 422 Unprocessable Entity
        }

        // If validation passes, create and save the new task
        $task = Task::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            // If 'is_completed' is not provided, it will use the default (false) from the migration/model. If it is provided, its value will be used.
            'is_completed' => $request->input('is_completed', false),
        ]);

        // Return the created task as a JSON response with a 201 Created status
        return response()->json($task, 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task) // Laravel auto injects the task model instance
    {
        // If the task is found, return it as a JSON response with a 200 OK status
        return response()->json($task, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {

        // Validate the incoming request data ( 'sometimes' means the rule is only applied if the field is present in the request )
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255', // If title is provided, it must not be empty
            'description' => 'sometimes|nullable|string',   // If description is provided, it can be null or a string
            'is_completed' => 'sometimes|boolean',         // If is_completed is provided, it must be a boolean
        ]);

        // If validation fails, return the errors
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        // Get only the validated data. This ensures no extra fields are passed to the model.
        $validatedData = $validator->validated();

        // Update the task with validated data
        // The fill() method assigns the attributes from the array to the model, respecting the $fillable property in the Task model.
        $task->fill($validatedData);
        $task->save(); // Save the changes to the database

        // Return the updated task with a 200 OK status
        return response()->json($task, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        // Delete the task from the database
        $task->delete();

        // Return a 204 No Content response to indicate successful deletion
        return response()->json(null, 204);
    }
}
