<?php

namespace Modules\Stock\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Storage;
use Modules\Stock\Entities\ItemUnitConversion;
use Modules\Stock\Entities\StockTransaction;
use Modules\StorageMap\Entities\StorageMapRackItem;

class ItemResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $unitPrimary = ItemUnitConversion::where('item_code',$this->code)->where('is_active',1)->where('is_primary',1)->first();
        $unit_id = (!empty($unitPrimary)) ? $unitPrimary->unit_id : '';
        $unit_name = (!empty($unitPrimary)) ? getUnitName($unitPrimary->unit_id) : '';

        $firstStock = StockTransaction::query()
            ->select('id', 'quantity', 'item_unit_conversion_id', 'transaction_code', 'transaction_symbol')
            ->where('transaction_code', $this->stock_app_old_id)
            ->where('item_code', $this->code)
            ->where('transaction_symbol', "OA")
            ->first();

        if (empty($firstStock)) {
            $firstStock = [
                'id' => null,
                'quantity' => null,
                'item_unit_conversion_id' => null,
                'transaction_code' => null,
                'transaction_symbol'  => null
            ];
        }

        $getLocation = StorageMapRackItem::query()
            ->where('is_active', 1)
            ->where('item_code', $this->code)
            ->get();

        return [
            'code' => $this->code,
            'old_code' => $this->old_code,
            'item_category_id' => $this->item_category_id,
            'item_brand_id' => $this->item_brand_id,
            'item_measure_id' => $this->item_measure_id,
            'item_brand' => getBrandName($this->item_brand_id),
            'item_measure_id' => $this->item_measure_id,
            'name' => $this->name,
            'detail' => $this->detail,
            'slug' => $this->slug,
            'nickname' => $this->nickname,
            'type' => $this->type,
            'size' => $this->size,
            'color' => $this->color,
            'description' => $this->description,
            'is_priority' => $this->is_priority,
            'borrowable' => $this->borrowable,
            'max_stock' => $this->max_stock,
            'min_stock' => $this->min_stock,
            'first_stock' => $firstStock,
            'current_stock' => getItemStock($this->code),
            'location' => getItemLocation($this->code),
            'status' => (boolean) $this->is_active,
            'status_stock' => (boolean) $this->status_stock,
            'stock_app_old_id' => $this->stock_app_old_id,
            'unit_id' => $unit_id,
            'unit_name' => $unit_name,
            'conversion' => (ItemUnitConversion::where('item_code',$this->code)->where('is_active',1)->where('is_primary',0)->count() > 0) ? 1 : 0,
            'item_category' => new ItemCategoryResource($this->itemCategory),
            'item_conversion' => ItemConversionResource::collection(ItemUnitConversion::where('item_code',$this->code)->where('is_active',1)->where('is_primary',0)->get()),
            'item_locations' => ItemLocationResource::collection($getLocation),
            'url_edit' => route('stock.item.update', [$this->code]),
            'url_status_update' => route('stock.item.update.status', [$this->code]),
            'url_status_stock_update' => route('stock.item.update.status.stock', [$this->code]),
            'url_delete' => route('ajax.stock.item.destroy.item', [$this->code]),
        ];
    }
}

// storage_map_properties_id: this.formItemLocation.storage_map_properties_id.id,
// storage_map_rack_stage_id: storage_map_rack_stage_id,
// item_code : this.form.code,
// floors_name : this.formItemLocation.storage_map_floor_id.name,
// location_name : location,
