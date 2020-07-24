<!doctype html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html;" charset="utf-8"/>
<title>Laporan Penyesuaian Stok</title>
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
{{-- <center style="font-size:16px"><strong>Permintaan Barang</strong></center><br> --}}

<table width="100%" class="data">
    <thead style="background-color: lightgray;">
        <tr>
            <th class="data1" width="5%">#</th>
            <th class="data1" width="10%">Kode</th>
            <th class="data1" width="25%" style="text-align:left;padding-left:5px;">Proses</th>
            <th class="data1" width="25%" style="text-align:left;padding-left:5px;">Pembuat</th>
            <th class="data1" width="25%" style="text-align:left;padding-left:5px;">Tanggal</th>
            <th class="data1" width="15%">Status</th>
        </tr>
    </thead>
    <tbody>
        @php
            $i=1
        @endphp
        @foreach($data as $d)
        <tr>
            <td class="data1">{{ $i++ }}</td>
            <td class="data1">{{ $d->code }}</td>
            <td class="data1">{{ ($d->stock_opname_group_id>0) ? 'Stok Opname' : 'Penyesuaian Stock' }}</td>
            <td class="data1" style="text-align:left;padding-left:5px;">{{ getEmployeeFullName($d->issued_by) }}</td>
            <td class="data1" style="text-align:left;padding-left:5px;">{{ date('d/m/Y H:i', strtotime($d->issue_date)) }}</td>
            <td class="data1">
                @if($d->status == 1) Request
                @elseif($d->status == 2) Proses
                @elseif($d->status == 3) Selesai
                @else Tidak DIketahui
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
