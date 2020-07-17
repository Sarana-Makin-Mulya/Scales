<?php

namespace Modules\PublicWarehouse\Http\Controllers\WeighingItem;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Routing\Controller;
use Modules\PublicWarehouse\Entities\WeighingItems;

class WeighingItemController extends Controller
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
            'code' => $this->generateCodeWeighingItem(),
            'name' => $request->input('name'),
            'slug' => Str::slug($request->input('name'), '-'),
            'description' => $request->input('description'),
        ];

        $weighingItems = new WeighingItems();
        $save = $weighingItems->create($data);

        return response()->json([
            'code' => $save->code,
            'changed' => true,
            'act' => 'New',
            'message' => __('Berhasil menambahkan data barang penimbangan.'),
        ]);
    }

    public function update(Request $request, $code)
    {
        $weighingItems = WeighingItems::find($code);

        $request->validate([
            'name' => 'required',
        ]);

        $weighingItems->update([
            'name' => $request->input('name'),
            'slug' => Str::slug($request->input('name'), '-'),
            'description' => $request->input('description'),
        ]);

        return response()->json([
            'code' => $code,
            'changed' => changeDetection($weighingItems),
            'act' => 'Update',
            'message' => 'Berhasil memperbaharui data barang penimbangan.',
        ]);
    }

    public function generateCodeWeighingItem()
    {
        $last_row = WeighingItems::withTrashed()
            ->whereRaw('MONTH(created_at) = '.date('m'))
            ->whereRaw('YEAR(created_at) = '.date('Y'))
            ->count()+1;
        return $this->checkCodeWeighingItem($last_row);
    }

    public function checkCodeWeighingItem($number)
    {
        $month = date('m');
        $year  = date('y');
        $code  = trim("WI".$month.$year.str_pad($number ,4,0,STR_PAD_LEFT));
        if (WeighingItems::withTrashed()->where('code', $code)->exists()) {
            $number++;
            return $this->checkCodeWeighingItem($number);
        }

        return $code;
    }
}
