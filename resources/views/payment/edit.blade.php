@extends('full-width-light.index')

@section('content')
<div class="container-fluid">
    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">ERPLive</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                        <li class="breadcrumb-item active">Edit Cash Payment</li>
                    </ol>
                </div>
                <h4 class="page-title">Edit Cash Payment</h4>
            </div>
        </div>
    </div>

    <!-- Success Alert -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible text-bg-success border-0 fade show" role="alert">
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
            {{ session('success') }}
        </div>
    @endif

    <!-- Error Alert -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Validation failed:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Edit Form -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('payments.update', $voucher->first()->v_no) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="v_type" value="CPV">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="entryDate" class="form-label">Date</label>
                                <input type="date" id="entryDate" name="entries[0][date]" class="form-control" value="{{ old('entries.0.date', $voucher->first()->date) }}">
                            </div>
                            <div class="col-md-4">
                                <label for="entryCash" class="form-label">Cash</label>
                                <select name="entries[0][cash]" id="entryCash" class="form-control select2" data-toggle="select2">
                                    <option value="">Select</option>
                                    @foreach ($accountMasters as $account)
                                        <option value="{{ $account->id }}" {{ $voucher->first()->cash_id == $account->id ? 'selected' : '' }}>{{ $account->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="entryParty" class="form-label">Account Title</label>
                                <select name="entries[0][account]" id="entryParty" class="form-control select2" data-toggle="select2">
                                    <option value="">Select</option>
                                    @foreach ($accounts as $account)
                                        <option value="{{ $account->id }}" {{ $voucher->first()->account_id == $account->id ? 'selected' : '' }}>{{ $account->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="entryDescription" class="form-label">Description</label>
                                <textarea id="entryDescription" name="entries[0][description]" class="form-control">{{ old('entries.0.description', $voucher->first()->description) }}</textarea>
                            </div>
                            <div class="col-md-3">
                                <label for="entryAmount" class="form-label">Amount</label>
                                <input type="number" id="entryAmount" name="entries[0][debit]" class="form-control" value="{{ old('entries.0.debit', $voucher->first()->debit) }}">
                            </div>
                            <div class="col-md-3">
                                <label for="uploadFile" class="form-label">Upload File</label>
                                <input type="file" id="uploadFile" name="file" class="form-control">
                                @if ($voucher->first()->file_id)
                                    <div class="mt-2">
                                        <a href="{{ asset('storage/' . $voucher->first()->file_id) }}" target="_blank">View current file</a>
                                    </div>
                                @endif
                            </div>
                            <div class="col-6 d-flex gap-2">
                                <button type="submit" class="btn btn-success flex-fill">Update Payment</button>
                                <a href="{{ route('payments.reports') }}" class="btn btn-secondary flex-fill">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
