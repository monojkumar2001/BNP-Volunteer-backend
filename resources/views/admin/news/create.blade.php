@extends('master.master')

@section('content')
    <div class="page-content">

        <nav class="page-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.news.index') }}">News</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create News</li>
            </ol>
        </nav>

       <div class="row justify-content-center align-items-center" >
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Create News</h6>

                        <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                       <div class="row">
                            <div class="col-md-6">
                                    <div class="mb-3">
                                    <label class="form-label">Title (EN)</label>
                                    <input type="text" name="title_en" class="form-control" value="{{ old('title_en') }}" required />
                                </div>
                            </div>
                            <div class="col-md-6">
                             <div class="mb-3">
                                <label class="form-label">Title (BN)</label>
                                <input type="text" name="title_bn" class="form-control" value="{{ old('title_bn') }}" />
                            </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                <label class="form-label">Slug</label>
                                <input type="text" name="slug" class="form-control" value="{{ old('slug') }}" required />
                                </div>
                            </div>
                         
                            
                       </div>

                          

                          

                      

                            <div class="mb-3">
                                <label class="form-label">Description (EN)</label>
                                <textarea name="content_en" id="easyMdeExample" rows="5" class="form-control" rows="6">{{ old('content_en') }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description (BN)</label>
                                <textarea name="content_bn" class="form-control" id="easyMdeExample2" rows="5">{{ old('content_bn') }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Image</label>
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
