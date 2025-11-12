{{-- @extends('master.master')

@section('content')
    <div class="page-content">

        <nav class="page-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Forms</a></li>
                <li class="breadcrumb-item active" aria-current="page">Basic Elements</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">

                        <h6 class="card-title">Basic Form</h6>

                        <form class="forms-sample" action="{{ route('admin.surveys.store') }}" method="POST"
                            id="surveyForm">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="title" class="form-label">Title</label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                                            id="title" name="title" value="{{ old('title') }}" placeholder="Title"
                                            required>
                                        @error('title')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="start_date" class="form-label">Start Date</label>
                                        <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                                            id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                                        @error('start_date')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="end_date" class="form-label">End Date</label>
                                        <input type="date" class="form-control @error('end_date') is-invalid @enderror"
                                            id="end_date" name="end_date" value="{{ old('end_date') }}" required>
                                        @error('end_date')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" name="description" id="tinymceExample"
                                            rows="5">{{ old('description') }}</textarea>
                                        @error('description')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-check mb-3">
                                        <input type="checkbox" class="form-check-input" id="status" name="status"
                                            {{ old('status', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="status">
                                            Active
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-6 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary me-2">Submit</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            const startDate = document.getElementById('start_date');
            const endDate = document.getElementById('end_date');

            // start_date can't be before today
            startDate.setAttribute('min', today);

            // end_date can't be before start_date
            startDate.addEventListener('change', function() {
                endDate.value = ''; // clear end date if user changes start date
                endDate.setAttribute('min', this.value);
            });
        });
    </script>
@endsection --}}





@extends('master.master')

@section('content')
    <div class="page-content">
        <h4>Create Survey</h4>

        <form action="{{ route('admin.surveys.store') }}" method="POST">
            @csrf

            {{-- Survey Basic Info --}}
            <div class="card p-3 mb-4">
                <div class="mb-3">
                    <label>Survey Title</label>
                    <input type="text" name="title" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="3"></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Start Date</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>End Date</label>
                        <input type="date" name="end_date" id="end_date" class="form-control" required>
                    </div>
                </div>

                <div class="form-check mb-3">
                    <input type="checkbox" name="is_active" class="form-check-input" id="status" checked>
                    <label for="status" class="form-check-label">Active</label>
                </div>
            </div>

            {{-- Dynamic Questions --}}
            <div id="questions-container"></div>

            <button type="button" class="btn btn-success mb-3" id="add-question">+ Add Question</button>
            <br>

            <button type="submit" class="btn btn-primary">Save Survey</button>
        </form>
    </div>

    <script>
        let questionIndex = 0;

        document.getElementById('add-question').addEventListener('click', function() {
            const questionHtml = `
                <div class="card p-3 mb-4 question-block border position-relative">
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-question"></button>
                    <div class="mb-2">
                        <label>Question</label>
                        <input type="text" name="questions[${questionIndex}][question_text]" class="form-control" required>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-6">
                            <label>Type</label>
                            <select name="questions[${questionIndex}][question_type]" class="form-select">
                                <option value="single">Single Choice</option>
                                <option value="multiple">Multiple Choice</option>
                            </select>
                        </div>
                        <div class="col-md-6 mt-4">
                            <div class="form-check">
                                <input type="checkbox" name="questions[${questionIndex}][is_required]" class="form-check-input" checked>
                                <label class="form-check-label">Required</label>
                            </div>
                        </div>
                    </div>

                    <div class="options-container">
                        <label>Options</label>
                        <div class="option-group input-group mb-2">
                            <input type="text" name="questions[${questionIndex}][options][]" class="form-control" required>
                            <button type="button" class="btn btn-danger remove-option">✕</button>
                        </div>
                    </div>

                    <button type="button" class="btn btn-sm btn-secondary add-option">+ Add Option</button>
                </div>
            `;

            document.getElementById('questions-container').insertAdjacentHTML('beforeend', questionHtml);
            questionIndex++;
        });

        // Add Option
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('add-option')) {
                const container = e.target.closest('.question-block').querySelector('.options-container');
                const qIndex = Array.from(document.querySelectorAll('.question-block')).indexOf(e.target.closest(
                    '.question-block'));
                const optionHtml = `
                    <div class="option-group input-group mb-2">
                        <input type="text" name="questions[${qIndex}][options][]" class="form-control" required>
                        <button type="button" class="btn btn-danger remove-option">✕</button>
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', optionHtml);
            }
        });

        // Remove Option
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-option')) {
                e.target.closest('.option-group').remove();
            }
        });

        // Remove Question
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-question')) {
                e.target.closest('.question-block').remove();
            }
        });

        // Set min date for validation
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('start_date').min = today;

            document.getElementById('start_date').addEventListener('change', function() {
                document.getElementById('end_date').min = this.value;
            });
        });
    </script>
@endsection
