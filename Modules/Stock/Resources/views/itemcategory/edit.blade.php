{!! Form::model($model, [
    'route' => $model->exists ? ['itemcategory.update', $model->id] : 'itemcategory.store',
    'method' => $model->exists ? 'PUT' : 'POST'
]) !!}

<div class="container-fluid">

    <div class="row">
        <div class="col-12 form-group">
            <label for="name">Nama Kategori</label>
            {!!
                Form::text(
                   'name', $model->name,
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

