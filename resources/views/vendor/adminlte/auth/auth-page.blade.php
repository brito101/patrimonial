@extends('adminlte::master')

@php($dashboard_url = View::getSection('dashboard_url') ?? config('adminlte.dashboard_url', 'home'))

@if (config('adminlte.use_route_url', false))
    @php($dashboard_url = $dashboard_url ? route($dashboard_url) : '')
@else
    @php($dashboard_url = $dashboard_url ? url($dashboard_url) : '')
@endif

@section('adminlte_css')
    @stack('css')
    @yield('css')
@stop

@section('classes_body'){{ ($auth_type ?? 'login') . '-page' }}@stop

@section('body')
    <div class="{{ $auth_type ?? 'login' }}-box">

        {{-- Logo --}}
        <div class="{{ $auth_type ?? 'login' }}-logo">
            <a href="{{ $dashboard_url }}">
                <img src="{{ asset(config('adminlte.logo_img')) }}" height="50">
                {{ env('APP_NAME') }}
            </a>
        </div>

        {{-- Card Box --}}
        <div class="card {{ config('adminlte.classes_auth_card', 'card-outline card-primary') }}">

            {{-- Card Header --}}
            @hasSection('auth_header')
                <div class="card-header {{ config('adminlte.classes_auth_header', '') }}">
                    <h3 class="card-title float-none text-center">
                        @yield('auth_header')
                    </h3>
                </div>
            @endif

            {{-- Card Body --}}
            <div class="card-body {{ $auth_type ?? 'login' }}-card-body {{ config('adminlte.classes_auth_body', '') }}">
                @yield('auth_body')
            </div>

            {{-- Card Footer --}}
            @hasSection('auth_footer')
                <div class="card-footer {{ config('adminlte.classes_auth_footer', '') }}">
                    @yield('auth_footer')
                </div>
            @endif

        </div>

    </div>
    <!-- Histats.com  (div with counter) -->
    <div id="histats_counter" class="mt-2"></div>
@stop

@section('adminlte_js')
    @stack('js')
    @yield('js')
    <!-- Histats.com  START  (aync)-->
    <script type="text/javascript">
        var _Hasync = _Hasync || [];
        _Hasync.push(['Histats.start', '1,4864518,4,511,95,18,00000000']);
        _Hasync.push(['Histats.fasi', '1']);
        _Hasync.push(['Histats.track_hits', '']);
        (function() {
            var hs = document.createElement('script');
            hs.type = 'text/javascript';
            hs.async = true;
            hs.src = ('//s10.histats.com/js15_as.js');
            (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(hs);
        })();
    </script>

    <noscript><a href="/" target="_blank"><img src="//sstatic1.histats.com/0.gif?4864518&101" alt="free site stat"
                border="0"></a></noscript>
    <!-- Histats.com  END  -->
@stop
