@extends('layouts.app')

@section('content')
    {{-- Page Header --}}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                {{-- Page Title --}}
                <div class="col-sm-6 d-none d-md-block">
                    <a class="text-white" href="#">
                        <h1>{{ __('Stok Bermasalah') }}</h1>
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
                    <main-stock-warning
                        url-stock-buffer="{{ route('ajax.stock.goods.buffer') }}"
                        url-check-stock-quarantine="{{ route('ajax.stock.get.quarantine.check')  }}"
                        url-stock-quarantine="{{ route('ajax.stock.get.quarantine')  }}"
                        filter-name="{{ $filterName }}"
                        page-name="{{ $pageName }}"
                    ></main-stock-warning>
                </div>
            <div>
        </div>
    </section>
@endsection

