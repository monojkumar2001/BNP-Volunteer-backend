@extends('master.master')

@section('content')
    <div class="page-content">
        <h4>Edit Survey</h4>

        <form action="{{ route('admin.surveys.update', $survey->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="card p-3 mb-4">
                <div class="mb-3">
                    <label>Survey Title</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $survey->title) }}"
                        required>
                    @error('title')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description', $survey->description) }}</textarea>
                    @error('description')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Start Date</label>
                        <input type="date" name="start_date" class="form-control"
                            value="{{ old('start_date', $survey->start_date->format('Y-m-d')) }}" required>
                        @error('start_date')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>End Date</label>
                        <input type="date" name="end_date" class="form-control"
                            value="{{ old('end_date', $survey->end_date->format('Y-m-d')) }}" required>
                        @error('end_date')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-check mb-3">
                    {{-- Hidden input to ensure '0' is sent if checkbox is unchecked --}}
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" class="form-check-input" id="status"
                        {{ old('is_active', $survey->is_active) ? 'checked' : '' }} value="1">
                    <label for="status" class="form-check-label">Active</label>
                    @error('is_active')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Questions --}}
            <div id="questions-container">
                @foreach ($survey->questions as $qIndex => $question)
                    <div class="card p-3 mb-4 question-block border position-relative">
                        <input type="hidden" name="questions[{{ $qIndex }}][id]" value="{{ $question->id }}">

                        <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-question"></button>

                        <div class="mb-2">
                            <label>Question</label>
                            <input type="text" name="questions[{{ $qIndex }}][question_text]" class="form-control"
                                value="{{ old("questions.$qIndex.question_text", $question->question_text) }}" required>
                            @error("questions.$qIndex.question_text")
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label>Type</label>
                                <select name="questions[{{ $qIndex }}][question_type]" class="form-select">
                                    <option value="single"
                                        {{ old("questions.$qIndex.question_type", $question->question_type) == 'single' ? 'selected' : '' }}>
                                        Single</option>
                                    <option value="multiple"
                                        {{ old("questions.$qIndex.question_type", $question->question_type) == 'multiple' ? 'selected' : '' }}>
                                        Multiple</option>
                                </select>
                                @error("questions.$qIndex.question_type")
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mt-4">
                                <div class="form-check">
                                    {{-- Hidden input to ensure '0' is sent if checkbox is unchecked --}}
                                    <input type="hidden" name="questions[{{ $qIndex }}][is_required]"
                                        value="0">
                                    <input type="checkbox" name="questions[{{ $qIndex }}][is_required]"
                                        class="form-check-input"
                                        {{ old("questions.$qIndex.is_required", $question->is_required) ? 'checked' : '' }}
                                        value="1">
                                    <label class="form-check-label">Required</label>
                                    @error("questions.$qIndex.is_required")
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="options-container">
                            @foreach ($question->options as $optIndex => $option)
                                <div class="option-group input-group mb-2">
                                    <input type="hidden"
                                        name="questions[{{ $qIndex }}][options][{{ $optIndex }}][id]"
                                        value="{{ $option->id }}">
                                    <input type="text"
                                        name="questions[{{ $qIndex }}][options][{{ $optIndex }}][option_text]"
                                        class="form-control"
                                        value="{{ old("questions.$qIndex.options.$optIndex.option_text", $option->option_text) }}"
                                        required>
                                    <button type="button" class="btn btn-danger remove-option">✕</button>
                                    @error("questions.$qIndex.options.$optIndex.option_text")
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endforeach
                        </div>

                        <button type="button" class="btn btn-sm btn-secondary add-option">+ Add Option</button>
                    </div>
                @endforeach
            </div>

            <button type="button" class="btn btn-success mb-3" id="add-question">+ Add Question</button>
            <br>
            <button type="submit" class="btn btn-primary">Update Survey</button>
        </form>
    </div>
@endsection

@section('js')
    <script>
        let questionIndex = {{ $survey->questions->count() }};

        document.getElementById('add-question').addEventListener('click', function() {
            const html = `
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
                            {{-- Hidden input to send 0 if checkbox is unchecked --}}
                            <input type="hidden" name="questions[${questionIndex}][is_required]" value="0">
                            <input type="checkbox" name="questions[${questionIndex}][is_required]" class="form-check-input" checked value="1">
                            <label class="form-check-label">Required</label>
                        </div>
                    </div>
                </div>

                <div class="options-container">
                    <div class="option-group input-group mb-2">
                        <input type="text" name="questions[${questionIndex}][options][0][option_text]" class="form-control" required>
                        <button type="button" class="btn btn-danger remove-option">✕</button>
                    </div>
                </div>

                <button type="button" class="btn btn-sm btn-secondary add-option">+ Add Option</button>
            </div>
        `;

            document.getElementById('questions-container').insertAdjacentHTML('beforeend', html);
            questionIndex++;
        });

        // Remove question
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-question')) {
                e.target.closest('.question-block').remove();
            }
        });

        // Add Option
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('add-option')) {
                const container = e.target.closest('.question-block').querySelector('.options-container');
                const qIndexElement = e.target.closest('.question-block');
                const qIndex = Array.from(document.querySelectorAll('.question-block')).indexOf(qIndexElement);
                const optionCount = container.querySelectorAll('.option-group').length;

                const optionHtml = `
                    <div class="option-group input-group mb-2">
                        <input type="text" name="questions[${qIndex}][options][${optionCount}][option_text]" class="form-control" required>
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
    </script>
@endsection
