@extends('master.master')

@section('content')
    <div class="page-content">

        <nav class="page-breadcrumb d-flex justify-content-between align-items-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Volunteer</a></li>
                <li class="breadcrumb-item active" aria-current="page">All Volunteer</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Volunteer Table</h6>

                        <div class="table-responsive">
                            <table id="dataTableExample" class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name </th>
                                        <th>Phone</th>
                                        <th>Create Date</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($volunteers as $key => $volunteer)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>

                                            <td>
                                            {{ $volunteer->name }}
                                            </td>
                                            <td>{{ $volunteer->phone }}</td>

                                            <td>
                                                {{ \Carbon\Carbon::parse($volunteer->created_at)->format('d-M-Y') }}
                                            </td>
                                       
                                            <td>
                                                @if ($volunteer->status == 'pending')
                                                    <span
                                                    style="font-size:14px; padding:10px 0;  font-weight:400;" 
                                                    class="badge px-3 bg-warning">Pending</span>
                                                @elseif($volunteer->status == 'approved')
                                                    <span  style="font-size:14px; padding:10px 0;  font-weight:400;" 
                                                    class="badge px-3 bg-success">Approved</span>
                                                @elseif($volunteer->status == 'rejected')
                                                    <span  style="font-size:14px; padding:10px 0;  font-weight:400;" 
                                                    class="badge px-3 bg-danger">Rejected</span>
                                                @endif
                                            </td>

                                            <td>
                                                <a href="{{ route('admin.volunteer.show', $volunteer->id) }}"
                                                    class="btn btn-info btn-icon" title="View">
                                                    <i data-feather="eye"></i></a>

                                                @if ($volunteer->status == 'pending')
                                                    <form action="{{ route('admin.volunteer.update', $volunteer->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="approved">
                                                        <button type="submit" class="btn btn-success btn-icon" title="Approve">
                                                            <i data-feather="check"></i>
                                                        </button>
                                                    </form>

                                                    <form action="{{ route('admin.volunteer.update', $volunteer->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="rejected">
                                                        <button type="submit" class="btn btn-danger btn-icon" title="Reject">
                                                            <i data-feather="x"></i>
                                                        </button>
                                                    </form>
                                                @endif
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
