<?php

namespace Modules\Stock\Http\Controllers\Stock;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\PurchaseOrder\Entities\PurchaseRequestItemDetail;
use Modules\PurchaseOrder\Entities\PurchaseRequestItems;
use Modules\Stock\Entities\Item;
use Modules\Stock\Transformers\Stock\StockLessResource;

class AjaxGetStockBuffer extends Controller
{
    protected $orderBy;
    protected $sortBy = 'ASC';

    public function __invoke(Request $request)
    {
        if ($request->call_by=="pr") {
            $pr_code = $request->code;
            if (!empty($pr_code)) {
                $arr_code = $this->getPurchaseRequestDetail($pr_code);
                if (!empty($arr_code)) {
                    $items = Item::with('itemCategory')
                        ->select('*')
                        ->addSelect(DB::raw("'$pr_code' as pr_code"))
                        ->whereNotIn('code', PurchaseRequestItemDetail::select('item_code')
                            ->where('source', 'stock-buffer')
                            ->whereHas('purchaseRequestItem', function ($query) use ($pr_code) {
                                $query->where('status', '<',PurchaseRequestItems::DONE)
                                      ->where('ic_purchase_request_code', '<>', $pr_code);
                            })
                            ->where('is_active', 1)
                            ->get())
                        ->where('info', 'LIKE', '%' . $request->keyword . '%')
                        ->whereRaw('min_stock > current_stock')
                        ->get();
                } else {
                    $items = Item::with('itemCategory')
                        ->whereNotIn('code', PurchaseRequestItemDetail::select('item_code')
                                                ->where('source', 'stock-buffer')
                                                ->whereHas('purchaseRequestItem', function ($query) {
                                                    $query->where('status', '<',PurchaseRequestItems::DONE);
                                                })
                                                ->where('is_active', 1)
                                                ->get())
                        ->where('info', 'LIKE', '%' . $request->keyword . '%')
                        ->whereRaw('min_stock > current_stock')
                        ->get();
                }
            } else {
                $items = Item::with('itemCategory')
                    ->whereNotIn('code', PurchaseRequestItemDetail::select('item_code')
                                            ->where('source', 'stock-buffer')
                                            ->whereHas('purchaseRequestItem', function ($query) {
                                                $query->where('status', '<',PurchaseRequestItems::DONE);
                                            })
                                            ->where('is_active', 1)
                                            ->get())
                    ->where('info', 'LIKE', '%' . $request->keyword . '%')
                    ->whereRaw('min_stock > current_stock')
                    ->get();
            }
        } else {
            $this->createSortOrder($request);
            $items = Item::with('itemCategory')
                ->whereNotIn('code', PurchaseRequestItemDetail::select('item_code')
                                        ->where('source', 'stock-buffer')
                                        ->whereHas('purchaseRequestItem', function ($query) {
                                            $query->where('status', '<',PurchaseRequestItems::DONE);
                                        })
                                        ->where('is_active', 1)
                                        ->get())
                ->where('info', 'LIKE', '%' . $request->keyword . '%')
                ->whereRaw('min_stock > current_stock')
                ->orderBy($this->orderBy, $this->sortBy)
                ->paginate($request->per_page);
        }

        return StockLessResource::collection($items);
    }

    public function getPurchaseRequestDetail($code)
    {
        $arr = [];
        $detail = PurchaseRequestItemDetail::query()
            ->select('ic_purchase_request_item_details.item_code')
            ->leftjoin('ic_purchase_request_items','ic_purchase_request_items.id','=','ic_purchase_request_item_details.ic_purchase_request_item_id')
            ->where('ic_purchase_request_items.ic_purchase_request_code',$code)
            ->where('ic_purchase_request_item_details.source', 'stock-buffer')
            ->where('ic_purchase_request_item_details.is_active', 1)
            ->get();

        if ($detail->count()>0) {
            foreach ($detail as $dt) {
                $arr[] = $dt->item_code;
            }
        }

        return $arr;
    }

    private function createSortOrder(Request $request)
    {
        !empty($request->order_by)
            ? $this->orderBy = $request->order_by
            : $this->orderBy = 'code';

        ((boolean) $request->sort_desc == false
        || (string) $request->sort_desc == "false")
            ? $this->sortBy = 'ASC'
            : $this->sortBy = 'DESC';

        if ($request->order_by == 'status') {
            $this->orderBy = 'is_active';
        }
    }
}
