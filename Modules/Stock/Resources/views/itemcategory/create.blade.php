{!! Form::open(['route' => 'itemcategory.store', 'method' => 'POST']) !!}
<div class="container-fluid">
    <div class="form-row">
        <div class="col-12 form-group">
            <label for="name">Nama Kategori</label>
            {!!
                 Form::text(
                    'name', null,
                    [
                        'class' => 'form-control form-control-sm',
                        'id' => 'name',
                        'placeholder' => 'Nama Kategori Barang',
                        'autocomplete' => 'off'
                    ]
                )
            !!}
            </div>
    </div>
</div>
{!! Form::close() !!}

