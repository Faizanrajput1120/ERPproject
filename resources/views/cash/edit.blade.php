@extends('full-width-light.index')

@section('content')
<div class="container-fluid">
    <!-- Page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Edit Cash Receipt</h4>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>There were some problems with your input:</strong>
            <ul class="mt-2 mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Edit form -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('cash.update', $cashReceipt->v_no) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="v_type" value="CRV">
                        <input type="hidden" name="invoice_number" value="{{ $cashReceipt->v_no }}">
                        <input type="hidden" name="total_amount" value="{{ $cashReceipt->credit }}">

                        <div class="row g-4 mb-3">
                            <div class="col-md-3">
                                <label for="entryDate" class="form-label">Date</label>
                                <input type="date" id="entryDate" class="form-control" name="date" value="{{ old('date', $cashReceipt->date) }}">
                            </div>
                            <div class="col-md-3">
                                <label for="entryCash" class="form-label">Cash</label>
                                <select name="account" class="form-select select2" id="entryCash">
                                    <option value="">Select</option>
                                    @foreach ($accountMasters as $account)
                                        <option value="{{ $account->id }}" {{ $cashReceipt->cash_id == $account->id ? 'selected' : '' }}>{{ $account->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="entryParty" class="form-label">Account Title</label>
                                <select name="account_title" class="form-select select2" id="entryParty">
                                    <option value="">Select</option>
                                    @foreach ($accounts as $account)
                                        <option value="{{ $account->id }}" {{ $cashReceipt->account_id == $account->id ? 'selected' : '' }}>{{ $account->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="entryDescription" class="form-label">Description</label>
                                <input class="form-control" name="description" id="entryDescription" value="{{ old('description', $cashReceipt->description) }}">
                            </div>
                        </div>
                        <div class="row g-4 mb-3">
                            <div class="col-md-3">
                                <label for="entryAmount" class="form-label">Amount</label>
                                <input type="number" class="form-control" name="amount" id="entryAmount" value="{{ old('amount', $cashReceipt->credit) }}">
                            </div>
                            <div class="col-md-5">
                                <label for="uploadFile" class="form-label">Upload File</label>
                                <input type="file" class="form-control" name="file" id="uploadFile">
                                @if ($cashReceipt->file_id)
                                    <div class="mt-2">
                                        <a href="{{ asset('storage/' . $cashReceipt->file_id) }}" target="_blank">View current file</a>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-4 d-flex align-items-end justify-content-end gap-2">
    <button type="submit" class="btn btn-success btn-sm">Update Receipt</button>
    <a href="{{ route('cash.index') }}" class="btn btn-secondary btn-sm">Cancel</a>
</div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
