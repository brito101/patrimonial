@extends('adminlte::page')
@section('plugins.select2', true)

@section('title', '- Edição de Material')

@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-fw fa-box"></i> Editar Material {{ $material->registration }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        @can('Listar Materiais')
                        <li class="breadcrumb-item"><a href="{{ route('admin.materials.index') }}">Materiais por Grupo</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.materials.active') }}">Materiais Ativos</a></li>
                        @endcan
                        <li class="breadcrumb-item active">Editar Material</li>
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

                        <form method="POST" action="{{ route('admin.materials.update', ['material' => $material->id]) }}">
                            @method('PUT')
                            <input type="hidden" name="id" value="{{ $material->id }}">
                            @csrf
                            <div class="card-body">

                                <div class="d-flex flex-wrap justify-content-start">
                                    <div class="col-12 col-md-3 form-group px-0 pr-md-2">
                                        <label for="registration">RM *</label>
                                        <input type="number" class="form-control" id="registration"
                                            placeholder="Registro de Material" name="registration"
                                            value="{{ old('registration') ?? $material->registration }}" required  max="18446744073709551615" min="1">
                                    </div>
                                    <div class="col-12 col-md-3 form-group px-0 px-md-2">
                                        <label for="secondary_code">SIADI</label>
                                        <input type="text" class="form-control" id="name"
                                            placeholder="Código do SIADI" name="secondary_code"
                                            value="{{ old('secondary_code') ?? $material->secondary_code }}">
                                    </div>
                                    <div class="col-12 col-md-3 form-group px-0 px-md-2">
                                        <label for="serial_number">Nº Série</label>
                                        <input type="text" class="form-control" id="name"
                                            placeholder="Nº de série do material" name="serial_number"
                                            value="{{ old('serial_number') ?? $material->serial_number }}">
                                    </div>

                                    <div class="col-12 col-md-3 form-group px-0 pl-md-2">
                                        <label for="value">Valor unitário *</label>
                                        <input type="text" class="form-control money_format_2" id="value"
                                            name="value" value="{{ old('value') ?? $material->value }}" required>
                                    </div>

                                    <div class="col-12 form-group px-0 mb-0">
                                        <x-adminlte-textarea label="Descrição" rows=5 name="description"
                                            placeholder="Texto descritivo..."
                                            enable-old-support="true">{{ old('description') ?? $material->description }}</x-adminlte-textarea>
                                    </div>

                                    <div class="col-12 col-md-4 form-group px-0 pr-md-2 mb-0">
                                        <label for="group_id">Grupo *</label>
                                        <x-adminlte-select2 name="group_id" id="group_id" required>
                                            <option value="">Nenhum</option>
                                            @foreach ($groups as $group)
                                                <option
                                                    {{ old('group_id') == $group->id ? 'selected' : ($material->group_id == $group->id ? 'selected' : '') }}
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
                                                <option
                                                    {{ old('department_id') == $department->id ? 'selected' : ($material->department_id == $department->id ? 'selected' : '') }}
                                                    value="{{ $department->id }}">
                                                    {{ $department->code ? $department->code . ' - ' : '' }}
                                                    {{ $department->name }}
                                                </option>
                                            @endforeach
                                        </x-adminlte-select2>
                                    </div>

                                    <div class="col-12 col-md-2 form-group px-0 px-md-2">
                                        <label for="year">Ano *</label>
                                        <input type="number" class="form-control" id="year" name="year"
                                            value="{{ old('year') ?? $material->year }}" required>
                                    </div>

                                    <div class="col-12 col-md-2 form-group px-0 px-md-2 mb-0">
                                        <label for="status">Status *</label>
                                        <x-adminlte-select2 name="status" id="status">
                                            <option
                                                {{ old('status') == 'Ativo' ? 'selected' : ($material->status == 'Ativo' ? 'selected' : '') }}
                                                value="Ativo">Ativo
                                            </option>
                                            <option
                                                {{ old('status') == 'Baixa' ? 'selected' : ($material->status == 'Baixa' ? 'selected' : '') }}
                                                value="Baixa">Baixa
                                            </option>
                                        </x-adminlte-select2>
                                    </div>

                                    <div class="col-12 form-group px-0 mb-0">
                                        <x-adminlte-textarea label="Observações" rows=5 name="observations"
                                            placeholder="Texto descritivo..."
                                            enable-old-support="true">{{ old('observations') ?? $material->observations }}</x-adminlte-textarea>
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
