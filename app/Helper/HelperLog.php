<?php

if (! function_exists('setProperties')) {
    function setProperties($model, $properties)
    {
        switch ($model) {
            case "Modules\Stock\Entities\Item":
                $data = setTableItems($properties);
                break;
            case "Modules\Stock\Entities\Brand":
                $data = setTableItemBrands($properties);
                break;
            case "Modules\General\Entities\Unit":
                $data = setTableUnits($properties);
                break;
            case "Modules\StorageMap\Entities\StorageMapRackItem":
                $data = setTableStorageMapRackItem($properties);
                break;
            case "Modules\General\Entities\Currency":
                $data = setTableCurrencies($properties);
                break;
            default:
                $data = $properties;
                break;
        }

        return $data;
    }
}

// Table storage_map_rack_items
if (! function_exists('setTableStorageMapRackItem')) {
    function setTableStorageMapRackItem($properties)
    {
        $array      = [];
        $hide_field = ['delivery_order_item_id'];
        foreach ($properties as $key => $data) {
            foreach ($data as $field => $value) {
                // Change Value
                $value = ($field=="storage_map_properties_id") ? getStorageMapPropertiesName($value) : $value;
                $value = ($field=="storage_map_rack_stage_id") ? getStorageMapProperties($value) : $value;
                $value = ($field=="item_code") ? getItemDetail($value) : $value;
                $value = ($field=="is_active") ? (($value==1) ? 'aktif' : 'non aktif') : $value;

                // Change Filed
                $field = ($field=="storage_map_properties_id") ? "Properti" : $field;
                $field = ($field=="storage_map_rack_stage_id") ? "Tingkatan" : $field;
                $field = ($field=="item_code") ? "Barang" : $field;
                $field = ($field=="description") ? "Deskripsi" : $field;
                $field = ($field=="is_active") ? "Status" : $field;

                // Set Array
                $array = setArray($key, $field, $value, $hide_field, $array);
            }
        }

        return $array;
    }
}

// Table items
if (! function_exists('setTableItems')) {
    function setTableItems($properties)
    {
        $array      = [];
        $hide_field = ['info', 'slug', 'detail','old_code', 'item_measure_id', 'current_stock', 'status_stock', 'stock_app_old_id', 'create_by'];
        foreach ($properties as $key => $data) {
            foreach ($data as $field => $value) {
                // Change Value
                $value = ($field=="is_active") ? (($value==1) ? 'aktif' : 'non aktif') : $value;
                $value = ($field=="is_priority") ? (($value==1) ? 'prioritas' : 'normal') : $value;
                $value = ($field=="borrowable") ? (($value==1) ? 'ya' : 'tidak') : $value;
                $value = ($field=="item_brand_id") ? getBrandName($value) : $value;

                // Change Filed
                $field = ($field=="code") ? "Kode" : $field;
                $field = ($field=="item_category_id") ? "Kategori" : $field;
                $field = ($field=="item_brand_id") ? "Merek" : $field;
                $field = ($field=="name") ? "Nama" : $field;
                $field = ($field=="nickname") ? "Alias" : $field;
                $field = ($field=="type") ? "Tipe" : $field;
                $field = ($field=="size") ? "Ukuran" : $field;
                $field = ($field=="color") ? "Warna" : $field;
                $field = ($field=="description") ? "Deskripsi" : $field;
                $field = ($field=="is_priority") ? "Prioritas" : $field;
                $field = ($field=="borrowable") ? "Pinjam" : $field;
                $field = ($field=="max_stock") ? "Mak stok" : $field;
                $field = ($field=="min_stock") ? "Min stok" : $field;
                $field = ($field=="is_active") ? "Status" : $field;

                // Set Array
                $array = setArray($key, $field, $value, $hide_field, $array);
            }
        }

        return $array;
    }
}

// Table items_brands
if (! function_exists('setTableItemBrands')) {
    function setTableItemBrands($properties)
    {
        $array      = [];
        $hide_field = ['slug'];
        foreach ($properties as $key => $data) {
            foreach ($data as $field => $value) {
                // Change Value
                $value = ($field=="is_active") ? (($value==1) ? 'aktif' : 'non aktif') : $value;

                // Change Filed
                $field = ($field=="name") ? "Nama" : $field;
                $field = ($field=="is_active") ? "Status" : $field;

                // Set Array
                $array = setArray($key, $field, $value, $hide_field, $array);
            }
        }

        return $array;
    }
}

// Table general_units
if (! function_exists('setTableUnits')) {
    function setTableUnits($properties)
    {
        $array      = [];
        $hide_field = ['measure_code'];
        foreach ($properties as $key => $data) {
            foreach ($data as $field => $value) {
                // Change Value
                $value = ($field=="is_active") ? (($value==1) ? 'aktif' : 'non aktif') : $value;

                // Change Filed
                $field = ($field=="name") ? "Nama" : $field;
                $field = ($field=="symbol") ? "Simbol" : $field;
                $field = ($field=="description") ? "Deskripsi" : $field;
                $field = ($field=="is_active") ? "Status" : $field;

                // Set Array
                $array = setArray($key, $field, $value, $hide_field, $array);
            }
        }

        return $array;
    }
}

// Table general_units
    if (! function_exists('setTableCurrencies')) {
        function setTableCurrencies($properties)
        {
            $array      = [];
            $hide_field = [];
            foreach ($properties as $key => $data) {
                foreach ($data as $field => $value) {
                    // Change Value
                    $value = ($field=="is_active") ? (($value==1) ? 'aktif' : 'non aktif') : $value;

                    // Change Filed
                    $field = ($field=="name") ? "Nama" : $field;
                    $field = ($field=="symbol") ? "Simbol" : $field;
                    $field = ($field=="is_active") ? "Status" : $field;

                    // Set Array
                    $array = setArray($key, $field, $value, $hide_field, $array);
                }
            }

            return $array;
        }
    }

// Set Array Delete
if (! function_exists('setArray')) {
    function setArray($key, $field, $value, $hide_field, $array)
    {
        if (array_search($field, $hide_field) !== false) {
            // Delete array
        } else {
          $array[$key][$field] = ucwords($value);
        }

        return $array;
    }
}
