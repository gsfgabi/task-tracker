<?php

namespace App\Http\Controllers;

use App\Models\TimeTracker;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TimeTrackerController extends Controller
{
    public function index(Request $request)
    {
        $query = TimeTracker::with(['task.project', 'collaborator']);

        if ($request->filled('task_id')) {
            $query->where('task_id', $request->task_id);
        }

        if ($request->filled('collaborator_id')) {
            $query->where('collaborator_id', $request->collaborator_id);
        }

        if ($request->filled('date')) {
            $query->whereDate('start_time', $request->date);
        }

        $timeTrackers = $query->orderBy('start_time', 'desc')->get();

        return view('time-trackers.index', compact('timeTrackers'));
    }

    public function create()
    {
        $tasks = Task::all();
        $collaborators = \App\Models\Collaborator::all();
        return view('time-trackers.create', compact('tasks', 'collaborators'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'task_id' => 'required|exists:tasks,id',
            'collaborator_id' => 'required|exists:collaborators,id',
            'start_time' => 'required|date',
            'end_time' => 'nullable|date|after:start_time'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Verificar conflitos de tempo
        if ($this->hasTimeConflict($request->collaborator_id, $request->start_time, $request->end_time)) {
            return back()->withErrors(['time' => 'Existe conflito de horário com outro registro.'])->withInput();
        }

        // Verificar limite de 24h por dia
        if ($this->exceedsDailyLimit($request->collaborator_id, $request->start_time, $request->end_time)) {
            return back()->withErrors(['time' => 'O total de horas para este dia excede 24 horas.'])->withInput();
        }

        TimeTracker::create($request->all());

        return redirect()->route('time-trackers.index')
            ->with('success', 'Time tracker criado com sucesso!');
    }

    public function show(TimeTracker $timeTracker)
    {
        $timeTracker->load(['task.project', 'collaborator']);
        return view('time-trackers.show', compact('timeTracker'));
    }

    public function edit(TimeTracker $timeTracker)
    {
        $tasks = Task::all();
        $collaborators = \App\Models\Collaborator::all();
        return view('time-trackers.edit', compact('timeTracker', 'tasks', 'collaborators'));
    }

    public function update(Request $request, TimeTracker $timeTracker)
    {
        $validator = Validator::make($request->all(), [
            'task_id' => 'required|exists:tasks,id',
            'collaborator_id' => 'required|exists:collaborators,id',
            'start_time' => 'required|date',
            'end_time' => 'nullable|date|after:start_time'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Verificar conflitos de tempo (excluindo o próprio registro)
        if ($this->hasTimeConflict($request->collaborator_id, $request->start_time, $request->end_time, $timeTracker->id)) {
            return back()->withErrors(['time' => 'Existe conflito de horário com outro registro.'])->withInput();
        }

        $timeTracker->update($request->all());

        return redirect()->route('time-trackers.index')
            ->with('success', 'Time tracker atualizado com sucesso!');
    }

    public function destroy(TimeTracker $timeTracker)
    {
        $timeTracker->delete();

        return redirect()->route('time-trackers.index')
            ->with('success', 'Time tracker excluído com sucesso!');
    }

    public function start(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'task_id' => 'required|exists:tasks,id',
            'collaborator_id' => 'required|exists:collaborators,id'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        // Verificar se já existe um time tracker ativo para este colaborador
        $activeTracker = TimeTracker::where('collaborator_id', $request->collaborator_id)
            ->whereNull('end_time')
            ->first();

        if ($activeTracker) {
            return back()->withErrors(['time' => 'Você já possui um time tracker ativo. Pare-o antes de iniciar outro.']);
        }

        TimeTracker::create([
            'task_id' => $request->task_id,
            'collaborator_id' => $request->collaborator_id,
            'start_time' => Carbon::now()
        ]);

        return back()->with('success', 'Time tracker iniciado com sucesso!');
    }

    public function stop(TimeTracker $timeTracker)
    {
        if ($timeTracker->end_time) {
            return back()->withErrors(['time' => 'Este time tracker já foi finalizado.']);
        }

        $timeTracker->update([
            'end_time' => Carbon::now()
        ]);

        return back()->with('success', 'Time tracker parado com sucesso!');
    }

    private function hasTimeConflict($collaboratorId, $startTime, $endTime, $excludeId = null)
    {
        $query = TimeTracker::where('collaborator_id', $collaboratorId)
            ->where(function ($q) use ($startTime, $endTime) {
                $q->whereBetween('start_time', [$startTime, $endTime])
                  ->orWhereBetween('end_time', [$startTime, $endTime])
                  ->orWhere(function ($q2) use ($startTime, $endTime) {
                      $q2->where('start_time', '<=', $startTime)
                         ->where('end_time', '>=', $endTime);
                  });
            });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    private function exceedsDailyLimit($collaboratorId, $startTime, $endTime)
    {
        $startDate = Carbon::parse($startTime)->startOfDay();
        $endDate = Carbon::parse($endTime)->endOfDay();

        $totalSeconds = TimeTracker::where('collaborator_id', $collaboratorId)
            ->whereDate('start_time', $startDate)
            ->whereNotNull('end_time')
            ->get()
            ->sum(function($tt) { return $tt->duration; });

        $newDuration = Carbon::parse($endTime)->diffInSeconds(Carbon::parse($startTime));
        $totalSeconds += $newDuration;

        return $totalSeconds > 86400; // 24 horas em segundos
    }
}
