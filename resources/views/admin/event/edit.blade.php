@extends('master.master')

@section('content')
    <div class="page-content">

        <nav class="page-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.events.index') }}">Events</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Event</li>
            </ol>
        </nav>

        <div class="row justify-content-center align-items-center">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Edit Event</h6>

                        <form action="{{ route('admin.events.update', $event->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                 <div class="col-md-6">
                                       <div class="mb-3">
                                         <label class="form-label">Title (EN)</label>
                                         <input type="text" name="title_en" class="form-control" value="{{ old('title_en', $event->title_en) }}" required />
                                     </div>

                                </div>
                                <div class="col-md-6">
                                 <div class="mb-3">
                                <label class="form-label">Title (BN)</label>
                                <input type="text" name="title_bn" class="form-control" value="{{ old('title_bn', $event->title_bn) }}" />
                            </div>
                                </div>
                                
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Event Date</label>
                                    <input type="date" name="event_date" class="form-control" value="{{ old('event_date', $event->event_date) }}" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Event Time</label>
                                    <input type="time" name="event_time" class="form-control" value="{{ old('event_time', $event->event_time) }}" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Location (EN)</label>
                                    <input type="text" name="location_en" class="form-control" value="{{ old('location_en', $event->location_en) }}" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Location (BN)</label>
                                    <input type="text" name="location_bn" class="form-control" value="{{ old('location_bn', $event->location_bn) }}" />
                                </div>
                            </div>
                         

                          

                        

                            <div class="mb-3">
                                <label class="form-label">Short Description (EN)</label>
                                <textarea name="short_description_en" class="form-control">{{ old('short_description_en', $event->short_description_en) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Short Description (BN)</label>
                                <textarea name="short_description_bn" class="form-control">{{ old('short_description_bn', $event->short_description_bn) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description (EN)</label>
                                <textarea name="description_en" class="form-control" id="easyMdeExample" rows="5" >{{ old('description_en', $event->description_en) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description (BN)</label>
                                <textarea name="description_bn" class="form-control" id="easyMdeExample2" rows="5" >{{ old('description_bn', $event->description_bn) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Video URL (optional)</label>
                                <input type="url" name="video_url" class="form-control" value="{{ old('video_url', $event->video_url) }}" />
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Image</label>
                                @if ($event->image)
                                    <div class="mb-2"><img src="{{ asset($event->image) }}" style="height:80px;" alt="img" /></div>
                                @endif
                                <input type="file" name="image" class="form-control" />
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-control">
                                    <option value="1" {{ $event->status ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ !$event->status ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>

                    <div class="d-flex justify-content-between">
                                <button class="btn btn-primary" type="submit">Update</button>
                            <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

    </div>
    <script>
        (function(){
            const titleInput = document.querySelector('input[name="title_en"]');
            const slugInput = document.querySelector('input[name="slug"]');
            if (!titleInput || !slugInput) return;

            let lastAuto = slugInput.value || '';

            function slugify(text){
                return text.toString().toLowerCase().trim()
                    .replace(/[\u2000-\u206F\u2E00-\u2E7F'"!@#\$%\^&\*\(\)\=\+\[\]{};:\\|,.<>\/?~`]+/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-');
            }

            titleInput.addEventListener('input', function(){
                const newSlug = slugify(this.value);
                if (!slugInput.dataset.userEdited || slugInput.value === lastAuto) {
                    slugInput.value = newSlug;
                    lastAuto = newSlug;
                }
            });

            slugInput.addEventListener('input', function(){
                slugInput.dataset.userEdited = (this.value !== lastAuto).toString();
            });
        })();
    </script>
@endsection
