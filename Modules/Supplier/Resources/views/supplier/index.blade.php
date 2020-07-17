@extends('layouts.app')

@section('content')
    {{-- Page Header --}}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                {{-- Page Title --}}
                <div class="col-sm-6 d-none d-md-block">
                    <a class="text-white" href="#">
                        <h1>{{ __('Data Master Supplier') }}</h1>
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
                    <supplier-table-records
                        url-data="{{ route('ajax.get.supplier') }}"
                        url-store="{{ route('supplier.store') }}"
                        url-store-supplier-category="{{ route('supplier.category.store') }}"
                        url-supplier-category-check-name-exist="{{ route('ajax.stock.check.supplier.category.name.exist') }}"
                        url-supplier-category-check-code-exist="{{ route('ajax.stock.check.supplier.category.code.exist') }}"
                    ></supplier-table-records>
                </div>
            <div>
        </div>
    </section>
@endsection
