<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use App\Models\Level;
use App\Models\Group;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class LevelController extends Controller
{
    /**
     * Check rights for a specific action.
     */
    private function checkRights(string $action)
    {
        $user = Auth::user();

        // Super admins/admins bypass rights check
        if ($user->role !== 'user') {
            return;
        }

        $right = $user->rights->where('app_name', 'levels')->first();

        if (!$right || !$right->$action) {
            abort(403, "You do not have permission to {$action} levels.");
        }
    }

    public function index(Request $request)
    {
        $this->checkRights('read');

        $selectedLevel = $request->get('level');

        $query = Level::with(['group', 'pre']);

        if ($selectedLevel) {
            $query->where('level_id', $selectedLevel);
        }

        $levels = $query->get();

        return view('levels.index', [
            'levels' => $levels,
            'all_levels' => Level::all(),
        ]);
    }

    public function create()
    {
        $this->checkRights('write');

        $groups = Group::all();
        $pre_levels = Level::all();

        return view('levels.create', compact('groups', 'pre_levels'));
    }

    public function store(Request $request)
    {
        $this->checkRights('write');

        $request->validate([
            'level_title' => 'required|string|max:100',
        ]);

        Level::create($request->only('level_title', 'group_id', 'pre_id'));

        return redirect()->route('levels.index')->with('success', 'Level created successfully.');
    }

    public function edit(Level $level)
    {
        $this->checkRights('edit');

        $groups = Group::all();
        $pre_levels = Level::where('level_id', '!=', $level->level_id)->get();

        return view('levels.create', compact('level', 'groups', 'pre_levels'));
    }

    public function update(Request $request, Level $level)
    {
        $this->checkRights('edit');

        $request->validate([
            'level_title' => 'required|string|max:100',
        ]);

        $level->update($request->only('level_title', 'group_id', 'pre_id'));

        return redirect()->route('levels.index')->with('success', 'Level updated successfully.');
    }

    public function destroy(Level $level)
    {
        $this->checkRights('erase');

        $accExist = ChartOfAccount::where('level_id', $level->level_id)->exists();
        if ($accExist) {
            return redirect()->back()->with('error', 'This Level is used in Chart of Account.');
        }

        $level->delete();

        return redirect()->route('levels.index')->with('success', 'Level deleted successfully.');
    }

    public function printTree()
    {
        $groups = Group::with(['levels.children.children.accounts'])->get();

        $pdf = Pdf::loadView("levels.levels_pdf", compact('groups'));
        return $pdf->download('accounts_hierarchy.pdf');
    }
}
