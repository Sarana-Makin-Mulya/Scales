@extends('layouts.app')

@section('content')
    {{-- Page Header --}}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                {{-- Page Title --}}
                <div class="col-sm-6 d-none d-md-block">
                    <a class="text-white" href="#">
                        <h1>{{ __('Barang') }}</h1>
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
                    <item
                        user-level="{{ getAuthGroup(Auth::user()->user_group_id) }}"
                        url-data="{{ route('ajax.stock.get.item') }}"
                        url-store="{{ route('stock.item.store') }}"
                        url-change-status="{{ route('stock.item.store') }}"
                        url-export-pdf="{{ route('stock.item.export.pdf')}}"

                        url-category-store="{{ route('stock.item.category.store') }}"
                        url-category-check-name-exist="{{ route('ajax.stock.check.item.category.name.exist') }}"
                        url-category-check-code-exist="{{ route('ajax.stock.check.item.category.code.exist') }}"

                        url-brand-store="{{ route('stock.item.brand.store') }}"
                        url-brand-check-name-exist="{{ route('ajax.stock.item.check.brand.name.exist') }}"

                        url-unit-store="{{ route('general.unit.store')}}"
                        url-unit-check-name-exist="{{ route('ajax.general.check.unit.name.exist')}}"
                        url-unit-check-symbol-exist="{{ route('ajax.general.check.unit.symbol.exist')}}"


                        {{-- :categories="{{ $categories }}"
                        :brands="{{ $brands }}"
                        :units="{{ $units }}" --}}
                    ></item>
                </div>
            <div>
        </div>
    </section>
@endsection

