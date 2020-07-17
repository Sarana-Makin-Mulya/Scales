@extends('layouts.app')

@section('content')
    {{-- Page Header --}}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                {{-- Page Title --}}
                <div class="col-sm-6 d-none d-md-block">
                    <a class="text-white" href="#">
                        <h1>{{ __('Kategori Supplier') }}</h1>
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
                    <supplier-category-table-records
                        url-data="{{ route('ajax.get.supplier.category') }}"
                        url-store="{{ route('supplier.category.store') }}"
                        url-check-name-exist="{{ route('ajax.stock.check.supplier.category.name.exist') }}"
                        url-check-code-exist="{{ route('ajax.stock.check.supplier.category.code.exist') }}"
                        {{-- url-export-pdf="{{ route('stock.item.category.export.pdf') }}"
                        url-import-Excel="{{ route('stock.item.category.import.excel') }}" --}}
                    ></supplier-category-table-records>
                </div>
            <div>
        </div>
    </section>
@endsection

