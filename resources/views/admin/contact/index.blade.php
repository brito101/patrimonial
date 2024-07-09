@extends('adminlte::page')

@section('title', '- Contato')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fa fa-fw fa-phone-alt"></i> Contato</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Contato</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">

        <div class="card card-widget widget-user shadow">

            <div class="widget-user-header bg-primary">
                <h3 class="widget-user-username">{{ env('APP_NAME') }}</h3>
                <h5 class="widget-user-desc">{{ env('APP_DES') }}</h5>
            </div>
            <div class="widget-user-image">
                <img class="img-circle elevation-2" src="{{ asset('img/brand.png') }}" alt="{{ env('APP_NAME') }}" style="background-color: white;">
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-sm-6 border-right">
                        <div class="description-block">
                            <h5 class="description-header">patrimonioufrrj@gmail.com</h5>
                            <span class="description-text" style="text-transform: none;">E-mail</span>
                        </div>
                    </div>

                    <div class="col-sm-6 border-right">
                        <div class="description-block">
                            <h5 class="description-header">(21) 99359-4700</h5>
                            <span class="description-text" style="text-transform: none;">WhatsApp</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
