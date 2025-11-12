@extends('master.master')

@section('content')
    <div class="page-content">
    <h3>Survey Report: {{ $survey->title }}</h3>
    <p>Total Responses: <strong>{{ $totalResponses }}</strong></p>

    @foreach ($survey->questions as $question)
        <div class="card mt-4">
            <div class="card-body">
                <h5>{{ $loop->iteration }}. {{ $question->question_text }}</h5>

                @php
                    $totalAnswers = $question->options->sum(fn($opt) => $opt->responseDetails->count());
                @endphp

                <ul class="list-group mt-3">
                    @foreach ($question->options as $option)
                        @php
                            $count = $option->responseDetails->count();
                            $percentage = $totalAnswers > 0 ? number_format(($count / $totalAnswers) * 100, 2) : 0;
                        @endphp
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $option->option_text }}
                            <span>
                                {{ $count }} votes
                                <span class="badge bg-primary rounded-pill">{{ $percentage }}%</span>
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endforeach
</div>
@endsection
