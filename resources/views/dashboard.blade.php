@extends('master.master')

@section('content')
    <div class="page-content">

        <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
            <div>
                <h4 class="mb-3 mb-md-0">Welcome {{ Auth::user()->name }}</h4>
            </div>

        </div>

        <div class="row">
            <div class="col-12 col-xl-12 stretch-card">
                <div class="row flex-grow-1">
                    <div class="col-md-4 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-baseline">
                                    <h6 class="card-title mb-0">Total Surveys</h6>

                                </div>
                                <div class="row">
                                    <div class="col-6 col-md-12 col-xl-5">

                                    </div>
                                    <div class="col-6 col-md-12 col-xl-7">
                                        <div id="customersChart" class="mt-md-3 mt-xl-0"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-baseline">
                                    <h6 class="card-title mb-0">Total Survey Submit</h6>

                                </div>
                                <div class="row">
                                    <div class="col-6 col-md-12 col-xl-5">

                                    </div>
                                    <div class="col-6 col-md-12 col-xl-7">
                                        <div id="ordersChart" class="mt-md-3 mt-xl-0"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-baseline">
                                    <h6 class="card-title mb-0">Total Visitors</h6>

                                </div>
                                <div class="row">
                                    <div class="col-6 col-md-12 col-xl-5">
                                        <h3 class="mb-2">89.87%</h3>
                                        <div class="d-flex align-items-baseline">
                                            <p class="text-success">
                                                <span>+2.8%</span>
                                                <i data-feather="arrow-up" class="icon-sm mb-1"></i>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-12 col-xl-7">
                                        <div id="growthChart" class="mt-md-3 mt-xl-0"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- row -->



        <div class="row">
            <div class="col-lg-6 col-xl-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Age Chart</h6>
                        <div id="ageChart"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-xl-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Gender chart</h6>
                        <div id="GenderChart"></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-xl-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header font-semibold">Occupation Chart</div>
                    <div class="card-body">
                        <div id="occupationChart"></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-xl-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header font-semibold">Marital Status Chart</div>
                    <div class="card-body">
                        <div id="maritalStatusChart"></div>
                    </div>
                </div>
            </div>


        </div> <!-- row -->

    </div>
@endsection
{{-- 
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
                        name: 'Count',
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
@endsection --}}
