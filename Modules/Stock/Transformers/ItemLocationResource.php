<?php

namespace Modules\Stock\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class ItemLocationResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'storage_map_properties_id' => $this->storage_map_properties_id,
            'storage_map_rack_stage_id' => $this->storage_map_rack_stage_id,
            'item_code' => $this->item_code,
            'floors_name' => getStorageMapFloorNameByPropertiesId($this->storage_map_properties_id),
            'location_name' => getStorageMapProperties($this->storage_map_rack_stage_id),
        ];
    }
}
