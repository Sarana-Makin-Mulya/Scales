<?php
use Illuminate\Support\Facades\DB;
use Modules\StorageMap\Entities\StorageMapCategory;
use Modules\StorageMap\Entities\StorageMapFloor;
use Modules\StorageMap\Entities\StorageMapProperties;
use Modules\StorageMap\Entities\StorageMapRackItem;
use Modules\StorageMap\Entities\StorageMapRackStage;

if (! function_exists('getStorageMapByItemDO')) {
    function getStorageMapByItemDO($storage_map_rack_item_id)
    {
        $model   = new Modules\StorageMap\Entities\StorageMapRackItem;
        $rack = $model::query()
            ->with('rackStage')
            ->with('rackStage.properties')
            ->with('rackStage.properties.category')
            ->with('rackStage.properties.floor')
            ->where('id', $storage_map_rack_item_id)
            ->first();

        if (!empty($rack)) {
            $storage_map_properties_id = null;
            $storage_map_floor_id = null;
            $storage_map_properties_name = null;
            $storage_map_floor_name = null;
            $storage_map_properties_category = null;

            if (!empty($rack->rackstage)) {
                $storage_map_properties_id = $rack->rackstage->storage_map_properties_id;
                if (!empty($rack->rackstage->properties)) {
                    $storage_map_floor_id = $rack->rackstage->properties->storage_map_floor_id;
                    $storage_map_properties_name  = $rack->rackstage->properties->name;

                    if (!empty($rack->rackstage->properties->floor)) {
                        $storage_map_floor_name = $rack->rackstage->properties->floor->code;
                    }
                    if (!empty($rack->rackstage->properties->floor)) {
                        $storage_map_properties_category = $rack->rackstage->properties->category->name;
                    }
                }
            }
            $storageMap = [
                'storage_map_rack_item_id' => $rack->id,
                'storage_map_floor_id' => $storage_map_floor_id,
                'storage_map_floor_name' => $storage_map_floor_name,
                'storage_map_properties_id' => $storage_map_properties_id,
                'storage_map_properties_category' => $storage_map_properties_category,
                'storage_map_properties_name' => $storage_map_properties_name,
                'storage_map_rack_stage_id' => $rack->storage_map_rack_stage_id,
                'storage_map_rack_stage_name' => $rack->rackstage->name,
                'rack_location' => $rack->rackstage->name,
                'value' => $rack->storage_map_rack_stage_id.",".$storage_map_properties_id.",".$storage_map_floor_id.",".$rack->rackstage->name,
                'text' => $storage_map_floor_name."-".$storage_map_properties_name.$rack->rackstage->name,
            ];
            return $storageMap;
        }
        return null;
    }
}

if (! function_exists('getStorageMapByItem')) {
    function getStorageMapByItem($item_code)
    {
        $storageMap = [];
        $model   = new Modules\StorageMap\Entities\StorageMapRackItem;
        $mapRack = $model::query()
            ->with('rackStage')
            ->with('rackStage.properties')
            ->with('rackStage.properties.category')
            ->with('rackStage.properties.floor')
            ->where('item_code', $item_code)
            ->get();

        if ($mapRack->count()>0) {
            foreach ($mapRack as $rack) {
                $storage_map_properties_id = null;
                $storage_map_floor_id = null;
                $storage_map_properties_name = null;
                $storage_map_floor_name = null;
                $storage_map_properties_category = null;

                if (!empty($rack->rackstage)) {
                    $storage_map_properties_id = $rack->rackstage->storage_map_properties_id;
                    if (!empty($rack->rackstage->properties)) {
                        $storage_map_floor_id = $rack->rackstage->properties->storage_map_floor_id;
                        $storage_map_properties_name  = $rack->rackstage->properties->name;

                        if (!empty($rack->rackstage->properties->floor)) {
                            $storage_map_floor_name = $rack->rackstage->properties->floor->code;
                        }
                        if (!empty($rack->rackstage->properties->floor)) {
                            $storage_map_properties_category = $rack->rackstage->properties->category->name;
                        }
                    }
                }
                $storageMap[] = [
                    'storage_map_rack_item_id' => $rack->id,
                    'storage_map_floor_id' => $storage_map_floor_id,
                    'storage_map_floor_name' => $storage_map_floor_name,
                    'storage_map_properties_id' => $storage_map_properties_id,
                    'storage_map_properties_category' => $storage_map_properties_category,
                    'storage_map_properties_name' => $storage_map_properties_name,
                    'storage_map_rack_stage_id' => $rack->storage_map_rack_stage_id,
                    'storage_map_rack_stage_name' => $rack->rackstage->name,
                    'rack_location' => $rack->rackstage->name,
                    'value' => $rack->storage_map_rack_stage_id.",".$storage_map_properties_id.",".$storage_map_floor_id.",".$rack->rackstage->name.",".$storage_map_floor_name."-".$storage_map_properties_name.$rack->rackstage->name,
                    'text' => $storage_map_floor_name."-".$storage_map_properties_name.$rack->rackstage->name,
                ];
            }
            return $storageMap;
        }

        return [];
    }
}

