@extends('master.master')

@section('content')
    <div class="page-content">

        <nav class="page-breadcrumb d-flex justify-content-between align-items-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Opinion</a></li>
                <li class="breadcrumb-item active" aria-current="page">All Opinions & Complaints</li>
            </ol>
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

