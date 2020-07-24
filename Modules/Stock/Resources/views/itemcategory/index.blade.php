@extends('layouts.app')
@section('content')
    {{-- Page Header --}}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                {{-- Page Title --}}
                <div class="col-sm-6 d-none d-md-block">
                    <a class="text-white" href="#">
                        <h1>{{ __('Kategori Barang') }}</h1>
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
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="nav-icon fas fa-store"></i> Data Kategori Barang</h3>
                            {{-- B :  Btn Action --}}
                                <span class="float-right">
                                <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                                Export
                                <span class="sr-only">Toggle Dropdown</span>
                                <div class="dropdown-menu" role="menu" x-placement="bottom-start">
                                    <a class="dropdown-item" href="#">Excel</a>
                                    <a class="dropdown-item" href="#">Word</a>
                                    <a class="dropdown-item" href="#">PDF</a>
                                </div>
                                </button>

                                <a href="{{ route('itemcategory.add') }}" class="btn btn-primary modal-show" title="Tambah Kategori">
                                    <i class="fas fa-plus" ></i> Tambah
                                </a>

                                </span>
                            {{-- E :  Btn Action --}}
                        </div>
                        <div class="card-body">
                            {{-- B :  List --}}
                                <table id="datatable" class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th width="45%">Nama</th>
                                            <th width="45%">Slug</th>
                                            <th width="10%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            {{-- E :  List --}}
                        </div>
                    </div>
                </div>
            <div>
        </div>

        <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-header" id="modal-header">
                            <h4 class="modal-title" id="modal-title">Modal Form</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body" id="modal-body">
                        </div>

                        <div class="modal-footer" id="modal-footer">
                            <button type="button" class="btn btn-cancel"  id="modal-btn-cancel" data-dismiss="modal">Batal</button>
                            <button type="button" class="btn btn-primary" id="modal-btn-save">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection

@section('script')
    <script>
    $('#datatable').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: "{{ route('itemcategory.datatable') }}",
        columns: [
            {data: 'name', name: 'name'},
            {data: 'slug', name: 'slug'},
            {data: 'action', name: 'action'}
        ]
    });

    // Modal form
    $('body').on('click', '.modal-show', function (event) {
        event.preventDefault();

        var me = $(this),
            url = me.attr('href'),
            title = me.attr('title');

        $('#modal-title').text(title);
        $('#modal-btn-save').removeClass('d-none').text(me.hasClass('edit') ? 'Simpan Perubahan' : 'Simpan');
        $('#modal-btn-cancel').attr('class','btn btn-cancel');
        $('#modal-btn-cancel').text('Batal');

        $.ajax({
            url: url,
            dataType: 'html',
            success: function (response) {
                $('#modal-body').html(response);
            }
        });

        $('#modal').modal('show');
    });

    // Modal Detail
    $('body').on('click', '.btn-show', function (event) {
        event.preventDefault();

        var me = $(this),
            url = me.attr('href'),
            title = me.attr('title');

        $('#modal-title').text(title);
        $('#modal-btn-save').addClass('d-none');
        $('#modal-btn-cancel').attr('class','btn btn-default');
        $('#modal-btn-cancel').text('Keluar');

        $.ajax({
            url: url,
            dataType: 'html',
            success: function (response) {
                $('#modal-body').html(response);
            }
        });

        $('#modal').modal('show');
    });

    // Ajax Save
    $('#modal-btn-save').click(function (event) {
    event.preventDefault();

    var form = $('#modal-body form'),
        url = form.attr('action'),
        method = $('input[name=_method]').val() == undefined ? 'POST' : 'PUT';

    form.find('.invalid-feedback').remove();
    form.find('.form-control').removeClass('is-invalid');

    $.ajax({
            url : url,
            method: method,
            data : form.serialize(),
            success: function (response) {
                form.trigger('reset');
                $('#modal').modal('hide');
                $('#datatable').DataTable().ajax.reload();
                swal.fire({
                    type : 'success',
                    title : 'Success!',
                    text : 'Data berhasil disimpan!'
                });
            },
            error : function (xhr) {
                var res = xhr.responseJSON;
                if ($.isEmptyObject(res) == false) {
                    $.each(res.errors, function (key, value) {
                        $('#' + key).closest('.form-group').append('<div class="invalid-feedback">' + value + '</div>');
                        $('#' + key).closest('.form-control').addClass('is-invalid');
                    });
                }
            }
        })
    });

    // Ajax Delete
    $('body').on('click', '.btn-delete', function (event) {
    event.preventDefault();

    var me = $(this),
        url = me.attr('href'),
        title = me.attr('title'),
        csrf_token = $('meta[name="csrf-token"]').attr('content');

    swal.fire({
        title: 'Hapus data ' + title + ', Anda yakin ?',
        text: 'Data yang sudah dihapus tidak dapat dibatalkan.',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Hapus!'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    '_method': 'DELETE',
                    '_token': csrf_token
                },
                success: function (response) {
                    $('#datatable').DataTable().ajax.reload();
                    swal({
                        type: 'success',
                        title: 'Success!',
                        text: 'Data berhasil dihapus!'
                    });
                },
                error: function (xhr) {
                    swal({
                        type: 'error',
                        title: 'Oops...',
                        text: 'Ada yang salah!'
                    });
                }
            });
        }
    });
});
    </script>
@endsection

