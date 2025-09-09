<?php

namespace App\Http\Controllers;


use App\Models\TRNDTL;
use App\Models\ErpParam;
use Illuminate\Http\Request;
use App\Models\ChartOfAccount as AccountMaster;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CashController extends Controller
{
    public function index(Request $request)
    {
        $user=Auth::user();
        $startDate = $request->input('start_date'); // Default: first day of current month
        $endDate = $request->input('end_date');                      // Default: today
        $status = $request->input('status');                                         // Default: 'all'
        $v_no = $request->input('v_no');                                              // Default: null
        $account_id = $request->input('account_id');                                  // Default: null


        $query = TRNDTL::where('v_type', 'CRV')->where('fk_c_id',$user->fk_c_id )->with('accounts');
        $query = TRNDTL::where('v_type', 'CRV')->where('fk_c_id',$user->fk_c_id )->with('cashes');
        // dd($query);
        if ($startDate && $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        }

        if ($status) {
            $query->where('status', $status);
        }
        if ($v_no) {
            $query->where('v_no', $v_no);
        }
        if ($account_id) {
            $query->where('account_id', $account_id);
        }

        $trndtls = $query->orderBy('date', 'desc')
            ->orderBy('v_no', 'desc')
            ->take(10)
            ->get();

        $accountMasters = AccountMaster::all();
        $vNoList = TRNDTL::where('v_type', 'CRV')->where('fk_c_id',$user->fk_c_id )->pluck('v_no')->unique()->toArray();
        $accountIdList = TRNDTL::where('v_type', 'CRV')->where('fk_c_id',$user->fk_c_id )->pluck('account_id')->unique()->toArray();
    
        return view('cash.index', [
            'trndtls' => $trndtls,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'status' => $status,
            'accountMasters' => $accountMasters,
            'vNoList' => $vNoList,
            'accountIdList' => $accountIdList,
        ]);
    }

    public function create()
    {
        $user = Auth::user();
        $accounts = AccountMaster::where('fk_c_id', $user->fk_c_id)->get();

        // Fetch ERP Params for the company
        $erpParams = ErpParam::with('level2')->where('fk_c_id', $user->fk_c_id)->get();

        // Default to empty collection
        $accountMasters = collect();

        // If ERP Params exist and cash_level is set, fetch AccountMasters for that level
        if ($erpParams->isNotEmpty() && $erpParams->first()->cash_level) {
            $cashLevelId = $erpParams->first()->cash_level;
            $accountMasters = AccountMaster::where('fk_level2_id', $cashLevelId)
                ->where('fk_c_id', $user->fk_c_id)
                ->get();
        }

        // Pass all required variables to the view
        // For create, voucher is null (no existing data)
        $voucher = null;
        return view('cash.create', [
            'accounts' => $accounts,
            'erpParams' => $erpParams,
            'accountMasters' => $accountMasters,
            'voucher' => $voucher,
        ]);
    }

    public function generateVoucherType($baseType)
    {
        $user=Auth::user();
        // Fetch the latest entry for the given voucher type from the database
        $latestVoucher = TRNDTL::where('v_type', 'like', "{$baseType}%")
            ->orderBy('id', 'desc') 
            ->where('fk_c_id',$user->fk_c_id )->first();

        // If no entries exist, start with 1
        if (!$latestVoucher) {
            return "{$baseType}1";
        }

        // Extract the numeric part from the latest voucher type
        preg_match('/(\d+)$/', $latestVoucher->v_type, $matches);
        $nextNumber = isset($matches[1]) ? (int) $matches[1] + 1 : 1; // Increment the number

        return "{$baseType}{$nextNumber}";
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        // Validate the request for the new form structure
        $request->validate([
            'v_type' => 'required|string',
            'date' => 'required|array',
            'date.*' => 'required|date',
            'cash' => 'required|array',
            'cash.*' => 'required|string',
            'account' => 'required|array',
            'account.*' => 'required|string',
            'description' => 'required|array',
            'description.*' => 'required|string',
            'credit' => 'required|array',
            'credit.*' => 'required|numeric',
        ]);

        // Handle file upload if present
        $filePath = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $filePath = $file->storeAs('uploads', $fileName, 'public');
        }

        $lastEntry = TRNDTL::where('v_type', $request->v_type)
            ->orderBy('id', 'desc')->where('fk_c_id', $user->fk_c_id)
            ->first();

        // If there are no previous records for this v_type, start from 1
        if ($lastEntry && is_numeric($lastEntry->v_no)) {
            $lastInvoiceNumber = (int) $lastEntry->v_no;
        } else {
            $lastInvoiceNumber = 0;
        }

        $newInvoiceNumber = $lastInvoiceNumber + 1;

        // Loop through each entry and assign the same v_no to all entries in this request
        $count = count($request->date);
        for ($i = 0; $i < $count; $i++) {
            TRNDTL::create([
                'v_no' => $newInvoiceNumber,
                'v_type' => 'CRV',
                'date' => $request->date[$i],
                'cash_id' => $request->cash[$i],
                'account_id' => $request->account[$i],
                'description' => $request->description[$i],
                'credit' => $request->credit[$i],
                'preparedby' => $user->id,
                'debit' => '0',
                'status' => 'unofficial',
                'file_id' => $filePath,
                'fk_c_id' => $user->fk_c_id
            ]);
        }

        // Redirect to the list view with success message
        return redirect()->route('cash.index')->with('success', $request->v_type . '-' . $newInvoiceNumber . ' has been saved successfully.');
    }




    public function reports(Request $request)
    {

    }




    // Show the edit form
    public function edit($v_no)
    {
        $user = Auth::user();
        $voucher = TRNDTL::where('v_no', $v_no)
            ->where('v_type', 'CRV')->where('fk_c_id', $user->fk_c_id)
            ->get();

        // Use the first voucher entry as the main cashReceipt for editing
        $cashReceipt = $voucher->first();

        $accounts = AccountMaster::where('fk_c_id', $user->fk_c_id)->get();

        $erpParams = ErpParam::with('level2')->where('fk_c_id', $user->fk_c_id)->get();

        $accountMasters = collect();
        if ($erpParams->isNotEmpty()) {
            $cashLevelId = $erpParams->first()->cash_level;
            $accountMasters = AccountMaster::where('fk_level2_id', $cashLevelId)->where('fk_c_id', $user->fk_c_id)->get();
        }

        // Pass the voucher and cashReceipt to the view
        return view('cash.edit', get_defined_vars());
    }



    // Handle the update
    public function update(Request $request, $id)
{
    $user = Auth::user();

    // Validate required fields
    $validated = $request->validate([
        'v_type' => 'required|string',
        'date' => 'required|date',
        'account' => 'required|numeric',
        'account_title' => 'required|numeric',
        'amount' => 'required|numeric|min:0',
        'description' => 'nullable|string',
        'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048'
    ]);

    // Fetch and delete previous entries (assuming single-entry voucher)
    TRNDTL::where('v_no', $id)
        ->where('v_type', 'CRV')
        ->where('fk_c_id', $user->fk_c_id)
        ->delete();

    $filePath = null;

    // Handle new file upload
    if ($request->hasFile('file')) {
        $file = $request->file('file');
        $fileName = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
        $filePath = $file->storeAs('uploads', $fileName, 'public');
    }

    // Create updated entry
    TRNDTL::create([
        'v_no' => $id,
        'v_type' => $request->v_type,
        'date' => $request->date,
        'cash_id' => $request->account,
        'account_id' => $request->account_title,
        'description' => $request->description,
        'preparedby' => $user->id,
        'credit' => $request->amount,
        'debit' => 0,
        'status' => 'unofficial',
        'file_id' => $filePath,
        'fk_c_id' => $user->fk_c_id
    ]);

    return redirect()->route('cash.index')->with('success', 'Cash receipt updated successfully.');
}








    public function destroy($id)
    {
        // Find the transaction by ID where v_type is CRV and r_id matches
        $trndtl = TRNDTL::where('v_type', 'CRV')
            ->where('id', $id)
            ->firstOrFail();

        // Delete the transaction
        $trndtl->delete();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Transaction deleted successfully!');
    }

    public function delete($id)
    {
        // Find the transaction by ID where v_type is CRV and r_id matches
        $trndtl = TRNDTL::where('v_type', 'CRV')
            ->where('id', $id)
            ->firstOrFail();

        // Delete the transaction
        $trndtl->delete();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Transaction deleted successfully!');
    }
    public function updateStatus(Request $request, $id)
    {
        $transaction = TRNDTL::findOrFail($id);
        $transaction->status = $request->status;
        $transaction->save();

        return response()->json(['success' => true]);
    }


}
