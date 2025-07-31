@extends('adminlte::page')

@if (Auth::user()->hasRole('Programador|Administrador'))
    @section('title', '- Dashboard')
@else
    @section('title', '- ' . join(' e ', array_filter(array_merge([join(', ', array_slice(Auth::user()->departments->pluck('name')->toArray(), 0, -1))], array_slice(Auth::user()->departments->pluck('name')->toArray(), -1)), 'strlen')))
@endif

@section('plugins.Chartjs', true)
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                @if (Auth::user()->hasRole('Programador|Administrador'))
                    <div class="col-sm-6">
                        <h1><i class="fa fa-fw fa-digital-tachograph"></i> Dashboard</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div>
                @else
                    <div class="col-sm-12">
                        <h1>
                            <i class="fa fa-fw fa-digital-tachograph"></i>
                            {{ join(' e ', array_filter(array_merge([join(', ', array_slice(Auth::user()->departments->pluck('name')->toArray(), 0, -1))], array_slice(Auth::user()->departments->pluck('name')->toArray(), -1)), 'strlen')) }}
                        </h1>
                    </div>
                @endif
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


                <div class="row px-2">
                    <div class="card col-12">
                        <div class="card-header">
                            Gráficos
                        </div>
                        <div class="card-body px-0 pb-0 d-flex flex-wrap justify-content-center">                        

                            @if (Auth::user()->hasRole('Programador|Administrador'))
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
                                                <canvas id="material-by-year"
                                                    style="display: block; width: 203px; height: 100px;"
                                                    class="chartjs-render-monitor" width="203"
                                                    height="100"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            @else
                @php
                    $dptName = join(' e ', array_filter(array_merge([join(', ', array_slice(Auth::user()->departments->pluck('name')->toArray(), 0, -1))], array_slice(Auth::user()->departments->pluck('name')->toArray(), -1)), 'strlen'));
                    $heads = ['SIADS', 'RM', 'Descrição', 'Setor','Valor', 'Valor Depreciado'];
                    $config = [
                        'ajax' => url('/admin/materials/active'),
                        'columns' => [
                            ['data' => 'secondary_code', 'name' => 'secondary_code'],
                            ['data' => 'registration', 'name' => 'registration'],
                            ['data' => 'description', 'name' => 'description'],
                            ['data' => 'department_name', 'name' => 'department_name'],
                            ['data' => 'value', 'name' => 'value'],
                            ['data' => 'depreciated_value', 'name' => 'depreciated_value'],
                        ],
                        'language' => ['url' => asset('vendor/datatables/js/pt-BR.json')],
                        'autoFill' => true,
                        'processing' => true,
                        'serverSide' => true,
                        'responsive' => true,
                        'pageLength' => -1,
                        'paging' => false,
                        'dom' => '<"d-flex flex-wrap col-12 justify-content-between"Bf>rtip',
                        'buttons' => [
                            // ['extend' => 'pageLength', 'className' => 'btn-default'],
                            [
                                'extend' => 'copy',
                                'className' => 'btn-default',
                                'text' => '<i class="fas fa-fw fa-lg fa-copy text-secondary"></i>',
                                'titleAttr' => 'Copiar',
                                'exportOptions' => ['columns' => ':not([dt-no-export])'],
                                'footer' => true,
                                'title' => $dptName,
                            ],
                            [
                                'extend' => 'print',
                                'className' => 'btn-default',
                                'text' => '<i class="fas fa-fw fa-lg fa-print text-info"></i>',
                                'titleAttr' => 'Imprimir',
                                'exportOptions' => ['columns' => ':not([dt-no-export])'],
                                'footer' => true,
                                'title' => $dptName,
                            ],
                            [
                                'extend' => 'csv',
                                'className' => 'btn-default',
                                'text' => '<i class="fas fa-fw fa-lg fa-file-csv text-primary"></i>',
                                'titleAttr' => 'Exportar para CSV',
                                'exportOptions' => ['columns' => ':not([dt-no-export])'],
                                'footer' => true,
                                'title' => $dptName,
                            ],
                            [
                                'extend' => 'excel',
                                'className' => 'btn-default',
                                'text' => '<i class="fas fa-fw fa-lg fa-file-excel text-success"></i>',
                                'titleAttr' => 'Exportar para Excel',
                                'exportOptions' => ['columns' => ':not([dt-no-export])'],
                                'footer' => true,
                                'title' => $dptName,
                            ],
                            [
                                'extend' => 'pdf',
                                'className' => 'btn-default',
                                'text' => '<i class="fas fa-fw fa-lg fa-file-pdf text-danger"></i>',
                                'titleAttr' => 'Exportar para PDF',
                                'exportOptions' => ['columns' => ':not([dt-no-export])'],
                                'footer' => true,
                                'title' => $dptName,
                            ],
                        ],
                    ];
                @endphp

                <div class="card">
                    <div class="card-header">
                        <div class="d-flex flex-wrap justify-content-between col-12 align-content-center">
                            <h3 class="card-title align-self-center">Materiais Cadastrados</h3>
                        </div>
                    </div>

                    <div class="card-body">
                        <x-adminlte-datatable id="table1" :heads="$heads" :heads="$heads" :config="$config" striped
                            hoverable beautify withFooter="materials_user" />
                    </div>
                </div>
            @endif

        </div>
    </section>
@endsection

@section('custom_js')
    
    <script>
        
        const materialYear = document.getElementById('material-by-year');
        if (materialYear) {

            const labeslBar = {!! json_encode($materialChart->materials['active']['year']) !!};

            const materialYearChart = new Chart(materialYear, {
                type: 'bar',
                data: {
                    labels: labeslBar,
                    datasets: [{
                            label: 'Ativos',
                            data: {!! json_encode($materialChart->materials['active']['quantity']) !!},
                            backgroundColor: "#0a0a0a",
                            borderWidth: 1
                        },
                        {
                            label: 'Baixas',
                            data: {!! json_encode($materialChart->materials['writeOff']['quantity']) !!},
                            backgroundColor: "#555",
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
                            barThickness: 15,
                            maxBarThickness: 15
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
