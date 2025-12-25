@extends('master.master')

@section('content')
    <div class="page-content">

        <nav class="page-breadcrumb d-flex justify-content-between align-items-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.contact_us.index') }}">Contact</a></li>
                <li class="breadcrumb-item active" aria-current="page">View Contact</li>
            </ol>
            <a href="{{ route('admin.contact_us.index') }}" class="btn btn-secondary">Back to List</a>
        </nav>

        <div class="row">
            <div class="col-md-8 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Contact Details</h6>

                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <td style="width: 200px;"><strong>Name</strong></td>
                                        <td>{{ $contactUs->name ?? 'N/A' }}</td>
                                    </tr>
                                
                                    <tr>
                                        <td><strong>Email</strong></td>
                                        <td>
                                            @if($contactUs->email)
                                                <a href="mailto:{{ $contactUs->email }}">{{ $contactUs->email }}</a>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                    </tr>

                                    <tr>
                                        <td><strong>Phone</strong></td>
                                        <td>
                                            @if($contactUs->phone)
                                                <a href="tel:{{ $contactUs->phone }}">{{ $contactUs->phone }}</a>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                    </tr>

                                    <tr>
                                        <td><strong>Subject</strong></td>
                                        <td>{{ $contactUs->subject ?? 'N/A' }}</td>
                                    </tr>

                                    <tr>
                                        <td><strong>Message</strong></td>
                                        <td>
                                            <div >
                                                {{ $contactUs->message ?? 'N/A' }}
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td><strong>Status</strong></td>
                                        <td>
                                            @if ($contactUs->status == 0)
                                                <span class="badge bg-warning">Unread</span>
                                            @else
                                                <span class="badge bg-success">Read</span>
                                            @endif
                                        </td>
                                    </tr>

                                    <tr>
                                        <td><strong>Submitted Date</strong></td>
                                        <td>{{ \Carbon\Carbon::parse($contactUs->created_at)->format('d-M-Y h:i A') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4 d-flex gap-2">
                            <form action="{{ route('admin.contact_us.update', $contactUs->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="{{ $contactUs->status == 0 ? 1 : 0 }}">
                                <button type="submit" class="btn btn-primary">
                                    {{ $contactUs->status == 0 ? 'Mark as Read' : 'Mark as Unread' }}
                                </button>
                            </form>

                            <form action="{{ route('admin.contact_us.destroy', $contactUs->id) }}" 
                                method="POST" class="d-inline"
                                onsubmit="return confirm('Are you sure you want to delete this contact?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>

                            <a href="{{ route('admin.contact_us.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection



