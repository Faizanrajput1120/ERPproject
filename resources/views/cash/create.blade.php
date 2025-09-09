@extends('full-width-light.index')
@section('content')
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">ERPLive</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                            <li class="breadcrumb-item active">Edit Cash Receipt</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Create Cash Receipt</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible text-bg-success border-0 fade show" role="alert">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"
                    aria-label="Close"></button>
                {{ session('success') }}
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane show active" id="input-types-preview">
                                <div class="row">

                                    <!-- If $voucher exists and has entries, use update route, else use store route -->
                                    <form id="voucherForm"
                                        action="{{ isset($voucher) && $voucher && count($voucher) > 0 ? route('cash.update', $voucher->first()->v_no) : route('cash.store') }}"
                                        method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @if (isset($voucher) && $voucher && count($voucher) > 0)
                                            @method('PUT')
                                        @endif


                                        <div class="row g-4 mb-3">
                                            <input type="hidden" id="invoice_type" class="form-control" name="v_type" value="CRV" required readonly>
                                            <input type="hidden" id="invoice" class="form-control" name="invoice_number" required>
                                            <input type="hidden" id="totalAmount" name="total_amount">

                                            <div class="col-md-3">
                                                <label for="entryDate" class="form-label">Date</label>
                                                <input type="date" id="entryDate" class="form-control" name="date">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="entryCash" class="form-label">Cash</label>
                                                <select name="cash_id" class="form-select select2" data-toggle="select2" id="entryCash">
                                                    <option value="">Select</option>
                                                    @foreach ($accountMasters as $account)
                                                        <option value="{{ $account->id }}">{{ $account->title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="entryParty" class="form-label">Account Title</label>
                                                <select name="account_id" id="entryParty" class="form-select select2" data-toggle="select2">
                                                    <option value="">Select</option>
                                                    @foreach ($accounts as $account)
                                                        <option value="{{ $account->id }}">{{ $account->title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="entryDescription" class="form-label">Description</label>
                                                <input id="entryDescription" class="form-control" name="description">
                                            </div>
                                        </div>
                                        <div class="row g-4 mb-3">
                                            <div class="col-md-3">
                                                <label for="entryAmount" class="form-label">Amount</label>
                                                <input type="number" id="entryAmount" class="form-control" name="amount">
                                            </div>
                                            <div class="col-md-5">
                                                <label for="uploadFile" class="form-label">Upload File</label>
                                                <input type="file" id="uploadFile" class="form-control" name="file">
                                                <div id="filePreviewContainer" class="mt-2" style="display:none;">
                                                    <img id="imagePreview" src="" alt="Image Preview" style="max-width: 150px; max-height: 150px; display:none;">
                                                    <span id="fileNamePreview" style="font-size:14px;"></span>
                                                    <button type="button" id="removeFile" class="btn btn-sm btn-danger">X</button>
                                                </div>
                                            </div>
                                            <div class="col-md-4 d-flex align-items-end justify-content-end gap-2">
                                                <button type="button" id="addEntry" class="btn btn-primary px-4">Add Entry</button>
                                                <button type="submit" id="submitVoucher" class="btn btn-success px-4">Submit</button>
                                            </div>

                                            <!-- Invoice Display -->
                                            <h3 class="mt-4">Invoice <span id="invoiceDisplay"></span></h3>

                                            <!-- Entries Table -->
                                            <div class="col-lg-12">
                                                <table class="table mt-4" id="entriesTable">
                                                    <thead>
                                                        <tr>
                                                            <th>Sr No</th>
                                                            <th>Date</th>
                                                            <th>Cash</th>
                                                            <th>Account Title</th>
                                                            <th>Description</th>
                                                            <th>Amount</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="entriesBody">
                                                        @if ($voucher && count($voucher) > 0)
                                                            @foreach ($voucher as $entry)
                                                                <!-- Loop through all entries in the $voucher collection -->
                                                                <tr>

                                                                    <td>{{ $loop->iteration }}
                                                                    </td>
                                                                    <td>
                                                                        {{ $entry->date }}
                                                                        <input type="hidden" name="date[]"
                                                                            value="{{ $entry->date }}">
                                                                    </td>
                                                                    <td>
                                                                        {{ $entry->cashes->title ?? 'N/A' }}
                                                                        <input type="hidden" name="cash[]"
                                                                            value="{{ $entry->cashes->id }}">
                                                                    </td> <!-- Adjust based on your relationships -->
                                                                    <td>{{ $entry->accounts->title ?? 'N/A' }}
                                                                        <input type="hidden" name="account[]"
                                                                            value="{{ $entry->accounts->id }}">
                                                                    </td> <!-- Adjust based on your relationships -->
                                                                    <td>{{ $entry->description }}
                                                                        <input type="hidden" name="description[]"
                                                                            value="{{ $entry->description }}">
                                                                    </td>
                                                                    <td>{{ $entry->credit }}
                                                                        <input type="hidden" name="credit[]"
                                                                            value="{{ $entry->credit }}">
                                                                    </td>
                                                                    <!-- Change this to the correct amount field, e.g. debit or credit -->
                                                                    <td>
                                                                        <!-- Delete Entry Button -->
                                                                        <a href="{{ route('cash.destroy', $entry->id) }}"
                                                                            class="btn btn-danger btn-sm"
                                                                            onclick="event.preventDefault();
                                                                    if(confirm('Are you sure you want to delete this transaction?')) {
                                                                        window.location.href='{{ route('cash.destroy', $entry->id) }}';
                                                                    }">Delete</a>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @else
                                                            <p>No entries found for this voucher.</p>
                                                        @endif

                                                    </tbody>
                                                </table>
                                            </div>

                                            <!-- Total Amount Display -->
                                            {{-- <h4 class="text-end">Total Amount: <span
                                                id="totalAmountDisplay">{{ $voucher->total_amount }}</span></h4> --}}
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const today = new Date().toISOString().split('T')[0];

        // Set the value of the input field to the current date
        document.getElementById('entryDate').value = today;

        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('uploadFile');
            const imagePreview = document.getElementById('imagePreview');
            const fileNamePreview = document.getElementById('fileNamePreview');
            const filePreviewContainer = document.getElementById('filePreviewContainer');
            const removeFileButton = document.getElementById('removeFile');

            // Handle file preview
            fileInput.addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (file) {
                    const fileType = file.type;
                    if (fileType.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            imagePreview.src = e.target.result;
                            imagePreview.style.display = 'block'; // Show the image preview
                            fileNamePreview.style.display = 'none'; // Hide the file name for images
                        };
                        reader.readAsDataURL(file);
                    } else {
                        imagePreview.style.display = 'none'; // Hide image preview
                        fileNamePreview.textContent = file.name; // Display file name
                        fileNamePreview.style.display = 'block'; // Show the file name
                    }
                    filePreviewContainer.style.display = 'block'; // Show the preview container
                }
            });

            // Remove file preview
            removeFileButton.addEventListener('click', function() {
                fileInput.value = ''; // Clear the input field
                imagePreview.src = ''; // Clear the image preview
                fileNamePreview.textContent = ''; // Clear the file name preview
                filePreviewContainer.style.display = 'none'; // Hide the preview container
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const addEntryButton = document.getElementById('addEntry');
            const submitButton = document.getElementById('submitVoucher');
            const voucherForm = document.getElementById('voucherForm');
            const entriesTable = document.getElementById('entriesBody');
            let totalAmount = 0;

            // Function to update total amount
            function updateTotalAmount() {
                document.getElementById('totalAmount').value = totalAmount;
            }

            // Add entry event
            addEntryButton.addEventListener('click', function(e) {
                e.preventDefault();
                const date = document.getElementById('entryDate').value;
                const cashId = document.getElementById('entryCash').value;
                const accountId = document.getElementById('entryParty').value;
                const description = document.getElementById('entryDescription').value;
                const amount = parseFloat(document.getElementById('entryAmount').value);

                if (!date || !cashId || !accountId || !description || isNaN(amount)) {
                    alert('Please fill in all fields.');
                    return;
                }

                // Add new entry to the table
                const newRow = entriesTable.insertRow();
                const rowIndex = entriesTable.rows.length - 1;
                newRow.innerHTML = `
                <td>${rowIndex + 1}</td>
                <td>${date}<input type="hidden" name="date[]" value="${date}"></td>
                <td>${document.getElementById('entryCash').options[document.getElementById('entryCash').selectedIndex].text}<input type="hidden" name="cash[]" value="${cashId}"></td>
                <td>${document.getElementById('entryParty').options[document.getElementById('entryParty').selectedIndex].text}<input type="hidden" name="account[]" value="${accountId}"></td>
                <td>${description}<input type="hidden" name="description[]" value="${description}"></td>
                <td>${amount.toFixed(2)}<input type="hidden" name="credit[]" value="${amount.toFixed(2)}"></td>
                <td><button type="button" class="btn btn-danger btn-sm remove-entry">Delete</button></td>
            `;

                totalAmount += amount;
                updateTotalAmount();

                // Clear inputs after adding
                document.getElementById('entryDate').value = today;
                document.getElementById('entryCash').selectedIndex = 0;
                document.getElementById('entryParty').selectedIndex = 0;
                document.getElementById('entryDescription').value = '';
                document.getElementById('entryAmount').value = '';

                // Remove entry functionality
                newRow.querySelector('.remove-entry').addEventListener('click', function() {
                    const amountToRemove = amount;
                    totalAmount -= amountToRemove;
                    updateTotalAmount();
                    newRow.remove();

                    // Update Sr No for each remaining entry
                    Array.from(entriesTable.rows).forEach((r, index) => {
                        r.cells[0].textContent = index + 1;
                    });
                });
            });

            // Submit button event (optional: validate at least one entry exists)
            submitButton.addEventListener('click', function(e) {
                // Optionally, check if at least one entry exists
                if (entriesTable.rows.length === 0) {
                    e.preventDefault();
                    alert('Please add at least one entry before submitting.');
                    return false;
                }
                // Otherwise, allow form to submit
            });
        });
    </script>

@endsection
