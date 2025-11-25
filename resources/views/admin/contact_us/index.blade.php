@extends('master.master')

@section('content')
    <div class="page-content">

        <nav class="page-breadcrumb d-flex justify-content-between align-items-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Contact</a></li>
                <li class="breadcrumb-item active" aria-current="page">All Contacts</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Contact Us Table</h6>

                        <div class="table-responsive">
                            <table id="dataTableExample" class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Subject</th>
                                        <th>Message Preview</th>
                                        <th>Submitted Date</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($contacts as $key => $contact)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>

                                            <td>
                                                {{ $contact->name ?? 'N/A' }}
                                            </td>
                                            
                                            <td>{{ $contact->email ?? 'N/A' }}</td>

                                            <td>{{ $contact->phone ?? 'N/A' }}</td>

                                            <td>
                                                {{ $contact->subject ?? 'N/A' }}
                                            </td>

                                            <td>
                                                {{ Str::limit($contact->message, 50) ?? 'N/A' }}
                                            </td>

                                            <td>
                                                {{ \Carbon\Carbon::parse($contact->created_at)->format('d-M-Y H:i') }}
                                            </td>
                                       
                                            <td>
                                                @if ($contact->status == 0)
                                                    <span style="font-size:14px; padding:10px 0; font-weight:400;" 
                                                    class="badge px-3 bg-warning">Unread</span>
                                                @else
                                                    <span style="font-size:14px; padding:10px 0; font-weight:400;" 
                                                    class="badge px-3 bg-success">Read</span>
                                                @endif
                                            </td>

                                            <td>
                                                <a href="{{ route('admin.contact_us.show', $contact->id) }}"
                                                    class="btn btn-info btn-icon" title="View">
                                                    <i data-feather="eye"></i>
                                                </a>

                                                <form action="{{ route('admin.contact_us.destroy', $contact->id) }}" 
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Are you sure you want to delete this contact?');">
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



