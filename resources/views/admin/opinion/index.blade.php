@extends('master.master')

@section('content')
    <div class="page-content">

        <nav class="page-breadcrumb d-flex justify-content-between align-items-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Opinion</a></li>
                <li class="breadcrumb-item active" aria-current="page">All Opinions & Complaints</li>
            </ol>
            <div class="d-flex gap-2">
                <!-- <form id="selectedPdfForm" action="{{ route('admin.opinion.download-selected-pdf') }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="ids" id="selectedIds">
                    <button type="submit" id="downloadSelectedBtn" class="btn btn-danger" disabled>
                        <i data-feather="download"></i> Download Selected PDF
                    </button>
                </form> -->
                <a href="{{ route('admin.opinion.export-excel') }}" class="btn btn-success">
                    <i data-feather="file-text"></i> Export to Excel
                </a>
            </div>
        </nav>

        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Opinions & Complaints Table</h6>

                        <div class="table-responsive">
                            <table id="dataTableExample" class="table">
                                <thead>
                                    <tr>
                                        <!-- <th style="width: 40px;">
                                            <input type="checkbox" id="selectAll" title="Select All">
                                        </th> -->
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Category</th>
                                        <th>Message Preview</th>
                                        <th>Submitted Date</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($opinions as $key => $opinion)
                                        <tr>
                                            <!-- <td>
                                                <input type="checkbox" class="opinion-checkbox" value="{{ $opinion->id }}">
                                            </td> -->
                                            <td>{{ $key + 1 }}</td>

                                            <td>
                                                {{ $opinion->name ?? 'N/A' }}
                                            </td>
                                            
                                            <td>{{ $opinion->phone ?? 'N/A' }}</td>

                                            <td>
                                                @php
                                                    $categories = [
                                                        '1' => 'পরামর্শ/অভিমত',
                                                        '2' => 'অভিযোগ',
                                                        '3' => 'চাঁদাবাজি/সংঘর্ষ রিপোর্ট',
                                                        '4' => 'অন্যান্য যোগাযোগ'
                                                    ];
                                                    $categoryLabels = [
                                                        '1' => 'Advice/Opinion',
                                                        '2' => 'Complaint',
                                                        '3' => 'Extortion/Conflict Report',
                                                        '4' => 'Other Contact'
                                                    ];
                                                @endphp
                                                <span >
                                                    {{ $categories[$opinion->category] ?? $categoryLabels[$opinion->category] ?? 'N/A' }}
                                                </span>
                                            </td>

                                            <td>
                                                {{ Str::limit($opinion->message, 50) }}
                                            </td>

                                            <td>
                                                {{ \Carbon\Carbon::parse($opinion->created_at)->format('d-M-Y H:i') }}
                                            </td>
                                       
                                            <td>
                                                @if ($opinion->status == 0)
                                                    <span style="font-size:14px; padding:10px 0; font-weight:400;" 
                                                    class="badge px-3 bg-warning">Unread</span>
                                                @else
                                                    <span style="font-size:14px; padding:10px 0; font-weight:400;" 
                                                    class="badge px-3 bg-success">Read</span>
                                                @endif
                                            </td>

                                            <td>
                                                <a href="{{ route('admin.opinion.show', $opinion->id) }}"
                                                    class="btn btn-info btn-icon" title="View">
                                                    <i data-feather="eye"></i>
                                                </a>

                                                <form action="{{ route('admin.opinion.destroy', $opinion->id) }}" 
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Are you sure you want to delete this opinion?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-icon" title="Delete">
                                                        <i data-feather="trash-2"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllCheckbox = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.opinion-checkbox');
        const downloadSelectedBtn = document.getElementById('downloadSelectedBtn');
        const selectedIdsInput = document.getElementById('selectedIds');
        const selectedPdfForm = document.getElementById('selectedPdfForm');

        // Select all functionality
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateDownloadButton();
            });
        }

        // Individual checkbox change
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateSelectAllState();
                updateDownloadButton();
            });
        });

        // Update select all checkbox state
        function updateSelectAllState() {
            if (selectAllCheckbox) {
                const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                const someChecked = Array.from(checkboxes).some(cb => cb.checked);
                selectAllCheckbox.checked = allChecked;
                selectAllCheckbox.indeterminate = someChecked && !allChecked;
            }
        }

        // Update download button state
        function updateDownloadButton() {
            const selectedIds = Array.from(checkboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.value);
            
            if (selectedIds.length > 0) {
                downloadSelectedBtn.disabled = false;
                selectedIdsInput.value = JSON.stringify(selectedIds);
            } else {
                downloadSelectedBtn.disabled = true;
                selectedIdsInput.value = '';
            }
        }

        // Form submission
        if (selectedPdfForm) {
            selectedPdfForm.addEventListener('submit', function(e) {
                const selectedIds = JSON.parse(selectedIdsInput.value || '[]');
                if (selectedIds.length === 0) {
                    e.preventDefault();
                    alert('Please select at least one opinion to download!');
                    return false;
                }
            });
        }
    });
</script>
@endpush