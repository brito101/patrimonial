@extends('adminlte::page')

@section('title', '- Cadastro de Usuário')
@section('plugins.select2', true)
@section('plugins.BootstrapSelect', true)

@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-fw fa-user"></i> Novo Usuário</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        @can('Listar Usuários')
                            <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Usuários</a></li>
                        @endcan
                        <li class="breadcrumb-item active">Novo Usuário</li>
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
                            <h3 class="card-title">Dados Cadastrais do Usuário</h3>
                        </div>

                        <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">

                                <div class="d-flex flex-wrap justify-content-between">
                                    <div class="col-12 col-md-6 form-group px-0 pr-md-2">
                                        <label for="name">Nome</label>
                                        <input type="text" class="form-control" id="name"
                                            placeholder="Nome Completo" name="name" value="{{ old('name') }}" required>
                                    </div>

                                    <div class="col-12 col-md-6 form-group px-0 pl-md-2">
                                        <label for="email">E-mail</label>
                                        <input type="email" class="form-control" id="email" placeholder="E-mail"
                                            name="email" value="{{ old('email') }}" required>
                                    </div>

                                    <div class="col-12 col-md-6 form-group px-0 pr-md-2">
                                        <label for="password">Senha</label>
                                        <input type="password" class="form-control" id="password" placeholder="Senha"
                                            minlength="8" name="password" value="{{ old('password') }}" required>
                                    </div>

                                    @can('Atribuir Perfis')
                                        <div class="col-12 col-md-6 form-group px-0 pl-md-2">
                                            <label for="role">Tipo de Usuário</label>
                                            <x-adminlte-select2 name="role">
                                                @foreach ($roles as $role)
                                                    <option {{ old('role') == $role->name ? 'selected' : '' }}
                                                        value="{{ $role->name }}">{{ $role->name }}</option>
                                                @endforeach
                                            </x-adminlte-select2>
                                        </div>

                                        <div class="col-12 form-group px-0">
                                            @php
                                                $configDept = [
                                                    'title' => 'Selecione múltiplas opções',
                                                    'showTick' => true,
                                                    'actionsBox' => true,
                                                    'liveSearch' => true,
                                                    'liveSearchPlaceholder' => 'Pesquisar...',
                                                ];
                                            @endphp

                                            <x-adminlte-select-bs id="departments" name="departments[]"
                                                label="Setor (selecione múltiplos)" igroup-size="md" :config="$configDept" multiple
                                                class="border rounded">
                                                @foreach ($departments as $department)
                                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                                @endforeach
                                            </x-adminlte-select-bs>
                                        </div>
                                    @endcan
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
    <script src="{{ asset('js/phone.js') }}"></script>
@endsection
