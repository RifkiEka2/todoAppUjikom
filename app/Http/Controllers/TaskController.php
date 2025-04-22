<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Workspace;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function task(Workspace $workspace)
    {
        $tasks = Task::where('workspace_id', $workspace->id)->orderBy('deadline','asc')->get();

        return view('task', compact('workspace', 'tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'deadline' => 'required|date',
            'priority' => 'required|in:rendah,sedang,tinggi',
            'workspace_id' => 'required|exists:workspaces,id',

        ]);
    
        Task::create([
            'title' => $validated['title'],
            'deadline' => $validated['deadline'],
            'priority' => $validated['priority'],
            'workspace_id' => $validated['workspace_id'], 
            'status' => 'on_progress', 
        ]);
    
        return redirect()->route('task.index', $validated['workspace_id'])->with('success', 'Task created successfully.');}
    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'deadline' => 'required|date',
            'priority' => 'required|in:rendah,sedang,tinggi',
        ]);
    
        $task->update($validated);
    
        return redirect()->route('task.index', $task->workspace_id)->with('success', 'Task updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return back()->with('success', 'Task deleted successfully.');
    }

    // TaskController.php
    public function updateStatus(Request $request, Task $task)
    {
        $status = $request->input('status') === 'completed' ? 'completed' : 'on_progress';
        $task->update(['status' => $status]);
        return back();
    }

}
