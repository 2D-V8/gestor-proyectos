<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TasksController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'project_id' => 'required|exists:projects,id',
        ]);

        Task::create($validated);

        return redirect()->back()->with('success', 'Tarea creada correctamente');
    }

public function update(Request $request, $id)
{
    $task = Task::findOrFail($id);

    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'status' => 'nullable|string|in:todo,in_progress,done', // Ojo con los valores permitidos
        'due_date' => 'nullable|date',
        'user_id' => 'nullable|exists:users,id',
    ]);

    $task->update($validated);


}


    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
    }
}
