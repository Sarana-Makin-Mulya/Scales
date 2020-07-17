@extends('layouts.app')

@section('content')

    {{-- Page Header --}}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                {{-- Page Title --}}
                <div class="col-sm-6 d-none d-md-block">
                    <a class="text-white" href="#">
                        <h1>{{ __('Aplikasi Penimbangan') }}</h1>
                    </a>
                </div>
                <div class="col-sm-6 d-none d-md-block text-right">
                    {{-- <button class="btn btn-primary">Tambah</button> --}}
                </div>
            </div>
        </div>
    </section>


    <section class="content">
        <section class="container-fluid">
            <weighing-main
                url-data="{{ route('ajax.wh.get.weighing') }}"
                url-store="{{ route('wh.weighing.store') }}"
                url-supplier-options="{{ route('ajax.get.supplier.options') }}"
                url-employee-options="{{ route('hr.ajax.employee.options') }}"

                url-weighing-category-data="{{ route('ajax.wh.get.weighing.category') }}"
                url-weighing-category-store="{{ route('wh.weighing.category.store') }}"
                url-weighing-category-check-name-exist="{{ route('ajax.stock.check.weighing.category.name.exist') }}"
            ></weighing-main>
        </section>
    </section>
@endsection
