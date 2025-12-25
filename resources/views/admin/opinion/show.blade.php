@extends('master.master')

@section('content')
    <div class="page-content">

        <nav class="page-breadcrumb d-flex justify-content-between align-items-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.opinion.index') }}">Opinion</a></li>
                <li class="breadcrumb-item active" aria-current="page">View Opinion</li>
            </ol>
            <a href="{{ route('admin.opinion.index') }}" class="btn btn-secondary">Back to List</a>
        </nav>

        <div class="row">
            <div class="col-md-8 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Opinion & Complaint Details</h6>

                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <td style="width: 200px;"><strong>Name</strong></td>
                                        <td>{{ $opinion->name ?? 'N/A' }}</td>
                                    </tr>
                                
                                    <tr>
                                        <td><strong>Phone</strong></td>
                                        <td>{{ $opinion->phone ?? 'N/A' }}</td>
                                    </tr>

                                    <tr>
                                        <td><strong>Category</strong></td>
                                        <td>
                                            @php
                                                $categories = [
                                                    '1' => [
                                                        'bn' => 'আপনার পরামর্শ বা অভিমত জানান',
                                                        'en' => 'Share your advice or opinion'
                                                    ],
                                                    '2' => [
                                                        'bn' => 'অভিযোগ জানান',
                                                        'en' => 'Report a complaint'
                                                    ],
                                                    '3' => [
                                                        'bn' => 'চাঁদাবাজি, সংঘর্ষ বা আইন-শৃঙ্খলা সংক্রান্ত ইনসিডেন্ট রিপোর্ট করুন',
                                                        'en' => 'Report extortion, conflict or law and order incident'
                                                    ],
                                                    '4' => [
                                                        'bn' => 'অন্য যে কোন বিষয়ে যোগাযোগ করুন',
                                                        'en' => 'Contact for any other matter'
                                                    ]
                                                ];
                                                $cat = $categories[$opinion->category] ?? null;
                                            @endphp
                                            @if($cat)
                                                <span >
                                                    {{ $cat['bn'] }} / {{ $cat['en'] }}
                                                </span>
                                            @else
                                                {{ $opinion->category }}
                                            @endif
                                        </td>
                                    </tr>

                                    @if($opinion->location)
                                    <tr>
                                        <td><strong>Location</strong></td>
                                        <td>{{ $opinion->location }}</td>
                                    </tr>
                                    @endif

                                    <tr>
                                        <td><strong>Message</strong></td>
                                        <td>
                                            <div >
                                                {{ $opinion->message }}
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td><strong>Status</strong></td>
                                        <td>
                                            @if ($opinion->status == 0)
                                                <span class="badge bg-warning">Unread</span>
                                            @else
                                                <span class="badge bg-success">Read</span>
                                            @endif
                                        </td>
                                    </tr>

                                    <tr>
                                        <td><strong>Submitted Date</strong></td>
                                        <td>{{ \Carbon\Carbon::parse($opinion->created_at)->format('d-M-Y h:i A') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4 d-flex gap-2">
                            <form action="{{ route('admin.opinion.update', $opinion->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="{{ $opinion->status == 0 ? 1 : 0 }}">
                                <button type="submit" class="btn btn-primary">
                                    {{ $opinion->status == 0 ? 'Mark as Read' : 'Mark as Unread' }}
                                </button>
                            </form>

                            <form action="{{ route('admin.opinion.destroy', $opinion->id) }}" 
                                method="POST" class="d-inline"
                                onsubmit="return confirm('Are you sure you want to delete this opinion?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>

                            <a href="{{ route('admin.opinion.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

