<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use App\Models\Level;
use Illuminate\Http\Request;

class ChartOfAccountController extends Controller
{
    public function index(Request $request)
    {
        $selected = $request->get('account');
        $accounts = $selected ? ChartOfAccount::where('acc_id', $selected)->get() : ChartOfAccount::all();

        return view('chart_of_accounts.index', [
            'accounts' => $accounts,
            'all_accounts' => ChartOfAccount::all(),
        ]);
    }

    public function create()
    {
        $levels=Level::all();
        return view('chart_of_accounts.create',compact('levels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'acc_name' => 'required|string|max:100',
            'level_id' => 'required|exists:levels,level_id',
        ]);

        ChartOfAccount::create($request->all());
        return redirect()->route('chart-of-accounts.index');
    }

    public function edit($id)
    {
        $account = ChartOfAccount::findOrFail($id);
        return view('chart_of_accounts.create', compact('account'));
    }

    public function update(Request $request, $id)
    {
        $account = ChartOfAccount::findOrFail($id);
        $account->update($request->all());

        return redirect()->route('chart-of-accounts.index');
    }

    public function destroy($id)
    {
        $account = ChartOfAccount::findOrFail($id);
        $account->delete();

        return redirect()->route('chart-of-accounts.index');
    }
}
