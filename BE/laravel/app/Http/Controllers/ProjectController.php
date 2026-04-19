<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;

class ProjectController extends Controller
{
    public function index() {
        $projects = Project::all();
        return view('home', compact('projects'));
    }

    public function create() {
        $projects = Project::all();
        return view('projects.create', compact('projects'));
    }

    public function store(Request $request) {
        $request->validate([
            'name'=>'required',
            'description'=>'required',
            'color'=>'required',
            'start_date'=>'required|date',
            'end_date'=>'required|date|after_or_equal:start_date',
        ]);

        Project::create($request->all());
        return redirect()->route('home')->with('success','Project berhasil dibuat');
    }

    public function show(Request $request, $id) {
        $project = Project::findOrFail($id);
        
        $all_tasks = $project->tasks()->with('users')->get();
        // Since we are formatting data for the view
        $all_tasks->transform(function ($task) {
            $task->assignees = $task->users->pluck('name')->toArray();
            return $task;
        });

        $filter = $request->get('filter', 'all');
        $todo_count = $all_tasks->where('status', 'To Do')->count();
        $inprogress_count = $all_tasks->where('status', 'In Progress')->count();
        $done_count = $all_tasks->where('status', 'Done')->count();
        $all_count = $all_tasks->count();

        if ($filter == 'to-do') {
            $tasks = $all_tasks->where('status', 'To Do');
        } elseif ($filter == 'in-progress') {
            $tasks = $all_tasks->where('status', 'In Progress');
        } elseif ($filter == 'done') {
            $tasks = $all_tasks->where('status', 'Done');
        } else {
            $tasks = $all_tasks;
        }

        $users = User::all();

        return view('projects.show', compact('project', 'tasks', 'filter', 'all_count', 'todo_count', 'inprogress_count', 'done_count', 'users'));
    }

    public function destroy($id) {
        $project = Project::findOrFail($id);
        $project->delete();
        return redirect()->route('home')->with('success','Project berhasil dihapus');
    }
}
