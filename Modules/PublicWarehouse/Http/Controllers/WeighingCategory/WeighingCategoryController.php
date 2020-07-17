<?php

namespace Modules\PublicWarehouse\Http\Controllers\WeighingCategory;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Routing\Controller;
use Modules\PublicWarehouse\Entities\WeighingCategory;

class WeighingCategoryController extends Controller
{
   /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('publicwarehouse::weighing.index');
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);
        $data = [
            'name' => $request->input('name'),
            'slug' => Str::slug($request->input('name'), '-'),
            'description' => $request->input('description'),
        ];

        $weighingCategory = new WeighingCategory();
        $save = $weighingCategory->create($data);

        return response()->json([
            'id' => $save->id,
            'changed' => true,
            'act' => 'New',
            'message' => __('Berhasil menambahkan data kategori penimbangan.'),
        ]);
    }

    public function update(Request $request, $id)
    {
        $weighingCategory = WeighingCategory::find($id);

        $request->validate([
            'name' => 'required',
        ]);

        $weighingCategory->update([
            'name' => $request->input('name'),
            'slug' => Str::slug($request->input('name'), '-'),
            'description' => $request->input('description'),
        ]);

        return response()->json([
            'id' => $id,
            'changed' => changeDetection($weighingCategory),
            'act' => 'Update',
            'message' => 'Berhasil memperbaharui data kategori penimbangan.',
        ]);
    }
}
