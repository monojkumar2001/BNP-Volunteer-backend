@extends('master.master')

@section('content')
    <div class="page-content">

        <nav class="page-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.central_bnp.index') }}">Central BNP</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Central BNP</li>
            </ol>
        </nav>

        <div class="row justify-content-center align-items-center">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Edit Central BNP</h6>

                        <form action="{{ route('admin.central_bnp.update', $centralBnp->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                 <div class="col-md-6">
                                       <div class="mb-3">
                                         <label class="form-label">Title (EN)</label>
                                         <input type="text" name="title_en" class="form-control" value="{{ old('title_en', $centralBnp->title_en) }}" required />
                                     </div>

                                </div>
                                <div class="col-md-6">
                                 <div class="mb-3">
                                <label class="form-label">Title (BN)</label>
                                <input type="text" name="title_bn" class="form-control" value="{{ old('title_bn', $centralBnp->title_bn) }}" />
                            </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                    <label class="form-label">Short Description (EN)</label>
                                    <textarea name="short_description_en" class="form-control" rows="3">{{ old('short_description_en', $centralBnp->short_description_en) }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                    <label class="form-label">Short Description (BN)</label>
                                    <textarea name="short_description_bn" class="form-control" rows="3">{{ old('short_description_bn', $centralBnp->short_description_bn) }}</textarea>
                                    </div>
                                </div>
                            <div class="mb-3">
                                <label class="form-label">Description (EN)</label>
                                <textarea name="content_en" id="tinymceContentEn" class="form-control" rows="5" >{{ old('content_en', $centralBnp->content_en) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description (BN)</label>
                                <textarea name="content_bn" id="tinymceContentBn" class="form-control" rows="5" >{{ old('content_bn', $centralBnp->content_bn) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Image</label>
                                @if ($centralBnp->image)
                                    <div class="mb-2"><img src="{{ asset($centralBnp->image) }}" style="height:80px;" alt="img" /></div>
                                @endif
                                <input type="file" name="image" class="form-control" />
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-control">
                                    <option value="1" {{ $centralBnp->status ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ !$centralBnp->status ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>

                    <div class="d-flex justify-content-between">
                                <button class="btn btn-primary" type="submit">Update</button>
                            <a href="{{ route('admin.central_bnp.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('js')

<script src="{{ asset('assets/vendors/tinymce/tinymce.min.js') }}"></script>
<script>
    $(document).ready(function() {
        if (typeof tinymce !== 'undefined') {
            tinymce.init({
                selector: '#tinymceContentEn',
                height: 400,
                menubar: false,
                plugins: [
                    'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                    'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                    'insertdatetime', 'media', 'table', 'code', 'help', 'wordcount'
                ],
                toolbar: 'undo redo | blocks | ' +
                    'bold italic underline strikethrough | fontsize fontfamily | forecolor backcolor | ' +
                    'alignleft aligncenter alignright alignjustify | ' +
                    'bullist numlist outdent indent | ' +
                    'removeformat | link image table | code fullscreen | help',
                font_size_formats: '8pt 9pt 10pt 11pt 12pt 14pt 16pt 18pt 20pt 22pt 24pt 28pt 30pt 36pt 48pt 64pt',
                font_family_formats: 'Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Comic Sans MS=comic sans ms,sans-serif;Courier New=courier new,courier;Helvetica=helvetica;Impact=impact,chicago;Lucida Grande=lucida grande;Tahoma=tahoma,arial,helvetica,sans-serif;Times New Roman=times new roman,times;Verdana=verdana,geneva',
                content_style: 'body { font-family: Arial, sans-serif; font-size: 14px; color: #fff; background-color: #1e293b; }',
                branding: false,
                promotion: false
            });

            tinymce.init({
                selector: '#tinymceContentBn',
                height: 400,
                menubar: false,
                plugins: [
                    'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                    'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                    'insertdatetime', 'media', 'table', 'code', 'help', 'wordcount'
                ],
                toolbar: 'undo redo | blocks | ' +
                    'bold italic underline strikethrough | fontsize fontfamily | forecolor backcolor | ' +
                    'alignleft aligncenter alignright alignjustify | ' +
                    'bullist numlist outdent indent | ' +
                    'removeformat | link image table | code fullscreen | help',
                font_size_formats: '8pt 9pt 10pt 11pt 12pt 14pt 16pt 18pt 20pt 22pt 24pt 28pt 30pt 36pt 48pt 64pt',
                font_family_formats: 'Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Comic Sans MS=comic sans ms,sans-serif;Courier New=courier new,courier;Helvetica=helvetica;Impact=impact,chicago;Lucida Grande=lucida grande;Tahoma=tahoma,arial,helvetica,sans-serif;Times New Roman=times new roman,times;Verdana=verdana,geneva',
                content_style: 'body { font-family: Arial, sans-serif; font-size: 14px; color: #fff; background-color: #1e293b; }',
                branding: false,
                promotion: false
            });
        } else {
            console.error('TinyMCE is not loaded. Please check CDN link.');
        }
    });
</script>
@endsection

