<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class TaskController extends Controller
{


    public function findAll($user_id)
    {
        try {
            $user = User::findOrFail($user_id);

            $tasks = Task::where('user_id', $user->id)->get();

            return response()->json($tasks);
        } catch (ModelNotFoundException $e) {
            Log::error('Modelo não encontrado: ' . $e->getMessage());

            return response()->json(['error' => 'Recurso não encontrado'], 404);
        }
    }


    public function create(Request $request, $user_id)
    {

        try {
            $user = User::findOrFail($user_id);

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'reminder' => 'required|boolean'
            ]);

            $name = $validated['name'];
            $reminder = $validated['reminder'];

            $newTask = [
                'name' => $name,
                'user_id' => $user->id,
                'reminder' => $reminder
            ];

            $task = Task::create($newTask);

            return response()->json($task, 201);
        } catch (QueryException $e) {
            Log::error('Erro de consulta ao banco de dados: ' . $e->getMessage());

            return response()->json(['error' => 'Erro ao criar o recurso'], 500);
        }
    }

    public function update(Request $request, Task $task)
    {
        try {
            $validatedData = $request->validated([
                'name' => 'required|string',
                'user_id' => 'required|number'
            ]);

            $task->update($validatedData);

            return response()->json($task, 200);
        } catch (ModelNotFoundException $e) {
            Log::error('Modelo não encontrado: ' . $e->getMessage());

            return response()->json(['error' => 'Recurso não encontrado'], 404);
        }
    }

    public function destroy(Request $request)
    {
        $validatedData = $request->validated([
            'id' => 'required|number',
        ]);
        $id = $validatedData['id'];

        Task::where('id', $id)->delete();

        return response()->json(null, 204);
    }
}
