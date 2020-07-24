<?php

namespace Modules\Stock\Http\Controllers\Brand;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\Stock\Entities\Brand;
use Modules\Stock\Http\Requests\Brand\BrandRequest;
use PDF;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('stock::brand.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('stock::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(BrandRequest $request)
    {
        $brandObj = new Brand();
        $save = $brandObj->create([
            'name' => strtoupper($request->name),
            'slug' => Str::slug($request->name),
        ]);

        return response()->json([
            'id' => $save->id,
            'changed' => true,
            'act' => 'New',
            'message' => 'Berhasil menambahkan data merek barang baru.',
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(BrandRequest $request, $id)
    {
        if ($brand = Brand::find($id)) {
            $brand->update([
                'name' => strtoupper($request->name),
                'slug' => Str::slug($request->name),
            ]);

            return response()->json([
                'id' => $id,
                'changed' => changeDetection($brand),
                'act' => 'Update',
                'message' => 'Berhasil memperbaharui data merek barang.',
            ]);
        }

        return response()->json(['message' => 'Data tidak ditemukan'], 404);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function updateStatus(Request $request, $id)
    {
        $message = 'Berhasil menon-aktifkan data merek barang.';

        if ($request->status) {
            $message = 'Berhasil mengaktifkan data merek barang.';
        }

        if ($brand = Brand::find($id)) {
            $brand->update([ 'is_active' => $request->status ]);
            return response()->json([ 'message' => $message ]);
        }

        return response()->json(['message' => 'Data tidak ditemukan']);
    }

    public function exportPdf(Request $request)
    {
        $brand = Brand::all();

        $pdf = PDF::loadView('stock::brand.export_pdf', ['brand' => $brand]);

        return $pdf->download();
    }
}
