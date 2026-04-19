<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Task;

class TaskController extends Controller
{
    public function store(Request $request, $projectId)
    {
        $request->validate([
            'title' => 'required',
            'status' => 'required',
            'priority' => 'required',
            'due_date' => 'nullable|date',
            'assignees' => 'array',
            'attachment' => 'nullable|file',
        ]);

        $project = Project::findOrFail($projectId);

        $task = new Task();
        $task->project_id = $project->id;
        $task->title = $request->title;
        $task->description = $request->description;
        $task->status = $request->status;
        $task->priority = $request->priority;
        $task->due_date = $request->due_date;
        $task->start_date = now();

        if ($request->hasFile('attachment')) {
            $task->attachment = $request->file('attachment')->store('attachments', 'public');
        }

        $task->save();

        if ($request->has('assignees')) {
            $task->users()->attach($request->assignees);
        }

        return redirect()->route('projects.show', $project->id)->with('success', 'Task added successfully');
    }

    public function edit($id)
    {
        $task = Task::findOrFail($id);
        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, $id)
    {
    $request->validate([
        'title' => 'required',
        'description' => 'nullable',
        'start_date' => 'nullable|date',
        'due_date' => 'nullable|date',
        'priority' => 'required',
        'status' => 'required|in:To Do,In Progress,Done',
        'attachment' => 'nullable|file',
    ]);

    $task = Task::findOrFail($id);

    $task->update([
        'title' => $request->title,
        'description' => $request->description,
        'start_date' => $request->start_date,
        'due_date' => $request->due_date,
        'priority' => $request->priority,
        'status' => $request->status,
    ]);

    if ($request->hasFile('attachment')) {
        $task->attachment = $request->file('attachment')->store('attachments', 'public');
        $task->save();
    }

    return redirect()->back()->with('success', 'Task berhasil diupdate');
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return redirect()->back()->with('success', 'Task berhasil dihapus');
    }
}
