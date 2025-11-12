@extends('master.master')

@section('content')
    <div class="page-content">

        <nav class="page-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.events.index') }}">Events</a></li>
                <li class="breadcrumb-item active" aria-current="page">View Event</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Event Details</h6>

                        <table class="table table-striped">
                            <tbody>
                                <tr><th>Title (EN)</th><td>{{ $event->title_en }}</td></tr>
                                <tr><th>Title (BN)</th><td>{{ $event->title_bn }}</td></tr>
                                <tr><th>Short Description (EN)</th><td>{!! nl2br(e($event->short_description_en)) !!}</td></tr>
                                <tr><th>Short Description (BN)</th><td>{!! nl2br(e($event->short_description_bn)) !!}</td></tr>
                                <tr><th>Description (EN)</th><td>{!! $event->description_en !!}</td></tr>
                                <tr><th>Description (BN)</th><td>{!! $event->description_bn !!}</td></tr>
                                <tr><th>Event Date</th><td>{{ $event->event_date ? \Carbon\Carbon::parse($event->event_date)->format('d-M-Y') : 'N/A' }}</td></tr>
                                <tr><th>Event Time</th><td>{{ $event->event_time ?? 'N/A' }}</td></tr>
                                <tr><th>Location (EN)</th><td>{{ $event->location_en }}</td></tr>
                                <tr><th>Location (BN)</th><td>{{ $event->location_bn }}</td></tr>
                                <tr><th>Video URL</th><td>{{ $event->video_url }}</td></tr>
                                <tr><th>Slug</th><td>{{ $event->slug }}</td></tr>
                                <tr><th>Status</th><td>{{ $event->status ? 'Active' : 'Inactive' }}</td></tr>
                                <tr><th>Image</th><td>@if($event->image)<img src="{{ asset($event->image) }}" style="height:120px;" alt="img" />@else N/A @endif</td></tr>
                            </tbody>
                        </table>

                        <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">Back</a>
                        <a href="{{ route('admin.events.edit', $event->id) }}" class="btn btn-primary">Edit</a>

                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
