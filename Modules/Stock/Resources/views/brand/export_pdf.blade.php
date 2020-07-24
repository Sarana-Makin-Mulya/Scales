<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Laporan Merek Barang</title>
<style type="text/css">
    body {
        font-family: 'Helvetica';
    }
    table {
        font-size: small;
    }
    .data1 {
        height: 30px;
        padding-left: 10px;
        border: 1px solid black;
    }
    .data {
        border-collapse: collapse;
        border: 1px solid black;
    }
    tfoot tr td{
        font-weight: bold;
        font-size: small;
    }
    .gray {
        background-color: lightgray
    }
    .header {
        padding: 10px 0;
        margin-bottom: 20px;
        border-bottom: 1px solid #AAAAAA;
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

{{-- <table width="100%">
    <tr>
        <td><strong>From:</strong> Linblum - Barrio teatral</td>
        <td><strong>To:</strong> Linblum - Barrio Comercial</td>
    </tr>
</table> --}}

<br/>

<table width="100%" class="data">
    <thead style="background-color: lightgray;">
    <tr>
        <th class="data1">#</th>
        <th class="data1">Name</th>
    </tr>
    </thead>
    <tbody>
    @php
        $i=1;
    @endphp
    @foreach ($brand as $b)
    <tr>
        <td class="data1">{{ $i++ }}</td>
        <td class="data1">{{ $b->name }}</td>
    </tr>
    @endforeach
    </tbody>

    {{-- <tfoot>
        <tr>
            <td colspan="3"></td>
            <td align="right">Subtotal $</td>
            <td align="right">1635.00</td>
        </tr>
        <tr>
            <td colspan="3"></td>
            <td align="right">Tax $</td>
            <td align="right">294.3</td>
        </tr>
        <tr>
            <td colspan="3"></td>
            <td align="right">Total $</td>
            <td align="right" class="gray">$ 1929.3</td>
        </tr>
    </tfoot> --}}
</table>

</body>
</html>
