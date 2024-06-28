@extends('adminlte::page')

@section('title', '- Dashboard')

@section('plugins.Chartjs', true)
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fa fa-fw fa-digital-tachograph"></i> Dashboard</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            @if (Auth::user()->hasRole('Programador|Administrador'))
                <div class="row">
                    <div class="col-12 col-sm-6 col-md-4">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-dark elevation-1"><i class="fas fa-user-shield"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Programadores</span>
                                <span class="info-box-number">{{ $programmers }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-user-tie"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Administradores</span>
                                <span class="info-box-number">{{ $administrators }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-user-cog"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Usuários</span>
                                <span class="info-box-number">{{ $users }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="small-box bg-primary">
                            <div class="inner">
                                <h3>{{ $departments }}</h3>
                                <p>Setores</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-building"></i>
                            </div>
                            <a href="{{ route('admin.departments.index') }}" class="small-box-footer">Visualizar <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="small-box bg-yellow">
                            <div class="inner">
                                <h3>{{ $groups->count() }}</h3>
                                <p>Grupos de Materiais</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-layer-group"></i>
                            </div>
                            <a href="{{ route('admin.groups.index') }}" class="small-box-footer">Visualizar <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $materials->where('status', 'Ativo')->count() }}</h3>
                                <p>Materiais Ativos</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-box-open"></i>
                            </div>
                            <a href="{{ route('admin.materials.active') }}" class="small-box-footer">Visualizar <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="small-box bg-dark">
                            <div class="inner">
                                <h3>{{ $materials->where('status', 'Baixa')->count() }}</h3>
                                <p>Materiais em Baixa</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-box"></i>
                            </div>
                            <a href="{{ route('admin.materials.writeOff') }}" class="small-box-footer">Visualizar <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $materials->where('status', 'Ativo')->count() }}</h3>
                                <p>Materiais Ativos</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-box-open"></i>
                            </div>
                            <a href="{{ route('admin.materials.active') }}" class="small-box-footer">Visualizar <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="small-box bg-dark">
                            <div class="inner">
                                <h3>{{ $materials->where('status', 'Baixa')->count() }}</h3>
                                <p>Materiais em Baixa</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-box"></i>
                            </div>
                            <a href="{{ route('admin.materials.writeOff') }}" class="small-box-footer">Visualizar <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
            @endif

            <div class="row px-2">
                <div class="card col-12">
                    <div class="card-header">
                        Gráficos
                    </div>
                    <div class="card-body px-0 pb-0 d-flex flex-wrap justify-content-center">
                        <div class="col-12 col-md-6">
                            <div class="card">
                                <div class="card-header border-0">
                                    <p class="mb-0">Materiais por Grupo</p>
                                </div>
                                <div class="cardy-body py-2">
                                    <div class="chart-responsive">
                                        <div class="chartjs-size-monitor">
                                            <div class="chartjs-size-monitor-expand">
                                                <div class=""></div>
                                            </div>
                                            <div class="chartjs-size-monitor-shrink">
                                                <div class=""></div>
                                            </div>
                                        </div>
                                        <canvas id="material-by-group" style="display: block; width: 203px; height: 100px;"
                                            class="chartjs-render-monitor" width="203" height="100"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="card">
                                <div class="card-header border-0">
                                    <p class="mb-0">Valor por Grupo</p>
                                </div>
                                <div class="cardy-body py-2">
                                    <div class="chart-responsive">
                                        <div class="chartjs-size-monitor">
                                            <div class="chartjs-size-monitor-expand">
                                                <div class=""></div>
                                            </div>
                                            <div class="chartjs-size-monitor-shrink">
                                                <div class=""></div>
                                            </div>
                                        </div>
                                        <canvas id="value-by-group" style="display: block; width: 203px; height: 100px;"
                                            class="chartjs-render-monitor" width="203" height="100"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card">
                                <div class="card-header border-0">
                                    <p class="mb-0">Movimentação Anual</p>
                                </div>
                                <div class="cardy-body py-2">
                                    <div class="chart-responsive">
                                        <div class="chartjs-size-monitor">
                                            <div class="chartjs-size-monitor-expand">
                                                <div class=""></div>
                                            </div>
                                            <div class="chartjs-size-monitor-shrink">
                                                <div class=""></div>
                                            </div>
                                        </div>
                                        <canvas id="material-by-year" style="display: block; width: 203px; height: 100px;"
                                            class="chartjs-render-monitor" width="203" height="100"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            @if (Auth::user()->hasRole('Programador|Administrador'))
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex flex-wrap justify-content-between col-12 align-content-center">
                            <h3 class="card-title align-self-center">Acessos Diário</h3>
                        </div>
                    </div>

                    @php
                        $heads = [
                            ['label' => 'Hora', 'width' => 10],
                            'Página',
                            'IP',
                            'User-Agent',
                            'Plataforma',
                            'Navegador',
                            'Usuário',
                            'Método',
                        ];
                        $config = [
                            'ajax' => url('/admin'),
                            'columns' => [
                                ['data' => 'time', 'name' => 'time'],
                                ['data' => 'url', 'name' => 'url'],
                                ['data' => 'ip', 'name' => 'ip'],
                                ['data' => 'useragent', 'name' => 'useragent'],
                                ['data' => 'platform', 'name' => 'platform'],
                                ['data' => 'browser', 'name' => 'browser'],
                                ['data' => 'name', 'name' => 'name'],
                                ['data' => 'method', 'name' => 'method'],
                            ],
                            'language' => ['url' => asset('vendor/datatables/js/pt-BR.json')],
                            'order' => [0, 'desc'],
                            'destroy' => true,
                            'autoFill' => true,
                            'processing' => true,
                            'serverSide' => true,
                            'responsive' => true,
                            'lengthMenu' => [[10, 50, 100, 500, 1000, -1], [10, 50, 100, 500, 1000, 'Tudo']],
                            'dom' => '<"d-flex flex-wrap col-12 justify-content-between"Bf>rtip',
                            'buttons' => [
                                ['extend' => 'pageLength', 'className' => 'btn-default'],
                                [
                                    'extend' => 'copy',
                                    'className' => 'btn-default',
                                    'text' => '<i class="fas fa-fw fa-lg fa-copy text-secondary"></i>',
                                    'titleAttr' => 'Copiar',
                                    'exportOptions' => ['columns' => ':not([dt-no-export])'],
                                ],
                                [
                                    'extend' => 'print',
                                    'className' => 'btn-default',
                                    'text' => '<i class="fas fa-fw fa-lg fa-print text-info"></i>',
                                    'titleAttr' => 'Imprimir',
                                    'exportOptions' => ['columns' => ':not([dt-no-export])'],
                                ],
                            ],
                        ];
                    @endphp

                    <div class="card-body">
                        <x-adminlte-datatable id="table1" :heads="$heads" :heads="$heads" :config="$config" striped
                            hoverable beautify />
                    </div>
                </div>

                <div class="row px-0">

                    <div class="col-12">
                        <div class="card">
                            <div class="card-header border-0">
                                <div class="d-flex justify-content-between">
                                    <h3 class="card-title">Usuários Online: <span
                                            id="onlineusers">{{ $onlineUsers }}</span></h3>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex">
                                    <p class="d-flex flex-column">
                                        <span class="text-bold text-lg" id="accessdaily">{{ $access }}</span>
                                        <span>Acessos Diários</span>
                                    </p>
                                    <p class="ml-auto d-flex flex-column text-right">
                                        <span id="percentclass"
                                            class="{{ $percent > 0 ? 'text-success' : 'text-danger' }}">
                                            <i id="percenticon"
                                                class="fas {{ $percent > 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}  mr-1"></i><span
                                                id="percentvalue">{{ $percent }}</span>%
                                        </span>
                                        <span class="text-muted">em relação ao dia anterior</span>
                                    </p>
                                </div>

                                <div class="position-relative mb-4">
                                    <div class="chartjs-size-monitor">
                                        <div class="chartjs-size-monitor-expand">
                                            <div class=""></div>
                                        </div>
                                        <div class="chartjs-size-monitor-shrink">
                                            <div class=""></div>
                                        </div>
                                    </div>
                                    <canvas id="visitors-chart" style="display: block; width: 489px; height: 200px;"
                                        class="chartjs-render-monitor" width="489" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            @endif



        </div>
    </section>
