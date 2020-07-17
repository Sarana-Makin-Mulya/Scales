@extends('layouts.app')

@section('content')
    {{-- Page Header --}}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                {{-- Page Title --}}
                <div class="col-sm-6 d-none d-md-block">
                    <a class="text-white" href="#">
                        <h1>{{ __('Penimbangan') }}</h1>
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
                    <weighing-main
                        url-data="{{ route('ajax.wh.get.weighing') }}"
                        url-store="{{ route('wh.weighing.store') }}"
                        url-supplier-options="{{ route('ajax.get.supplier.options') }}"
                        url-employee-options="{{ route('hr.ajax.employee.options') }}"

                        url-weighing-category-data="{{ route('ajax.wh.get.weighing.category') }}"
                        url-weighing-category-store="{{ route('wh.weighing.category.store') }}"
                        url-weighing-category-check-name-exist="{{ route('ajax.stock.check.weighing.category.name.exist') }}"

                        url-weighing-item-data="{{ route('ajax.wh.get.weighing.item') }}"
                        url-weighing-item-store="{{ route('wh.weighing.item.store') }}"
                        url-weighing-item-check-name-exist="{{ route('ajax.stock.check.weighing.item.name.exist') }}"
                    ></weighing-main>
                </div>
            <div>
        </div>
    </section>
@endsection

