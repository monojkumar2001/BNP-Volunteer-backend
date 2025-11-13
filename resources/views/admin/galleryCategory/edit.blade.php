@extends('master.master')

@section('content')
    <div class="page-content">

        <nav class="page-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.galleryCategory.index') }}"> Gallery Category</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Gallery Category</li>
            </ol>
        </nav>

        <div class="row justify-content-center align-items-center">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Edit Gallery Category</h6>

                        <form action="{{ route('admin.galleryCategory.update', $galleryCategory->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                 <div class="col-md-6">
                                       <div class="mb-3">
                                         <label class="form-label">Name (EN)</label>
                                         <input type="text" name="name_en" class="form-control" value="{{ old('name_en', $galleryCategory->name_en) }}" required />
                                     </div>

                                </div>
                                <div class="col-md-6">
                                 <div class="mb-3">
                                <label class="form-label">Name (BN)</label>
                                <input type="text" name="name_bn" class="form-control" value="{{ old('name_bn', $galleryCategory->name_bn) }}" />
                            </div>
                                </div>

                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-control">
                                    <option value="1" {{ $galleryCategory->status ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ !$galleryCategory->status ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>

                    <div class="d-flex justify-content-between">
                                <button class="btn btn-primary" type="submit">Update</button>
                            <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">Cancel</a>
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
