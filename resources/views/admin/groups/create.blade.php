@extends('adminlte::page')

@section('title', '- Cadastro de Grupo')

@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-fw fa-layer-group"></i> Novo Grupo</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        @can('Listar Grupos')
                            <li class="breadcrumb-item"><a href="{{ route('admin.groups.index') }}">Grupos</a></li>
                        @endcan
                        <li class="breadcrumb-item active">Novo Grupo</li>
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
                            <h3 class="card-title">Dados Cadastrais do Grupo</h3>
                        </div>

                        <form method="POST" action="{{ route('admin.groups.store') }}">
                            @csrf
                            <div class="card-body">

                                <div class="d-flex flex-wrap justify-content-between">
                                    <div class="col-12 col-md-4 form-group px-0 pr-md-2">
                                        <label for="code">Código</label>
                                        <input type="text" class="form-control" id="code"
                                            placeholder="Código de referência" name="code" value="{{ old('code') }}">
                                    </div>
                                    <div class="col-12 col-md-8 form-group px-0 pl-md-2">
                                        <label for="name">Nome *</label>
                                        <input type="text" class="form-control" id="name"
                                            placeholder="Nome do Grupo" name="name" value="{{ old('name') }}" required>
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
