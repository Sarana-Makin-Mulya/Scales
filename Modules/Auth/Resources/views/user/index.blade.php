@extends('layouts.app')

@section('content')
    {{-- Page Header --}}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                {{-- Page Title --}}
                <div class="col-sm-6 d-none d-md-block">
                    <a class="text-white" href="#">
                        <h1>{{ __('User') }}</h1>
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
                    <user-table-records
                        url-data="{{ route('ajax.user.get.manage') }}"
                        url-store="{{ route('user.manage.store') }}"
                        url-check-username-exist="{{ route('ajax.user.get.manage.username.exist') }}"
                        url-check-email-exist="{{ route('ajax.user.get.manage.email.exist') }}"
                        url-level-group-options="{{ route('ajax.user.get.level.group.options') }}"
                    ></user-table-records>
                </div>
            <div>
        </div>
    </section>
@endsection

