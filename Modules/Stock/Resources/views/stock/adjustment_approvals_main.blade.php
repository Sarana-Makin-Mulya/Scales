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
                    <stock-adjustment-approvals-main
                        url-data-adjustment-per-code="{{ route('ajax.stock.get.adjustment') }}"
                        url-data-adjustment-per-item="{{ route('ajax.stock.get.adjustment.per.item') }}"
                        url-update-multi-approvals="{{ route('stock.adjustment.approvals.multi.per.item.update') }}"
                        url-get-row-adjustment-per-item-pending = "{{ route('ajax.stock.approvals.pending.adjustment.per.item') }}"
                    ></stock-adjustment-approvals-main>
                </div>
            <div>
        </div>
    </section>
@endsection

