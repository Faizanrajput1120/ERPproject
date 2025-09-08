<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkspaceController extends Controller
{
    // Show Workspace dashboard
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'super_admin') {
            // Show all workspaces + their admins
            $workspaces = Workspace::with(['admins'])->get();
            return view('workspace.super_admin_dashboard', compact('workspaces'));
        }

        if ($user->role === 'admin') {
            // Show only own workspace + users
            $workspace = $user->workspace; // relationship on User model
            $users = $workspace ? $workspace->users : collect();
            return view('workspace.admin_dashboard', compact('workspace', 'users'));
        }

        abort(403, 'Unauthorized');
    }
        // Show the form to create a new workspace
    public function create()
    {
    $workspaces = Workspace::all();
    return view('workspace.create', compact('workspaces'));
}

          // Store a new workspace
    public function store(Request $request)
    {
        $request->validate([
            'c_name' => 'required|string|max:255',
        ]);

        Workspace::create([
            'c_name' => $request->c_name,
        ]);

        return redirect()->route('workspace.index')->with('success', 'Workspace created successfully.');
    }

    // Super Admin: create Admin for a workspace
    public function createAdmin(Request $request)
    {
        $request->validate([
            'workspace_id'   => 'required|exists:workspace,cid',
            'name'     => 'required|string',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);
        
        $user=User::create([
            'name'    => $request->name,
            'email'   => $request->email,
            'password'=> bcrypt($request->password),
            'role'    => 'admin',
            'fk_cid'  => $request->workspace_id,
        ]);

        // dd($user);
        return back()->with('success', 'Admin created successfully');
    }

    // Admin: create User for their workspace
    public function createUser(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'name'     => 'required|string',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name'    => $request->name,
            'email'   => $request->email,
            'password'=> bcrypt($request->password),
            'role'    => 'user',
            'fk_cid'  => $user->fk_cid,
        ]);

        return back()->with('success', 'User created successfully');
    }
}