if (! function_exists('getItemLocationByDO')) {
    function getItemLocationByDO($storage_map_rack_item_id)
    {
        $model   = new Modules\StorageMap\Entities\StorageMapRackItem;
        $mapRack = $model::with('rackStage')->where('id', $storage_map_rack_item_id)->first();
        return (!empty($mapRack)) ? $mapRack->rackstage->name : '-' ;
    }
}

if (! function_exists('getItemLocation')) {
    function getItemLocation($item_code)
    {
        $rack_location = null;
        $mapRack = StorageMapRackItem::with('rackStage')->where('item_code', $item_code)->get();
        if ($mapRack->count()>0) {
            foreach ($mapRack as $rack) {
                $rackstage_id = (!empty($rack->rackstage)) ? $rack->rackstage->id : null;
                if (empty($rack_location)) {
                    if (!empty($rackstage_id)) {
                        $rack_location .= getStorageMapProperties($rackstage_id);
                    } else {
                        $rack_location .= getStorageMapPropertiesNotStage($rack->storage_map_properties_id);
                    }
                } else {
                    if (!empty($rackstage_id)) {
                        $rack_location .= "/".getStorageMapProperties($rackstage_id);
                    } else {
                        $rack_location .= "/".getStorageMapPropertiesNotStage($rack->storage_map_properties_id);
                    }
                }
            }
            return $rack_location;
        }

        return "-";
    }
}

if (! function_exists('getStorageMapPropertiesNotStage')) {
    function getStorageMapPropertiesNotStage($storage_map_properties_id)
    {
        $properties = StorageMapProperties::where('id', $storage_map_properties_id)->first();
        if (!empty($properties)) {
            if (!empty($properties)) {
                $properties_name   = $properties->name;
                $storage_map_floor = getStorageMapFloorCode($properties->storage_map_floor_id);
                $storage_map_floor = (!empty($storage_map_floor)) ? $storage_map_floor."-" : null;
            }
            $rack_location = $storage_map_floor.$properties_name;
            return $rack_location;
        }

        return "-";
    }
}

if (! function_exists('getStorageMapProperties')) {
    function getStorageMapProperties($stage_id)
    {
        $rack_location           = null;
        $properties_name         = null;
        $storage_map_floor       = null;
        $stages = StorageMapRackStage::with('properties')->where('id', $stage_id)->first();
        if (!empty($stages)) {
            if (!empty($stages->properties)) {
                $properties_name   = $stages->properties->name;
                $storage_map_floor = getStorageMapFloorCode($stages->properties->storage_map_floor_id);
                $storage_map_floor = (!empty($storage_map_floor)) ? $storage_map_floor."-" : null;
            }
            $rack_location = $storage_map_floor.$properties_name.$stages->name;
            return $rack_location;
        }

        return "-";
    }
}

if (! function_exists('getStorageMapPropertiesName')) {
    function getStorageMapPropertiesName($id)
    {
        $data = StorageMapProperties::where('id', $id)->first();
        return (!empty($data)) ? $data->name : null;
    }
}



if (! function_exists('getStorageMapFloorNameByPropertiesId')) {
    function getStorageMapFloorNameByPropertiesId($id)
    {
        $data = StorageMapProperties::where('id', $id)->first();
        return (!empty($data)) ? getStorageMapFloorName($data->storage_map_floor_id) : null;
    }
}

if (! function_exists('getStorageMapFloorName')) {
    function getStorageMapFloorName($id)
    {
        $data = StorageMapFloor::where('id', $id)->first();
        return (!empty($data)) ? $data->name : null;
    }
}

if (! function_exists('getStorageMapFloorCode')) {
    function getStorageMapFloorCode($id)
    {
        $data = StorageMapFloor::where('id', $id)->first();
        return (!empty($data)) ? $data->code : null;
    }
}

if (! function_exists('getStorageMapCategoryName')) {
    function getStorageMapCategoryName($id)
    {
        $data = StorageMapCategory::where('id', $id)->first();
        return (!empty($data)) ? $data->name : null;
    }
}







