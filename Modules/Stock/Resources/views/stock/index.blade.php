@extends('layouts.app')

@section('content')
    {{-- Page Header --}}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                {{-- Page Title --}}
                <div class="col-sm-6 d-none d-md-block">
                    <a class="text-white" href="#">
                        <h1>{{ __('Stok Barang') }}</h1>
                    </a>
                </div>
                <div class="col-sm-6 d-none d-md-block text-right">
                    {{-- <button class="btn btn-primary">Tambah</button> --}}
                </div>
            </div>
        </div>
    </section>

    {{-- Content --}}
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <main-stock
                        url-stock-index="{{ route('ajax.stock.goods.index') }}"
                        url-stock-history="{{ route('ajax.stock.goods.history') }}"
                        url-stock-borrowed="{{ route('ajax.stock.goods.borrowed') }}"
                    ></main-stock>

                </div>
            <div>
        </div>
    </section>
@endsection

