@extends('adminlte::page')

@section('title', '- Materiais')
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-fw fa-box"></i> Materiais</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Materiais</li>
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

                        @php
                            $heads = [['label' => 'ID', 'width' => 10], 'Grupo', 'Descrição', 'Valor', 'Quantidade'];
                            $config = [
                                'ajax' => url('/admin/materials'),
                                'columns' => [
                                    ['data' => 'id', 'name' => 'id'],
                                    ['data' => 'code', 'name' => 'code'],
                                    ['data' => 'name', 'name' => 'name'],
                                    ['data' => 'value', 'name' => 'value'],
                                    ['data' => 'quantity', 'name' => 'quantity'],
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
                                ],
                            ];
                        @endphp

                        <div class="card-body">
                            <x-adminlte-datatable id="table1" :heads="$heads" :heads="$heads" :config="$config"
                                striped hoverable beautify withFooter="groups" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

