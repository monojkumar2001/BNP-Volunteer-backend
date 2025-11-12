@extends('master.master')

@section('content')
    <div class="page-content">

        <nav class="page-breadcrumb d-flex justify-content-between align-items-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.volunteer.index') }}">Volunteer</a></li>
                <li class="breadcrumb-item active" aria-current="page">View Volunteer</li>
            </ol>
            <a href="{{ route('admin.volunteer.index') }}" class="btn btn-secondary">Back to List</a>
        </nav>

        <div class="row">
            <div class="col-md-8 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Volunteer Details</h6>

                        <form action="{{ route('admin.volunteer.update', $volunteer->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td><strong>Name</strong></td>
                                            <td>{{ $volunteer->name }}</td>
                                        </tr>
                                    
                                        <tr>
                                            <td><strong>Phone</strong></td>
                                            <td>{{ $volunteer->phone ?? 'N/A' }}</td>
                                        </tr>
                                       
                                        <tr>
                                            <td><strong>Status</strong></td>
                                            <td>
                                                <select name="status" class="form-control form-control-sm" style="width: 150px;">
                                                    <option value="pending" {{ $volunteer->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                    <option value="approved" {{ $volunteer->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                                    <option value="rejected" {{ $volunteer->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Registered Date</strong></td>
                                            <td>{{ \Carbon\Carbon::parse($volunteer->created_at)->format('d-M-Y H:i') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4 d-flex justify-content-between">
                                <button type="submit" class="btn btn-primary">Update Status</button>
                                <a href="{{ route('admin.volunteer.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
