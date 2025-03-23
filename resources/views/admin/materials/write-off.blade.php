@extends('adminlte::page')

@section('title', '- Materiais')
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)
@section('plugins.select2', true)
@section('plugins.BootstrapSelect', true)

@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    @if (Auth::user()->hasRole('Programador|Administrador'))
                        <h1><img src="{{ asset('img/icons/box-archive-solid.svg') }}" style="width: 1.25em;height: 1.25em;">
                            Materiais em Baixa{{ $departmentId ? ' do Setor: ' . $departmentName : '' }}</h1>
                    @else
                        <h1><img src="{{ asset('img/icons/box-archive-solid.svg') }}" style="width: 1.25em;height: 1.25em;">
                            Materiais em Baixa do setor: {{ $departmentName }}</h1>
                    @endif
                </div>

                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.materials.index') }}">Materiais por Grupo</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.materials.active') }}">Materiais Ativos</a>
                        </li>
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
                        <div class="d-flex flex-wrap px-0 px-lg-2">
                            <div class="col-12 col-lg-4 m-0 mt-2">
                                <x-adminlte-select2 name="sel2Basic" label="Seleção por Setor" igroup-size="md"
                                            data-placeholder="Selecione uma opção..." class="w-100">
                                        <option value="0" {{ $departmentId == null ? 'selected' : '' }}>Indiferente</option>
                                        @foreach ($departments as $item)
                                            <option value="{{ $item->id }}" {{ $departmentId == $item->id ? 'selected' : '' }}>{{ $item->name }}
                                                </option>
                                        @endforeach
                                </x-adminlte-select2>                                                
                            </div>

                            <div class="col-12 col-lg-8 d-flex flex-wrap justify-content-end align-content-center py-2">
                                <h5 class="col-12 h6 text-bold text-center text-md-left align-self-center">
                                Operações em lote</h5>
                                <div class="px-2 col-12 col-md-4 d-flex justify-content-center">
                                    <form method="POST" action="{{ route('admin.materials.batch.edit') }}" class="w-100">
                                        @csrf
                                        <input type="hidden" name="ids" value="" id="ids" class="ids">
                                        <button type="submit" id="batch-edit" class="change-status btn btn-primary w-100"
                                            data-confirm="Confirma a edição desta seleção?"
                                            title="Editar os materiais das linhas selecionadas"><i
                                                class="fas fa-fw fa-edit"></i>
                                            Edição</button>
                                    </form>
                                </div>
                            <div class="px-2 col-12 col-md-4 d-flex justify-content-center">
                                <form method="POST" action="{{ route('admin.materials.batch.active') }}" class="w-100">
                                    @csrf
                                    <input type="hidden" name="ids" value="" id="ids" class="ids">
                                    <button type="submit" id="change-status" class="change-status btn btn-warning w-100"
                                        data-confirm="Confirma a baixa desta seleção?"
                                        title="Alterar os materiais das linhas selecionadas para situação de ativo"><i
                                            class="fas fa-fw fa-sync"></i>
                                        Ativo</button>
                                </form>
                            </div>
                            <div class="px-2 col-12 col-md-4 d-flex justify-content-center">
                                <form method="POST" action="{{ route('admin.materials.batch.delete') }}" class="w-100">
                                    @csrf
                                    <input type="hidden" name="ids" value="" id="ids" class="ids">
                                    <button type="submit" id="batch-delete" class="btn btn-danger w-100"
                                        data-confirm="Confirma a exclusão desta seleção?"
                                        title="Excluir os materiais das linhas selecionadas"><i
                                            class="fas fa-fw fa-trash"></i>
                                        Exclusão</button>
                                </form>
                            </div>
                        </div>
                    </div>

                        @php
                            $heads = [
                                // ['label' => 'ID', 'width' => 10],
                                'SIADS',
                                'RM',
                                'Descrição',
                                ['label' => 'Grupo', 'no-export' => true],
                                ['label' => 'Setor', 'no-export' => true],
                                ['label' => 'Valor', 'no-export' => true],
                                ['label' => 'Valor Depreciado', 'no-export' => true],
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
                                    ['data' => 'depreciated_value', 'name' => 'depreciated_value'],
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
                                        'title' => $departmentName,
                                        'titleAttr' => 'Copiar',
                                        'exportOptions' => ['columns' => ':not([dt-no-export])'],
                                        'footer' => true,
                                    ],
                                    [
                                        'extend' => 'print',
                                        'className' => 'btn-default',
                                        'text' => '<i class="fas fa-fw fa-lg fa-print text-info"></i>',
                                        'title' => $departmentName,
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
                                        'title' => $departmentName,
                                        'titleAttr' => 'Exportar para Excel',
                                        'exportOptions' => ['columns' => ':not([dt-no-export])'],
                                        'footer' => true,
                                    ],
                                    [
                                        'extend' => 'pdf',
                                        'className' => 'btn-default',
                                        'text' => '<i class="fas fa-fw fa-lg fa-file-pdf text-danger"></i>',
                                        'title' => $departmentName,
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
    <script>
        $(document).ready(function() {
            $('#sel2Basic').on('change', function() {
                let department = $(this).val();
                
                if (department == 0) {
                    window.location.href = "{{ route('admin.materials.writeOff') }}";
                } else {
                    window.location.href = "{{ route('admin.materials.writeOff') }}" + "/" + department;
                }
            });
        });
    </script>
@endsection
