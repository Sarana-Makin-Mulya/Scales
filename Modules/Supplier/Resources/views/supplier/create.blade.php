<div class="container-fluid">

        <div class="row form-group">
            <div class="col-12">
                <label for="exampleInputEmail1">Nama Supplier</label>
                <input type="text" class="form-control form-control-sm" placeholder="Nama supplier" value="">
            </div>
        </div>

        <div class="row form-group">
            <div class="col-4">
                <label for="exampleInputEmail1">No Telpon</label>
                <input type="text" class="form-control form-control-sm" placeholder="">
            </div>
            <div class="col-4">
                <label for="exampleInputEmail1">No Fax</label>
                <input type="text" class="form-control form-control-sm" placeholder="">
            </div>
            <div class="col-4">
                <label for="exampleInputEmail1">Email</label>
                <input type="text" class="form-control form-control-sm" placeholder="">
            </div>
        </div>

        <div class="row form-group">
            <div class="col-12">
                <label for="exampleInputEmail1">Kategori</label>
                <input type="text" class="form-control form-control-sm" placeholder="">
            </div>
        </div>

        <div class="row form-group">
            <div class="col-12">
                <label for="exampleInputEmail1">Alamat</label>
                <input type="text" class="form-control form-control-sm" placeholder="">
            </div>
        </div>

        <div class="row form-group">
            <div class="col-4">
                <label for="exampleInputEmail1">Provinsi</label>
                <input type="text" class="form-control form-control-sm" placeholder="">
            </div>
            <div class="col-4">
                <label for="exampleInputEmail1">Kota</label>
                <input type="text" class="form-control form-control-sm" placeholder="">
            </div>
            <div class="col-4">
                <label for="exampleInputEmail1">Kode Pos</label>
                <input type="text" class="form-control form-control-sm" placeholder="">
            </div>
        </div>

        <div class="row form-group">
            <div class="col-12">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="kena_pajak" value="Y">
                    <label class="form-check-label" for="kena_pajak">Kena Pajak</label>
                </div>
            </div>
        </div>

        <div class="row form-group">
            <div class="col-12">
                <label for="name" class="col-form-label">Nomor NPWP
                    <span class="small"><i data-toggle="tooltip" data-placement="bottom" title="" class="fas fa-question-circle" data-original-title="Nomor Pokok Wajib Pajak"></i></span>
                </label>
                <input type="text" placeholder="Nomor NPWP" class="form-control form-control-sm">
            </div>
        </div>

        <div class="row form-group">
            <div class="col-12">
                <label for="name" class="col-form-label">Nomor SPPKP
                    <span class="small"><i data-toggle="tooltip" data-placement="bottom" title="" class="fas fa-question-circle" data-original-title="Surat Pengukuhan Pelanggan Kena Pajak."></i></span>
                </label>
                <input type="text" placeholder="Nomor SPPKP" class="form-control form-control-sm">
            </div>
        </div>

        <div class="row form-group">
            <div class="col-12">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="alamat_npwp" value="Y">
                    <label class="form-check-label" for="alamat_npwp">Alamat NPWP sama dengan alamat supplier</label>
                </div>
            </div>
        </div>

        <span id="npwp_address" style="display:none">
        {{-- B : NPWP Address --}}
        <div class="row form-group">
            <div class="col-12">
                <label for="exampleInputEmail1">Alamat</label>
                <input type="text" class="form-control form-control-sm" placeholder="">
            </div>
        </div>

        <div class="row form-group">
            <div class="col-4">
                <label for="exampleInputEmail1">Provinsi</label>
                <input type="text" class="form-control form-control-sm" placeholder="">
            </div>
            <div class="col-4">
                <label for="exampleInputEmail1">Kota</label>
                <input type="text" class="form-control form-control-sm" placeholder="">
            </div>
            <div class="col-4">
                <label for="exampleInputEmail1">Kode Pos</label>
                <input type="text" class="form-control form-control-sm" placeholder="">
            </div>
        </div>

        </span>
        {{-- E : NPWP Address --}}
    </div>
