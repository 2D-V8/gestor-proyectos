<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProjectsController extends Controller
{
public function index()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            $projects = Project::with('tasks')->get();
        } else {
            // Traemos proyectos donde es dueño o donde está compartido
            $projects = Project::with('tasks')
                ->where('user_id', $user->id)
                ->orWhereHas('users', function($query) use ($user) {
                    $query->where('users.id', $user->id);
                })
                ->get();
        }

        return Inertia::render('Projects/Index', [
            'projects' => $projects,
        ]);
    }


    public function edit($id)
    {
        $project = Project::findOrFail($id);
        return Inertia::render('Projects/Edit', [
            'project' => $project,
        ]);
    }

    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);
        $project->update($request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]));

        return redirect()->route('projects.index')->with('success', 'Proyecto actualizado');
    }

    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Proyecto eliminado');
    }

    public function store(Request $request)
    {
        // Validación de los campos del proyecto
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Asignar el proyecto al usuario autenticado
        $validated['user_id'] = auth()->id();

        // Crear el nuevo proyecto
        $project = Project::create($validated);

        // Asociar el proyecto con el usuario como 'owner' en la tabla pivot
        $project->users()->attach(auth()->id(), ['role' => 'owner']);

        return redirect()->back()->with('success', 'Proyecto creado correctamente');
    }

public function share(Request $request, $projectId)
    {
        $project = Project::findOrFail($projectId);

        // Ya no hay validación del rol del usuario que comparte

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|in:admin,member'
        ]);

        $project->users()->syncWithoutDetaching([
            $validated['user_id'] => ['role' => $validated['role']],
        ]);

        return back()->with('success', 'Proyecto compartido con éxito');
    }

}
