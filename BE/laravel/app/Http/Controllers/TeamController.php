<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TeamController extends Controller
{
    public function index()
    {
        $users = User::withCount(['tasks', 'tasks as active_tasks' => function ($query) {
            $query->whereIn('status', ['To Do', 'In Progress']);
        }, 'tasks as late_tasks' => function ($query) {
            $query->where('due_date', '<', now())->where('status', '!=', 'Done');
        }])->get();

        $teamMembers = $users->map(function ($user) {
            return (object)[
                'id' => $user->id,
                'name' => $user->name,
                'role' => $user->role ?? 'Member',
                'phone' => $user->phone ?? '+6280000000000',
                'total_tasks' => $user->tasks_count,
                'active_tasks' => $user->active_tasks,
                'late_tasks' => $user->late_tasks,
            ];
        });

        return view('team.index', compact('teamMembers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'role' => 'required',
        ]);

        User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'role' => $request->role,
            'email' => Str::slug($request->name) . rand(1000, 9999) . '@example.com',
            'password' => Hash::make('password123'),
        ]);

        return redirect()->route('team.index')->with('success', 'Anggota tim berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'role' => 'required',
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'role' => $request->role,
        ]);

        return redirect()->route('team.index')->with('success', 'Anggota tim berhasil diperbarui');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('team.index')->with('success', 'Anggota tim berhasil dihapus');
    }
}
