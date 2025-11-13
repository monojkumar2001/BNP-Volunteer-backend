@extends('master.master')

@section('content')
    <div class="page-content">

        <nav class="page-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.gallery.index') }}">Gallery</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create Gallery </li>
            </ol>
        </nav>

       <div class="row " >
            <div class="col-md-8 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Create Gallery</h6>

                        <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                       <div class="mb-3">
    <label class="form-label">Gallery Category</label>
    <select name="gallery_category_id" class="form-control" required>
        <option value="" disabled selected>Select Category</option>
        @foreach($galleryCategories as $category)
            <option value="{{ $category->id }}" {{ old('gallery_category_id') == $category->id ? 'selected' : '' }}>
                {{ $category->name_en }}
            </option>
        @endforeach
    </select>
</div>

                         <div class="mb-3">
                                <label class="form-label"> Gallery Image</label>
                                <input type="file" name="image" class="form-control" />
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-control">
                                    <option value="1" selected>Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                           <div class="d-flex justify-content-between">
                            <button class="btn btn-primary" type="submit">Create</button>
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
            const titleInput = document.querySelector('input[name="name_en"]');
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
                // only update slug if user hasn't edited it manually
                if (!slugInput.dataset.userEdited || slugInput.value === lastAuto) {
                    slugInput.value = newSlug;
                    lastAuto = newSlug;
                }
            });

            slugInput.addEventListener('input', function(){
                // mark as user edited if value differs from lastAuto
                slugInput.dataset.userEdited = (this.value !== lastAuto).toString();
            });
        })();
    </script>
@endsection
