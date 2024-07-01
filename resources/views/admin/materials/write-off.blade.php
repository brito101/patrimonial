@extends('adminlte::page')

@section('title', '- Materiais')
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><img src="{{ asset('img/icons/box-archive-solid.svg') }}" style="width: 1.25em;height: 1.25em;">
                        Materiais em Baixa</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.materials.index') }}">Materiais por Grupo</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.materials.active') }}">Materiais Ativos</a></li>
                        <li class="breadcrumb-item active">Materiais em Baixa</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    @include('components.alert')

                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex flex-wrap justify-content-between col-12 align-content-center">
                                <h3 class="card-title align-self-center">Materiais Cadastrados</h3>
                                @can('Criar Materiais')
                                    <a href="{{ route('admin.materials.create') }}" title="Novo Material"
                                        class="btn btn-success"><i class="fas fa-fw fa-plus"></i>Novo Material</a>
                                @endcan
                            </div>
                        </div>

                        <div class="col-12 d-flex flex-wrap justify-content-end align-content-center py-2">
                            <h5 class="col-12 col-md-8 h6 text-muted text-center text-md-right align-self-center m-md-0">
                                Operações em lote</h5>
                            <div class="px-2 col-12 col-md-2 d-flex justify-content-center">
                                <form method="POST" action="{{ route('admin.materials.batch.active') }}" class="w-100">
                                    @csrf
                                    <input type="hidden" name="ids" value="" id="ids" class="ids">
                                    <button type="submit" id="change-status" class="change-status btn btn-warning w-100"
                                        data-confirm="Confirma a baixa desta seleção?" title="Alterar os materiais das linhas selecionadas para situação de ativo"><i class="fas fa-fw fa-sync"></i>
                                        Ativo</button>
                                </form>
                            </div>
                            <div class="px-2 col-12 col-md-2 d-flex justify-content-center">
                                <form method="POST" action="{{ route('admin.materials.batch.delete') }}" class="w-100">
                                    @csrf
                                    <input type="hidden" name="ids" value="" id="ids" class="ids">
                                    <button type="submit" id="batch-delete" class="btn btn-danger w-100"
                                        data-confirm="Confirma a exclusão desta seleção?" title="Excluir os materiais das linhas selecionadas"><i class="fas fa-fw fa-trash"></i>
                                        Exclusão</button>
                                </form>
                            </div>
                        </div>

                        @php
                            $heads = [
                                // ['label' => 'ID', 'width' => 10],
                                'SIADS',
                                'RM',
                                'Descrição',
                                'Grupo',
                                'Setor',
                                'Valor',
                                ['label' => 'Ações', 'no-export' => true, 'width' => 10],
                            ];
                            $config = [
                                'ajax' => url('/admin/materials/write-off'),
                                'columns' => [
                                    // ['data' => 'id', 'name' => 'id'],
                                    ['data' => 'secondary_code', 'name' => 'secondary_code'],
                                    ['data' => 'registration', 'name' => 'registration'],
                                    ['data' => 'description', 'name' => 'description'],
                                    ['data' => 'group_name', 'name' => 'group_name'],
                                    ['data' => 'department_name', 'name' => 'department_name'],
                                    ['data' => 'value', 'name' => 'value'],
                                    [
                                        'data' => 'action',
                                        'name' => 'action',
                                        'orderable' => false,
                                        'searchable' => false,
                                    ],
                                ],
                                'language' => ['url' => asset('vendor/datatables/js/pt-BR.json')],
                                'autoFill' => true,
                                'processing' => true,
                                'serverSide' => true,
                                'responsive' => true,
                                'pageLength' => 50,
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
                                        'footer' => true,
                                    ],
                                    [
                                        'extend' => 'print',
                                        'className' => 'btn-default',
                                        'text' => '<i class="fas fa-fw fa-lg fa-print text-info"></i>',
                                        'titleAttr' => 'Imprimir',
                                        'exportOptions' => ['columns' => ':not([dt-no-export])'],
                                        'footer' => true,
                                    ],
                                    // [
                                    //     'extend' => 'csv',
                                    //     'className' => 'btn-default',
                                    //     'text' => '<i class="fas fa-fw fa-lg fa-file-csv text-primary"></i>',
                                    //     'titleAttr' => 'Exportar para CSV',
                                    //     'exportOptions' => ['columns' => ':not([dt-no-export])'],
                                    //     'footer' => true,
                                    // ],
                                    [
                                        'extend' => 'excel',
                                        'className' => 'btn-default',
                                        'text' => '<i class="fas fa-fw fa-lg fa-file-excel text-success"></i>',
                                        'titleAttr' => 'Exportar para Excel',
                                        'exportOptions' => ['columns' => ':not([dt-no-export])'],
                                        'footer' => true,
                                    ],
                                    [
                                        'extend' => 'pdf',
                                        'className' => 'btn-default',
                                        'text' => '<i class="fas fa-fw fa-lg fa-file-pdf text-danger"></i>',
                                        'titleAttr' => 'Exportar para PDF',
                                        'exportOptions' => ['columns' => ':not([dt-no-export])'],
                                        'footer' => true,
                                    ],
                                ],
                            ];
                        @endphp

                        <div class="card-body">
                            <x-adminlte-datatable id="table1" :heads="$heads" :heads="$heads" :config="$config"
                                striped hoverable beautify withFooter="materials" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('custom_js')
    <script src="{{ asset('js/batch.js') }}"></script>
@endsection
