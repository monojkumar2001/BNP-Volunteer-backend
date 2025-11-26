@extends('master.master')

@php
    use Illuminate\Support\Str;
@endphp

@section('content')
    <div class="page-content">
        <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
            <div>
                <h4 class="mb-1">Welcome back, {{ Auth::user()->name }}</h4>
                <p class="text-muted mb-0">Here is the real-time snapshot of the entire platform.</p>
            </div>
            <div class="d-flex align-items-center gap-2 mt-3 mt-md-0">
                <span class="badge bg-primary-subtle text-primary fw-semibold px-3 py-2">
                    Updated {{ now()->format('d M, h:i A') }}
                </span>
                <a href="{{ route('admin.contact_us.index') }}" class="btn btn-primary btn-icon-text">
                    <i data-feather="mail" class="btn-icon-prepend"></i>
                    View Contacts
                </a>
            </div>
        </div>

        {{-- Top Stats --}}
        <div class="row">
            @php
                $cards = [
                    [
                        'label' => 'Total Users',
                        'value' => number_format($totalUsers),
                        'sub' => $newUsersCount . ' new today',
                        'subClass' => 'text-success',
                        'icon' => 'users',
                        'bg' => 'bg-primary-subtle text-primary',
                    ],
                    [
                        'label' => 'News & Events',
                        'value' => number_format($totalNews + $totalEvents),
                        'sub' => $publishedNews . ' news / ' . $upcomingEvents . ' upcoming events',
                        'subClass' => 'text-muted',
                        'icon' => 'calendar',
                        'bg' => 'bg-warning-subtle text-warning',
                    ],
                    [
                        'label' => 'Central BNP',
                        'value' => number_format($totalCentralBnp),
                        'sub' => $publishedCentralBnp . ' published',
                        'subClass' => 'text-success',
                        'icon' => 'file-text',
                        'bg' => 'bg-info-subtle text-info',
                    ],
                    [
                        'label' => 'Volunteers',
                        'value' => number_format($totalVolunteers),
                        'sub' => $pendingVolunteers . ' pending',
                        'subClass' => 'text-warning',
                        'icon' => 'heart',
                        'bg' => 'bg-secondary-subtle text-secondary',
                    ],
                    [
                        'label' => 'Opinions & Complaints',
                        'value' => number_format($totalOpinions),
                        'sub' => $unreadOpinions . ' unread',
                        'subClass' => $unreadOpinions ? 'text-danger' : 'text-success',
                        'icon' => 'message-square',
                        'bg' => 'bg-danger-subtle text-danger',
                    ],
                    [
                        'label' => 'Contact Messages',
                        'value' => number_format($totalContacts),
                        'sub' => $unreadContacts . ' unread',
                        'subClass' => $unreadContacts ? 'text-danger' : 'text-success',
                        'icon' => 'mail',
                        'bg' => 'bg-primary-subtle text-primary',
                    ],
                    [
                        'label' => 'Active Items',
                        'value' => number_format($upcomingEvents + $publishedNews + $publishedCentralBnp),
                        'sub' => 'Active events, news & central bnp',
                        'subClass' => 'text-muted',
                        'icon' => 'zap',
                        'bg' => 'bg-success-subtle text-success',
                    ],
                ];
            @endphp

            @foreach ($cards as $card)
                <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted">{{ $card['label'] }}</h6>
                                    <h3 class="mt-2 mb-0">{{ $card['value'] }}</h3>
                                    <small class="{{ $card['subClass'] }}">{{ $card['sub'] }}</small>
                                </div>
                                <div class="icon-md rounded {{ $card['bg'] }} d-flex align-items-center justify-content-center">
                                    <i data-feather="{{ $card['icon'] }}"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Charts --}}
        <div class="row">
            <div class="col-lg-6 col-xl-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Age Distribution</h6>
                        <div id="ageChart"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-xl-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Gender Distribution</h6>
                        <div id="GenderChart"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-xl-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header fw-semibold">Top Occupations</div>
                    <div class="card-body">
                        <div id="occupationChart"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-xl-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header fw-semibold">Marital Status</div>
                    <div class="card-body">
                        <div id="maritalStatusChart"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Latest data tables --}}
        <div class="row">
            <div class="col-lg-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="card-title mb-0">Latest Contact Messages</h6>
                            <a href="{{ route('admin.contact_us.index') }}" class="text-primary small">View all</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Subject</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($latestContacts as $contact)
                                        <tr>
                                            <td>{{ $contact->name ?? 'N/A' }}</td>
                                            <td>{{ Str::limit($contact->subject ?? 'N/A', 30) }}</td>
                                            <td>
                                                <span class="badge px-2 {{ $contact->status ? 'bg-success' : 'bg-warning text-dark' }}">
                                                    {{ $contact->status ? 'Read' : 'Unread' }}
                                                </span>
                                            </td>
                                            <td>{{ $contact->created_at->format('d M, h:i A') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">No contact messages yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="card-title mb-0">Latest Opinions & Complaints</h6>
                            <a href="{{ route('admin.opinion.index') }}" class="text-primary small">View all</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($latestOpinions as $opinion)
                                        <tr>
                                            <td>{{ $opinion->name ?? 'Anonymous' }}</td>
                                            <td>{{ $opinion->category ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge px-2 {{ $opinion->status ? 'bg-success' : 'bg-warning text-dark' }}">
                                                    {{ $opinion->status ? 'Read' : 'Unread' }}
                                                </span>
                                            </td>
                                            <td>{{ $opinion->created_at->format('d M, h:i A') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">No opinions yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="card-title mb-0">Newest Volunteers</h6>
                            <a href="{{ route('admin.volunteer.index') }}" class="text-primary small">View all</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($latestVolunteers as $volunteer)
                                        <tr>
                                            <td>{{ $volunteer->name }}</td>
                                            <td>{{ $volunteer->phone }}</td>
                                            <td>
                                                <span class="badge px-2 {{ $volunteer->status ? 'bg-success' : 'bg-warning text-dark' }}">
                                                    {{ $volunteer->status ? 'Approved' : 'Pending' }}
                                                </span>
                                            </td>
                                            <td>{{ $volunteer->created_at->format('d M, h:i A') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">No volunteers yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection

@section('js')
    <script>
        const ageData = {!! json_encode($ageData) !!};
        const genderData = {!! json_encode($genderData) !!};
        const occupationData = {!! json_encode($occupationData) !!};
        const maritalStatusData = {!! json_encode($maritalStatusData) !!};
    </script>

    <script>
        $(function() {
            'use strict';

            var colors = {
                primary: "#6571ff",
                secondary: "#7987a1",
                success: "#05a34a",
                info: "#66d1d1",
                warning: "#fbbc06",
                danger: "#ff3366",
                light: "#e9ecef",
                dark: "#060c17",
                muted: "#7987a1",
                gridBorder: "rgba(77, 138, 240, .15)",
                bodyColor: "#b8c3d9",
                cardBg: "#0c1427"
            }

            var fontFamily = "'Roboto', Helvetica, sans-serif"

            // Age Chart (Donut)
            if ($('#ageChart').length) {
                const labels = Object.keys(ageData);
                const series = Object.values(ageData);

                var options = {
                    chart: {
                        height: 300,
                        type: "donut",
                        foreColor: colors.bodyColor,
                        background: colors.cardBg,
                        toolbar: {
                            show: false
                        },
                    },
                    theme: {
                        mode: 'dark'
                    },
                    tooltip: {
                        theme: 'dark'
                    },
                    stroke: {
                        colors: ['rgba(0,0,0,0)']
                    },
                    labels: labels,
                    colors: [colors.primary, colors.warning, colors.danger, colors.info, colors.success],
                    legend: {
                        show: true,
                        position: "top",
                        horizontalAlign: 'center',
                        fontFamily: fontFamily,
                    },
                    dataLabels: {
                        enabled: false
                    },
                    series: series
                };

                var chart = new ApexCharts(document.querySelector("#ageChart"), options);
                chart.render();
            }

            // Gender Chart (Pie)
            if ($('#GenderChart').length) {
                const genderLabels = Object.keys(genderData);
                const genderSeries = Object.values(genderData);

                var options = {
                    chart: {
                        height: 300,
                        type: "pie",
                        foreColor: colors.bodyColor,
                        background: colors.cardBg,
                        toolbar: {
                            show: false
                        },
                    },
                    theme: {
                        mode: 'dark'
                    },
                    tooltip: {
                        theme: 'dark'
                    },
                    labels: genderLabels,
                    colors: [colors.primary, colors.warning, colors.danger, colors.info],
                    legend: {
                        show: true,
                        position: "top",
                        horizontalAlign: 'center',
                        fontFamily: fontFamily,
                    },
                    stroke: {
                        colors: ['rgba(0,0,0,0)']
                    },
                    dataLabels: {
                        enabled: false
                    },
                    series: genderSeries
                };

                var chart = new ApexCharts(document.querySelector("#GenderChart"), options);
                chart.render();
            }

            // Occupation Chart (Bar)
            if ($('#occupationChart').length) {
                const labels = Object.keys(occupationData);
                const series = Object.values(occupationData);

                var options = {
                    chart: {
                        type: 'bar',
                        height: 300,
                        foreColor: colors.bodyColor,
                        background: colors.cardBg,
                        toolbar: {
                            show: false
                        }
                    },
                    theme: {
                        mode: 'dark'
                    },
                    tooltip: {
                        theme: 'dark'
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '50%',
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        show: true,
                        width: 2,
                        colors: ['transparent']
                    },
                    series: [{
                        name: 'Respondents',
                        data: series
                    }],
                    xaxis: {
                        categories: labels
                    },
                    fill: {
                        opacity: 1
                    },
                    colors: [colors.primary, colors.success, colors.warning, colors.danger]
                };

                var chart = new ApexCharts(document.querySelector("#occupationChart"), options);
                chart.render();
            }

            // Marital Status Chart (Pie)
            if ($('#maritalStatusChart').length) {
                const labels = Object.keys(maritalStatusData);
                const series = Object.values(maritalStatusData);

                var options = {
                    chart: {
                        height: 300,
                        type: 'pie',
                        foreColor: colors.bodyColor,
                        background: colors.cardBg,
                        toolbar: {
                            show: false
                        }
                    },
                    theme: {
                        mode: 'dark'
                    },
                    tooltip: {
                        theme: 'dark'
                    },
                    labels: labels,
                    series: series,
                    stroke: {
                        colors: ['rgba(0,0,0,0)']
                    },
                    legend: {
                        position: "top",
                        horizontalAlign: 'center',
                        fontFamily: fontFamily,
                    },
                    colors: [colors.primary, colors.warning, colors.danger, colors.info]
                };

                var chart = new ApexCharts(document.querySelector("#maritalStatusChart"), options);
                chart.render();
            }
        });
    </script>
@endsection

