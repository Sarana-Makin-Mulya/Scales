@extends('layouts.app')

@section('content')
    {{-- Page Header --}}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                {{-- Page Title --}}
                <div class="col-sm-6 d-none d-md-block">
                    <a class="text-white" href="#">
                        <h1>{{ __('Stok Opname') }}</h1>
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
                    <main-stockopname
                        url-stock-opname="{{ route('ajax.stock.get.stock.opname') }}"
                        url-stock-opname-store="{{ route('stock.opname.store') }}"
                        url-stock-opname-store-adjustment="{{ route('stock.opname.store.adjustment') }}"
                        url-stock-opname-store-daily="{{ route('stock.opname.store.daily') }}"
                        url-data-export-excel="{{ route('ajax.stock.get.stock.opname.export.excel') }}"
                        url-data-import-excel="{{ route('stock.opname.import.excel') }}"
                        page-name = "{{ $pageName }}"
                        filter-name = "{{ $filterName }}"
                    ></main-stockopname>
                </div>
            <div>
        </div>
    </section>
@endsection

