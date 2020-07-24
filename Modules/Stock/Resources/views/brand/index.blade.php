@extends('layouts.app')

@section('content')
    {{-- Page Header --}}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                {{-- Page Title --}}
                <div class="col-sm-6 d-none d-md-block">
                    <a class="text-white" href="#">
                        <h1>{{ __('Merek Barang') }}</h1>
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
                    <brand-table-records
                        url-data="{{ route('ajax.stock.item.get.brand') }}"
                        url-store="{{ route('stock.item.brand.store') }}"
                        url-check-name-exist="{{ route('ajax.stock.item.check.brand.name.exist') }}"
                        url-export-pdf="{{ route('stock.item.brand.export.pdf') }}"
                    ></brand-table-records>
                </div>
            <div>
        </div>
    </section>
@endsection