@endsection

@section('custom_js')
    @if (Auth::user()->hasRole('Programador|Administrador'))
        <script>
            const ctx = document.getElementById('visitors-chart');
            if (ctx) {
                ctx.getContext('2d');
                const myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ({!! json_encode($chart->labels) !!}),
                        datasets: [{
                            label: 'Acessos por horário',
                            data: {!! json_encode($chart->dataset) !!},
                            borderWidth: 1,
                            borderColor: '#007bff',
                            backgroundColor: 'transparent'
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        },
                        legend: {
                            labels: {
                                boxWidth: 0,
                            }
                        },
                    },
                });

                let getData = function() {

                    $.ajax({
                        url: "{{ route('admin.home.chart') }}",
                        type: "GET",
                        success: function(data) {
                            myChart.data.labels = data.chart.labels;
                            myChart.data.datasets[0].data = data.chart.dataset;
                            myChart.update();
                            $("#onlineusers").text(data.onlineUsers);
                            $("#accessdaily").text(data.access);
                            $("#percentvalue").text(data.percent);
                            const percentclass = $("#percentclass");
                            const percenticon = $("#percenticon");
                            percentclass.removeClass('text-success');
                            percentclass.removeClass('text-danger');
                            percenticon.removeClass('fa-arrow-up');
                            percenticon.removeClass('fa-arrow-down');
                            if (parseInt(data.percent) > 0) {
                                percentclass.addClass('text-success');
                                percenticon.addClass('fa-arrow-up');
                            } else {
                                percentclass.addClass('text-danger');
                                percenticon.addClass('fa-arrow-down');
                            }
                        }
                    });
                };
                setInterval(getData, 10000);
            }
        </script>
    @endif
    <script>
        function generateGradientColors(colors, steps) {
            let gradientColorsRGB = [];
            let gradientColorsRGBA = [];
            let gradientColorsRGBAopacity = [];
            let totalColors = colors.length;

            for (let i = 0; i < totalColors - 1; i++) {
                let startColor = colors[i];
                let endColor = colors[i + 1];

                let startRGB = startColor.match(/\d+/g).map(Number);
                let endRGB = endColor.match(/\d+/g).map(Number);
                let segmentSteps = Math.ceil(steps / (totalColors - 1));

                let stepRGB = [
                    (endRGB[0] - startRGB[0]) / segmentSteps,
                    (endRGB[1] - startRGB[1]) / segmentSteps,
                    (endRGB[2] - startRGB[2]) / segmentSteps
                ];

                for (let j = 0; j < segmentSteps; j++) {
                    let r = Math.round(startRGB[0] + stepRGB[0] * j);
                    let g = Math.round(startRGB[1] + stepRGB[1] * j);
                    let b = Math.round(startRGB[2] + stepRGB[2] * j);
                    gradientColorsRGB.push(`rgb(${r}, ${g}, ${b})`);
                    gradientColorsRGBA.push(`rgba(${r}, ${g}, ${b},  0.75)`);
                    gradientColorsRGBAopacity.push(`rgba(${r}, ${g}, ${b},  0.25)`);
                }
            }

            gradientColorsRGB.push(colors[totalColors - 1]);
            gradientColorsRGBA.push(colors[totalColors - 1]);
            gradientColorsRGBAopacity.push(colors[totalColors - 1]);

            return [gradientColorsRGB.slice(0, steps), gradientColorsRGBA.slice(0, steps), gradientColorsRGBAopacity.slice(
                0, steps)];
        }

        const colorStops = [
            "rgb(255, 0, 0)",
            "rgb(255, 255, 0)",
            "rgb(0, 255, 0)",
            "rgb(0, 255, 255)",
            "rgb(0, 0, 255)",
            "rgb(255, 0, 255)"
        ];

        const gradient = generateGradientColors(colorStops, {{ $groups->count() }});

        const materialByGroup = document.getElementById('material-by-group');
        if (materialByGroup) {
            materialByGroup.getContext('2d');
            const materialByGroupChart = new Chart(materialByGroup, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($materialChart->labels) !!},
                    datasets: [{
                        label: 'Materiais por grupo',
                        data: {!! json_encode($materialChart->quantity) !!},
                        borderWidth: 1,
                        backgroundColor: gradient[1],
                        borderColor: gradient[0],
                    }]
                },
                options: {
                    responsive: true,
                    legend: {
                        position: 'left',
                        labels: {
                            fontSize: 10
                        }
                    },
                },
            });
        }

        const valueByGroup = document.getElementById('value-by-group');
        if (valueByGroup) {
            valueByGroup.getContext('2d');
            const valueByGroupChart = new Chart(valueByGroup, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($materialChart->labels) !!},
                    datasets: [{
                        label: 'valor por grupo',
                        data: {!! json_encode($materialChart->value) !!},
                        borderWidth: 1,
                        backgroundColor: gradient[1],
                        borderColor: gradient[0],
                    }]
                },
                options: {
                    responsive: true,
                    legend: {
                        position: 'left',
                        labels: {
                            fontSize: 10
                        }
                    },
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, data) {

                                return data.labels[tooltipItem.datasetIndex] + ': ' + (data.datasets[tooltipItem
                                    .datasetIndex].data[tooltipItem.index]).toLocaleString('pt-BR', {
                                    style: 'currency',
                                    currency: 'BRL'
                                });
                            }
                        }
                    }
                },
            });
        }

        const materialYear = document.getElementById('material-by-year');
        if (materialYear) {

            const labeslBar = {!! json_encode($materialChart->materials['active']['year']) !!};
            const gradientBar = generateGradientColors(colorStops, labeslBar.length);

            const materialYearChart = new Chart(materialYear, {
                type: 'bar',
                data: {
                    labels: labeslBar,
                    datasets: [{
                            label: 'Ativos',
                            data: {!! json_encode($materialChart->materials['active']['quantity']) !!},
                            backgroundColor: gradientBar[1],
                            borderColor: gradientBar[0],
                            borderWidth: 1
                        },
                        {
                            label: 'Baixas',
                            data: {!! json_encode($materialChart->materials['writeOff']['quantity']) !!},
                            backgroundColor: gradientBar[2],
                            borderColor: gradientBar[0],
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }],
                        xAxes: [{
                            barThickness: 50,
                            maxBarThickness: 50
                        }]
                    },
                    legend: {
                        labels: {
                            boxWidth: 10,
                        }
                    },
                },
            });
        }
    </script>
@endsection
