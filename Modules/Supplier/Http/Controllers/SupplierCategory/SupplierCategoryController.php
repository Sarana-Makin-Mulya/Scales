<?php

namespace Modules\Supplier\Http\Controllers\SupplierCategory;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Supplier\Entities\SupplierCategory;
use Illuminate\Support\Str;

class SupplierCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('supplier::supplier_category.index');
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
            'code' => $request->input('code'),
            'name' => $request->input('name'),
            'slug' => Str::slug($request->input('name'), '-'),
        ];

        $supplierCategory = new SupplierCategory();
        $save = $supplierCategory->create($data);

        return response()->json([
            'id' => $save->id,
            'changed' => true,
            'act' => "New",
            'message' => __('Berhasil menambahkan data kategori supplier baru.'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $supplierCategory = SupplierCategory::find($id);

        $request->validate([
            'code' => 'required|string|max:5',
            'name' => 'required|string|max:255',
        ]);

        $supplierCategory->update([
            'code' => $request->code,
            'name' => $request->name,
            'slug' => Str::slug($request->name, '-'),
        ]);

        return response()->json([
            'id' => $id,
            'changed' => changeDetection($supplierCategory),
            'act' => "Update",
            'message' => 'Berhasil memperbaharui data kategori supplier.',
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $message = 'Berhasil menon-aktifkan data kategori supplier.';

        if ($request->status) {
            $message = 'Berhasil mengaktifkan data kategori supplier.';
        }

        if ($supplierCategory = SupplierCategory::find($id)) {
            $supplierCategory->update([ 'is_active' => $request->status]);
            return response()->json([ 'message' => $message ]);
        }

        return response()->json(['message' => 'Data tidak ditemukan']);
    }
}
