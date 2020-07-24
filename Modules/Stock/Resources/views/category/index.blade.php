@extends('layouts.app')

@section('content')
    {{-- Page Header --}}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                {{-- Page Title --}}
                <div class="col-sm-6 d-none d-md-block">
                    <a class="text-white" href="#">
                        <h1>{{ __('Kategori Barang') }}</h1>
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
                    <category-table-records
                        url-data="{{ route('ajax.stock.get.item.category') }}"
                        url-store="{{ route('stock.item.category.store') }}"
                        url-change-status="{{ route('stock.item.category.store') }}"
                        url-check-name-exist="{{ route('ajax.stock.check.item.category.name.exist') }}"
                        url-check-code-exist="{{ route('ajax.stock.check.item.category.code.exist') }}"
                        url-export-pdf="{{ route('stock.item.category.export.pdf') }}"
                    ></category-table-records>
                </div>
            <div>
        </div>
    </section>
@endsection

