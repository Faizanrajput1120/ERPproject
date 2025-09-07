<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use App\Models\Level;
use App\Models\Group;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LevelController extends Controller
{
    public function index(Request $request)
    {
        $selectedLevel = $request->get('level');

        $query = Level::with(['group', 'pre']); // eager load

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
        $groups = Group::all();
        $pre_levels = Level::all();
        return view('levels.create', compact('groups', 'pre_levels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'level_title' => 'required|string|max:100',
        ]);

        Level::create($request->only('level_title', 'group_id', 'pre_id'));
        return redirect()->route('levels.index')->with('success', 'Level created successfully.');
    }

    public function edit(Level $level)
    {
        $groups = Group::all();
        $pre_levels = Level::where('level_id', '!=', $level->level_id)->get();
        return view('levels.create', compact('level', 'groups', 'pre_levels'));
    }

    public function update(Request $request, Level $level)
    {
        $request->validate([
            'level_title' => 'required|string|max:100',
        ]);

        $level->update($request->only('level_title', 'group_id', 'pre_id'));
        return redirect()->route('levels.index')->with('success', 'Level updated successfully.');
    }

    public function destroy(Level $level)
    {
       $accExist = ChartOfAccount::where('level_id', $level->level_id)->exists();
     if($accExist){
        dd("WORKING");
        return redirect()->back()->with('error', 'This Level is used in Chart of Account');
    }
        
        $level->delete();
        return redirect()->route('levels.index')->with('success', 'Level deleted successfully.');
    }
    public function printTree()
{
    // Fetch all groups with nested levels and accounts
    $groups = Group::with(['levels.children.children.accounts'])->get();

    $pdf = Pdf::loadView("levels.levels_pdf", compact('groups'));
    return $pdf->download('accounts_hierarchy.pdf');
}

    // public function printTree()
    // {
    //     $levels = Level::with('group')->get();

    //     // Build tree
    //     $lookup = [];
    //     foreach ($levels as $lvl) {
    //         $lookup[$lvl->level_id] = [
    //             'level_id' => $lvl->level_id,
    //             'level_title' => $lvl->level_title,
    //             'group_name' => $lvl->group->name ?? '-',
    //             'pre_id' => $lvl->pre_id,
    //             'children' => []
    //         ];
    //     }

    //     $roots = [];
    //     foreach ($lookup as &$lvl) {
    //         if ($lvl['pre_id'] && isset($lookup[$lvl['pre_id']])) {
    //             $lookup[$lvl['pre_id']]['children'][] = &$lvl;
    //         } else {
    //             $roots[] = &$lvl;
    //         }
    //     }

    //     $pdf = Pdf::loadView('levels.levels_pdf', compact('roots'));
    //     return $pdf->download('levels_tree.pdf');
    // }
}
