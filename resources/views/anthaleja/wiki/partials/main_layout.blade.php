@extends('layouts.main')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/wiki.css') }}">
@endsection
@section('content')
    <div class="wiki">
        <div class="row">
            @if (isset($infobox) && $infobox !== '')
                <div class="col-md-9">
                    @yield('article_content')
                </div>
                <div class="col-md-3">
                    {!! $infobox !!}
                </div>
            @else
                <div class="col-md-12">
                    @yield('article_content')
                </div>
            @endif
            @if (isset($infobox) && $infobox !== '')
                <div class="row">
                    <div class="col-md-12">
                        <!-- Continua il contenuto dell'articolo a full-width -->
                        <p>Qui il contenuto torna ad occupare tutte le 12 colonne, dopo la fine dell'infobox.</p>
                        <p>Puoi aggiungere il resto del contenuto qui.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
