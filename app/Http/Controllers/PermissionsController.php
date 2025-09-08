<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rights;
use App\Models\User;

class PermissionsController extends Controller
{
    /**
     * Show all users and their rights.
     */
    public function index()
    {
        $users = User::with('rights')->get();
        return view('permissions.index', compact('users'));
    }

    /**
     * Show form to edit a user's rights.
     */
    public function edit($userId)
    {
        $user = User::findOrFail($userId);

        $modules = ['chart-of-accounts', 'levels', 'workspace', 'users'];
        $rights = ['write', 'read', 'edit', 'erase'];

        // Key rights by module name for easy access in Blade
        $userRights = $user->rights ? $user->rights->keyBy('app_name') : collect();

        return view('permissions.edit', compact('user', 'modules', 'rights', 'userRights'));
    }

    /**
     * Update a user's rights.
     */
    public function update(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

        $modules = ['chart-of-accounts', 'levels', 'workspace', 'users'];
        $rights = ['write', 'read', 'edit', 'erase'];

        // Remove old rights
        Rights::where('fk_userid', $user->id)->delete();

        // Recreate rights
        foreach ($modules as $module) {
            $data = [
                'app_name' => $module,
                'fk_userid' => $user->id,
            ];

            foreach ($rights as $right) {
                $data[$right] = $request->input("perm_{$module}_{$right}") === '1' ? 1 : 0;
            }

            Rights::create($data);
        }

        return redirect()->route('permissions.index')->with('success', 'Rights updated successfully.');
    }

    /**
     * Remove a user's rights entirely.
     */
    public function destroy($userId)
    {
        Rights::where('fk_userid', $userId)->delete();
        return redirect()->route('permissions.index')->with('success', 'Rights removed successfully.');
    }
}
