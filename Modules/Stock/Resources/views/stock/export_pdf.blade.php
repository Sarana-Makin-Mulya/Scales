<!doctype html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html;" charset="utf-8"/>
<title>Permintaan : {{ $data->code }}</title>
<style type="text/css">
    body {
        font-family: 'Helvetica';
        font-size: 8px;
    }
    table {
        font-size: 8px;
    }
    .data1 {
        height: 30px;
        border: 1px solid black;
        text-align: center;
    }
    .data {
        border-collapse: collapse;
        border: 1px solid black;
    }
    tfoot tr td{
        font-weight: bold;
    }
    .gray {
        background-color: lightgray
    }
    .header {
        padding: 10px 0;
        margin-bottom: 20px;
        /* border-bottom: 1px solid #AAAAAA; */
        background-color: #000034;
        color: white;
    }
    .img {
        margin-left: 20px;
    }
    .txt {
        margin-right: 20px;
        font-family: 'Helvetica';
    }
</style>
</head>
<body>

<table width="100%" class="header">
    <tr>
    <td valign="middle"><img src="{{ public_path('images/logo-smm.png') }}" class="img" alt="" width="150"/></td>
        <td align="right">
            <h3 class="txt">PT Sarana Makin Mulya</h3>
            <pre class="txt">
                Jl. Raya Cimareme No.273
                Kab. Bandung Barat
                Jawa Barat
                (0268) 66040
            </pre>
        </td>
    </tr>

</table>
<center style="font-size:16px"><strong>Penyesuaian Stok</strong></center>
<table width="100%">
    <tr>
        <td width="50%">
            {{-- Left --}}
            <table width="100%" border="0">
                <tr>
                    <td width="35%"><strong>Kode</strong></td>
                    <td width="2%">:</td>
                    <td width="63%">{{ $data->code }}</td>
                </tr>
                <tr>
                    <td><strong>Tanggal Permintaan</strong></td>
                    <td>:</td>
                    <td>{{ date('d/m/Y H:i', strtotime($data->issue_date)) }}</td>
                </tr>
                <tr>
                    <td><strong>Pembuat</strong></td>
                    <td>:</td>
                    <td>{{ getEmployeeDescription($data->issued_by) }}</td>
                </tr>
                <tr>
                    <td><strong>Status</strong></td>
                    <td>:</td>
                    <td>
                        @if($data->status==1)
                        Request
                        @elseif($data->status==2)
                        Proses
                        @elseif($data->status==3)
                        Selesai
                        @else
                        Tidak diketahui
                        @endif
                    </td>
                </tr>
            </table>
        </td>
        <td width="50%">
            -
        </td>
    </tr>
</table>
<br>
<div style="margin-bottom:10px"><b>Daftar Barang</b></div>
<table width="100%" class="data">
    <thead style="background-color: lightgray;">
    <tr>
        <th class="data1" width="3%">#</th>
        <th class="data1" width="25%" style="text-align:left;padding-left:5px;">Barang</th>
        <th class="data1" width="10%">Kuantiti</th>
        <th class="data1" width="12%" style="text-align:left;padding-left:5px;">Keterangan</th>
        <th class="data1" width="25%" style="text-align:left;padding-left:5px;">Persetujuan</th>
        <th class="data1" width="25%" style="text-align:left;padding-left:5px;">Laporan</th>
    </tr>
    </thead>
    <tbody>
        @php
            $i=1
        @endphp
        @foreach($data->stockAdjustmentItem as $d)
        @if($d->deleted_at==null)
        <tr>
            <td class="data1">{{ $i++ }}</td>
            <td class="data1" style="text-align:left;padding-left:5px;">{{ getItemDetail($d->item_code) }}</td>
            <td class="data1">{{ $d->quantity.' '.getUnitConversionName($d->item_unit_conversion_id) }}</td>
            <td class="data1">{{ getStockAdjustmentCategoryName($d->stock_adjustment_category_id)}}</td>
            <td class="data1" style="text-align:left;padding-left:5px;">
                @if($d->approvals_status==1)
                <div>Menunggu</div>
                @else
                <table>
                    <tr>
                        <td>Status</td>
                        <td>:
                            @if($d->approvals_status==2) Disetujui @endif
                            @if($d->approvals_status==3) Ditolak @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Oleh</td>
                        <td>:
                            {{ getEmployeeFullname($d->approvals_by) }}
                        </td>
                    </tr>
                    <tr>
                        <td>Tanggal</td>
                        <td>:
                            {{ date('d/m/Y H:i', strtotime($d->issue_date)) }}
                        </td>
                    </tr>
                    @if($d->approvals_note!=null && $d->approvals_note!='-')
                    <tr>
                        <td>Catatan</td>
                        <td>:
                            {{ $d->approvals_note }}
                        </td>
                    </tr>
                    @endif
                </table>
                @endif
            </td>
            <td class="data1" style="text-align:left;padding-left:5px;">
                @if($d->approvals_status==1)
                    <div>Menunggu</div>
                @else
                     @if(!empty($d->release_by))
                        <table>
                            <tr>
                                <td>Oleh</td>
                                <td>:
                                    {{ getEmployeeFullname($d->release_by) }}
                                </td>
                            </tr>
                            <tr>
                                <td>Tanggal</td>
                                <td>:
                                    {{ date('d/m/Y H:i', strtotime($d->release_date)) }}
                                </td>
                            </tr>
                            @if($d->release_note!=null && $d->release_note!='-')
                            <tr>
                                <td>Catatan</td>
                                <td>:
                                    {{ $d->release_note }}
                                </td>
                            </tr>
                            @endif
                        </table>
                    @else
                        Menunggu Laporan
                    @endif
                @endif
            </td>
        </tr>
        @endif
        @endforeach
    </tbody>
</table>
</body>
</html>
