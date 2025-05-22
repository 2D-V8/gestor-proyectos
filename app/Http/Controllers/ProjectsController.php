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
        $projects = Project::with('tasks')->where('user_id', $user->id)->get();
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

        // Asegurarse de que el proyecto se asigne al usuario autenticado
        $validated['user_id'] = auth()->id(); // Aquí asignamos el user_id

        // Crear el nuevo proyecto
        Project::create($validated);

        return redirect()->back()->with('success', 'Proyecto creado correctamente');
    }
}
