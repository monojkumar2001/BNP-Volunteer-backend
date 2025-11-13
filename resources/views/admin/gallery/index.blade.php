@extends('master.master')

@section('content')
    <div class="page-content">

        <nav class="page-breadcrumb d-flex justify-content-between align-items-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Gallery </a></li>
                <li class="breadcrumb-item active" aria-current="page">All Gallery </li>
            </ol>
            <a href="{{ route('admin.gallery.create') }}" class="btn btn-primary active" role="button" aria-pressed="true">Create Gallery </a>

        </nav>

        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Gallery Table</h6>

                        <div class="table-responsive">
                            <table id="dataTableExample" class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                           <th>Image</th>         
                                           <th>Created</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($galleries as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                          <td>
                                                @if ($item->image)
                                                    <img src="{{ asset($item->image) }}" alt="img" style="height:40px;" />
                                                @else
                                                    -
                                                @endif
                                            </td>
                                             <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d-M-Y') }}</td>
                                            <td>
                                                @if ($item->status)
                                                    <span  style="font-size:14px; padding:10px 0;  font-weight:400;" 
                                                    class="badge px-3 bg-success">Active</span>
                                                @else
                                                    <span  style="font-size:14px; padding:10px 0;  font-weight:400;" 
                                                    class="badge px-3 bg-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.gallery.edit', $item->id) }}" class="btn btn-primary btn-icon" title="Edit"><i data-feather="edit"></i></a>
                                                <form id="delete_form_{{ $item->id }}" action="{{ route('admin.gallery.destroy', $item->id) }}" method="post" class="d-inline">
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
