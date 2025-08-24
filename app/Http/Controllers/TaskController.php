<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\Collaborator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::with(['project', 'collaborator']);

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->filled('collaborator_id')) {
            $query->where('collaborator_id', $request->collaborator_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tasks = $query->orderBy('created_at', 'desc')->get();
        $projects = Project::all();
        $collaborators = Collaborator::all();

        return view('tasks.index', compact('tasks', 'projects', 'collaborators'));
    }

    public function create()
    {
        $projects = Project::all();
        $collaborators = Collaborator::all();
        return view('tasks.create', compact('projects', 'collaborators'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'required|exists:projects,id',
            'collaborator_id' => 'nullable|exists:collaborators,id',
            'status' => 'required|in:pending,in_progress,completed'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Task::create($request->all());

        return redirect()->route('tasks.index')
            ->with('success', 'Tarefa criada com sucesso!');
    }

    public function show(Task $task)
    {
        $task->load(['project', 'collaborator', 'timeTrackers.collaborator']);
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $projects = Project::all();
        $collaborators = Collaborator::all();
        return view('tasks.edit', compact('task', 'projects', 'collaborators'));
    }

    public function update(Request $request, Task $task)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'required|exists:projects,id',
            'collaborator_id' => 'nullable|exists:collaborators,id',
            'status' => 'required|in:pending,in_progress,completed'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $task->update($request->all());

        return redirect()->route('tasks.index')
            ->with('success', 'Tarefa atualizada com sucesso!');
    }

    public function destroy(Task $task)
    {
        if ($task->timeTrackers()->count() > 0) {
            return back()->with('error', 'Não é possível excluir uma tarefa que possui registros de tempo.');
        }

        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', 'Tarefa excluída com sucesso!');
    }
}
   