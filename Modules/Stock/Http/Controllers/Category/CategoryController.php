<?php

namespace Modules\Stock\Http\Controllers\Category;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\Stock\Entities\ItemCategory;
use PDF;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('stock::category.index');
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
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:5',
            'name' => 'required|string|max:255',
        ]);

        $data = [
            'code' => strtoupper($request->input('code')),
            'old_code' => strtoupper($request->input('old_code')),
            'name' => strtoupper($request->input('name')),
            'slug' => Str::slug($request->input('name'), '-'),
        ];

        $category = new ItemCategory();
        $save     = $category->create($data);


        return response()->json([
            'id' => $save->id,
            'changed' => true,
            'act' => 'New',
            'message' => __('Berhasil menambahkan data satuan baru.'),
        ]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('stock::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('stock::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $category = ItemCategory::find($id);

        $request->validate([
            'code' => 'required|string|max:5',
            'name' => 'required|string|max:255',
        ]);

        $category->update([
            'code' => strtoupper($request->code),
            'old_code' => strtoupper($request->old_code),
            'name' => strtoupper($request->name),
            'slug' => Str::slug($request->name, '-'),
        ]);

        return response()->json([
            'id' => $id,
            'changed' => changeDetection($category),
            'act' => 'Update',
            'message' => 'Berhasil memperbaharui data kategori barang.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function updateStatus(Request $request, $id)
    {
        $message = 'Berhasil menon-aktifkan data kategori barang.';

        if ($request->status) {
            $message = 'Berhasil mengaktifkan data kategori barang.';
        }

        if ($category = ItemCategory::find($id)) {
            $category->update([ 'is_active' => $request->status]);
            return response()->json([ 'message' => $message ]);
        }

        return response()->json(['message' => 'Data tidak ditemukan']);
    }

    public function exportPdf(Request $request)
    {
        $category = ItemCategory::all();

        $pdf = PDF::loadView('stock::category.export_pdf', ['category' => $category]);

        return $pdf->download();
    }
}
