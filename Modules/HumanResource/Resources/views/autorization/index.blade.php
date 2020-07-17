@extends('layouts.app')

@section('content')
    {{-- Page Header --}}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                {{-- Page Title --}}
                <div class="col-sm-6 d-none d-md-block">
                    <a class="text-white" href="#">
                        <h1>{{ __('Otorisasi') }}</h1>
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
                    <employee-autorization-table-records
                        url-data="{{ route('ajax.hr.get.autorization') }}"
                        url-store="{{ route('hr.autorization.store') }}"
                        url-department-option="{{ route('hr.ajax.department.options') }}"
                        url-employee-options="{{ route('hr.ajax.employee.options') }}"
                        url-check-name-exist="{{ route('ajax.hr.check.autorization.name.exist') }}"
                        url-check-code-exist="{{ route('ajax.hr.check.autorization.code.exist') }}"
                    ></employee-autorization-table-records>
                </div>
            <div>
        </div>
    </section>
@endsection

