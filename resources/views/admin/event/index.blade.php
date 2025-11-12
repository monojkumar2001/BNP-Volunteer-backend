@extends('master.master')

@section('content')
    <div class="page-content">

        <nav class="page-breadcrumb d-flex justify-content-between align-items-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Events</a></li>
                <li class="breadcrumb-item active" aria-current="page">All Events</li>
            </ol>
            <a href="{{ route('admin.events.create') }}" class="btn btn-primary active" role="button" aria-pressed="true">Create Event</a>

        </nav>

        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Events Table</h6>

                        <div class="table-responsive">
                            <table id="dataTableExample" class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Title (EN)</th>
                                        <th>Title (BN)</th>
                                        <th>Short (EN)</th>
                                        <th>Short (BN)</th>
                                        <th>Image</th>
                                        <th>Event Date</th>
                                        <th>Event Time</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($events as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ Str::limit($item->title_en ?? 'N/A', 40) }}</td>
                                            <td>{{ Str::limit($item->title_bn ?? '-', 40) }}</td>
                                            <td>{{ Str::limit($item->short_description_en ?? '-', 40) }}</td>
                                            <td>{{ Str::limit($item->short_description_bn ?? '-', 40) }}</td>
                                            <td>
                                                @if ($item->image)
                                                    <img src="{{ asset($item->image) }}" alt="img" style="height:40px;" />
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>{{ $item->event_date ? \Carbon\Carbon::parse($item->event_date)->format('d-M-Y') : 'N/A' }}</td>
                                            <td>{{ $item->event_time ?? 'N/A' }}</td>
                                            <td>
                                                <a href="{{ route('admin.events.edit', $item->id) }}" class="btn btn-primary btn-icon" title="Edit"><i data-feather="edit"></i></a>
                                                <form id="delete_form_{{ $item->id }}" action="{{ route('admin.events.destroy', $item->id) }}" method="post" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-danger btn-icon delete-button" onclick="if(confirm('Are you sure?')){ document.getElementById('delete_form_{{ $item->id }}').submit(); }" title="Delete">
                                                        <i data-feather="trash"></i>
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
