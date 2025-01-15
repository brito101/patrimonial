@extends('adminlte::page')
@section('plugins.select2', true)

@section('title', '- Cadastro de Material')

@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-fw fa-box"></i> Novo Material</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        @can('Listar Materiais')
                            <li class="breadcrumb-item"><a href="{{ route('admin.materials.index') }}">Materiais por Grupo</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.materials.active') }}">Materiais Ativos</a></li>
                        @endcan
                        <li class="breadcrumb-item active">Novo Material</li>
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
                            <h3 class="card-title">Dados Cadastrais do Material</h3>
                        </div>

                        <form method="POST" action="{{ route('admin.materials.store') }}">
                            @csrf
                            <div class="card-body">

                                <div class="d-flex flex-wrap justify-content-start">
                                    
                                    <div class="col-12 col-md-4 form-group px-0 pr-md-2">
                                        <label for="secondary_code">SIADS</label>
                                        <input type="number" class="form-control" id="name"
                                            placeholder="Código do SIADS" name="secondary_code"
                                            value="{{ old('secondary_code') }}" max="18446744073709551615" min="1">
                                    </div>
                                    <div class="col-12 col-md-4 form-group px-0 px-md-2">
                                        <label for="registration">RM<small> (em caso de mais de um item será o valor
                                                inicial)</small></label>
                                        <input type="number" class="form-control" id="registration"
                                            placeholder="Registro de Material" name="registration"
                                            value="{{ old('registration') }}" max="18446744073709551615" min="1">
                                    </div>

                                    <div class="col-12 col-md-3 form-group px-0 px-md-2">
                                        <label for="value">Valor unitário *</label>
                                        <input type="text" class="form-control money_format_2" id="value"
                                            name="value" value="{{ old('value') }}" required>
                                    </div>

                                    <div class="col-12 col-md-1 form-group px-0 pl-md-2">
                                        <label for="quantity">Qtd</label>
                                        <input type="number" class="form-control" id="quantity" name="quantity"
                                            value="{{ old('quantity') }}">
                                    </div>

                                    <div class="col-12 form-group px-0 mb-0">
                                        <x-adminlte-textarea label="Descrição" rows=5 name="description"
                                            placeholder="Texto descritivo..." enable-old-support="true" />
                                    </div>

                                    <div class="col-12 col-md-4 form-group px-0 pr-md-2 mb-0">
                                        <label for="group_id">Grupo *</label>
                                        <x-adminlte-select2 name="group_id" id="group_id" required>
                                            @foreach ($groups as $group)
                                                <option {{ old('group_id') == $group->id ? 'selected' : '' }}
                                                    value="{{ $group->id }}">
                                                    {{ $group->code ? $group->code . ' - ' : '' }} {{ $group->name }}
                                                </option>
                                            @endforeach
                                        </x-adminlte-select2>
                                    </div>

                                    <div class="col-12 col-md-4 form-group px-0 px-md-2 mb-0">
                                        <label for="department_id">Setor *</label>
                                        <x-adminlte-select2 name="department_id" id="department_id">
                                            @foreach ($departments as $department)
                                                <option {{ old('department_id') == $department->id ? 'selected' : '' }}
                                                    value="{{ $department->id }}">
                                                    {{ $department->code ? $department->code . ' - ' : '' }}
                                                    {{ $department->name }}
                                                </option>
                                            @endforeach
                                        </x-adminlte-select2>
                                    </div>


                                    <div class="col-12 col-md-2 form-group px-0 px-md-2">
                                        <label for="year">Ano</label>
                                        <input type="number" class="form-control" id="year" name="year"
                                            value="{{ old('year') ?? date('Y') }}">
                                    </div>

                                    <div class="col-12 col-md-2 form-group px-0 px-md-2 mb-0">
                                        <label for="status">Status *</label>
                                        <x-adminlte-select2 name="status" id="status">
                                            <option {{ old('status') == 'Ativo' ? 'selected' : '' }} value="Ativo">Ativo
                                            </option>
                                            <option {{ old('status') == 'Baixa' ? 'selected' : '' }} value="Baixa">Baixa
                                            </option>
                                        </x-adminlte-select2>
                                    </div>

                                    <div class="col-12 form-group px-0 mb-0">
                                        <x-adminlte-textarea label="Observações" rows=5 name="observations"
                                            placeholder="Texto descritivo..." enable-old-support="true" />
                                    </div>

                                </div>

                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Enviar</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('custom_js')
    <script src="{{ asset('vendor/jquery/jquery.inputmask.bundle.min.js') }}"></script>
    <script src="{{ asset('js/money.js') }}"></script>
@endsection
