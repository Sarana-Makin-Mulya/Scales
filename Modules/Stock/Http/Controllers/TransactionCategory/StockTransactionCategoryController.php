<?php

namespace Modules\Stock\Http\Controllers\TransactionCategory;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Stock\Entities\StockTransactionCategory;

class StockTransactionCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('stock::transactionCategory.index');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
        ]);

        $data = [
            'name' => $request->input('name')
        ];

        $transactioncategory = new StockTransactionCategory();
        $transactioncategory->create($data);

        return response()->json(['message' => __('Berhasil menambahkan data kategori transaksi.')]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $transactioncategory = StockTransactionCategory::find($id);

        $request->validate([
            'name' => 'required|string|max:50',
        ]);

        $transactioncategory->update([
            'name' => $request->name
        ]);

        return response()->json([
            'message' => 'Berhasil memperbaharui data kategori transaksi.',
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $message = 'Berhasil menon-aktifkan data kategori transaksi.';

        if ($request->status) {
            $message = 'Berhasil mengaktifkan data kategori transaksi.';
        }

        if ($transactioncategory = StockTransactionCategory::find($id)) {
            $transactioncategory->update([ 'is_active' => $request->status]);
            return response()->json([ 'message' => $message ]);
        }

        return response()->json(['message' => 'Data tidak ditemukan']);
    }
}
