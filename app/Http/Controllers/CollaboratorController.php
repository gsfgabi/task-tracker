<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Collaborator;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class CollaboratorController extends Controller
{
    public function index()
    {
        $collaborators = Collaborator::with(['tasks.project', 'timeTrackers'])->get();
        
        return response()->json([
            'status' => 'success',
            'data' => $collaborators
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:collaborators',
            'password' => 'required|string|min:6',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:collaborators'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $collaborator = Collaborator::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Colaborador criado com sucesso',
            'data' => $collaborator
        ], 201);
    }

    public function show($id)
    {
        $collaborator = Collaborator::with(['tasks.project', 'timeTrackers.task.project'])->findOrFail($id);
        
        return response()->json([
            'status' => 'success',
            'data' => $collaborator
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'sometimes|required|string|max:255|unique:collaborators,username,' . $id,
            'password' => 'sometimes|required|string|min:6',
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:collaborators,email,' . $id
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $collaborator = Collaborator::findOrFail($id);
        $collaborator->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Colaborador atualizado com sucesso',
            'data' => $collaborator
        ]);
    }

    public function destroy($id)
    {
        $collaborator = Collaborator::findOrFail($id);
        $collaborator->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Colaborador excluído com sucesso'
        ]);
    }

    public function report(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'collaborator_id' => 'sometimes|exists:collaborators,id',
            'project_id' => 'sometimes|exists:projects,id',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after:start_date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $query = Collaborator::with(['timeTrackers.task.project']);

        if ($request->has('collaborator_id')) {
            $query->where('id', $request->collaborator_id);
        }

        $collaborators = $query->get();

        $report = [];
        foreach ($collaborators as $collaborator) {
            $timeTrackers = $collaborator->timeTrackers;

            if ($request->has('project_id')) {
                $timeTrackers = $timeTrackers->where('task.project_id', $request->project_id);
            }

            if ($request->has('start_date')) {
                $timeTrackers = $timeTrackers->where('start_time', '>=', $request->start_date);
            }

            if ($request->has('end_date')) {
                $timeTrackers = $timeTrackers->where('start_time', '<=', $request->end_date);
            }

            $totalTime = $timeTrackers->whereNotNull('end_time')->sum(function($tt) { return $tt->duration; });
            $hours = intval($totalTime / 3600);
            $minutes = intval(($totalTime % 3600) / 60);

            $report[] = [
                'collaborator' => $collaborator->name,
                'total_time' => sprintf('%02d:%02d', $hours, $minutes),
                'total_seconds' => $totalTime,
                'time_trackers' => $timeTrackers->values()
            ];
        }

        return response()->json([
            'status' => 'success',
            'data' => $report
        ]);
    }

    public function dailyReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'collaborator_id' => 'sometimes|exists:collaborators,id',
            'project_id' => 'sometimes|exists:projects,id',
            'month' => 'sometimes|integer|between:1,12',
            'year' => 'sometimes|integer|min:2020'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $query = Collaborator::with(['timeTrackers.task.project']);

        if ($request->has('collaborator_id')) {
            $query->where('id', $request->collaborator_id);
        }

        $collaborators = $query->get();

        $dailyReport = [];
        $daysInMonth = Carbon::create($year, $month)->daysInMonth;

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::create($year, $month, $day);
            
            foreach ($collaborators as $collaborator) {
                $timeTrackers = $collaborator->timeTrackers
                    ->where('start_time', '>=', $date->startOfDay())
                    ->where('start_time', '<=', $date->endOfDay())
                    ->whereNotNull('end_time');

                if ($request->has('project_id')) {
                    $timeTrackers = $timeTrackers->where('task.project_id', $request->project_id);
                }

                $totalTime = $timeTrackers->sum(function($tt) { return $tt->duration; });
                $hours = intval($totalTime / 3600);
                $minutes = intval(($totalTime % 3600) / 60);

                $dailyReport[] = [
                    'date' => $date->format('Y-m-d'),
                    'collaborator' => $collaborator->name,
                    'total_time' => sprintf('%02d:%02d', $hours, $minutes),
                    'total_seconds' => $totalTime
                ];
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => $dailyReport
        ]);
    }
}
