<?php

namespace App\Http\Controllers;

use App\Models\Workspace;
use Illuminate\Http\Request;

class WorkspaceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $workspaces = Workspace::all();
        return view('workspace', compact('workspaces'));
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
        $request->validate([
            'title' => 'required|string|max:50|unique:workspaces,title',
        ]);

        Workspace::create([
            'title' => $request->title,
        ]);

        return redirect()->route('workspace.index')->with('success', 'Workspace created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Workspace $workspace)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Workspace $workspace)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Workspace $workspace)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
        ]);
    
        // Update workspace
        $workspace->title = $validatedData['title'];
        $workspace->save();
    
        return redirect()->route('workspace.index')->with('success', 'Workspace updated successfully!');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Workspace $workspace)
    {
        $workspace->tasks()->delete(); 
        $workspace->delete();
        return back()->with('success', 'Ruang kerja berhasil dihapus.');
    }
}
