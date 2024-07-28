@extends('adminlte::page')

@section('title', '- Materiais')
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)
@section('plugins.BsCustomFileInput', true)

@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">

                    @if (Auth::user()->hasRole('Programador|Administrador'))
                        <h1><i class="fas fa-fw fa-box"></i> Materiais</h1>
                    @else
                        <h1><i class="fas fa-fw fa-box"></i> Materiais do Setor {{ Auth::user()->department->name }}</h1>
                    @endif
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.materials.active') }}">Materiais Ativos</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.materials.writeOff') }}">Materiais em Baixa</a>
                        </li>
                        <li class="breadcrumb-item active">Materiais por Grupo</li>
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

                    <div class="card card-solid">
                        <div class="card-header d-flex flex-wrap">
                            <div class="col-12 col-md-6 align-self-center"><i class="fas fa-fw fa-upload"></i> Importação de
                                Planilha</div>

                            <div class="col-12 col-md-6 d-flex justify-content-end">
                                <a class="btn btn-secondary" href="{{ asset('worksheets/materials.ods') }}" download><i
                                        class="fas fa-fw fa-download"></i> Planilha padrão para
                                    cadastro de materiais</a>
                            </div>
                        </div>

                        <form action="{{ route('admin.materials.import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body pb-0">
                                <x-adminlte-input-file name="file" label="Arquivo" placeholder="Selecione o arquivo..."
                                    legend="Selecionar" />
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-primary">Importar</button>
                            </div>
                        </form>
                    </div>

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
                            $heads = [
                                // ['label' => 'ID', 'width' => 10], 
                                'Grupo', 'Descrição', 'Valor', 'Valor Depreciado', 'Quantidade'];
                            $config = [
                                'ajax' => url('/admin/materials'),
                                'columns' => [
                                    // ['data' => 'id', 'name' => 'id'],
                                    ['data' => 'code', 'name' => 'code'],
                                    ['data' => 'name', 'name' => 'name'],
                                    ['data' => 'value', 'name' => 'value'],
                                    ['data' => 'depreciated_value', 'name' => 'depreciated_value'],
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
