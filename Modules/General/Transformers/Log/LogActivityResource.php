<?php

namespace Modules\General\Transformers\Log;

use Illuminate\Http\Resources\Json\Resource;

class LogActivityResource extends Resource
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
            'log_name' => $this->log_name,
            'description' => $this->description,
            'subject_id' => $this->subject_id,
            'subject_type' => $this->subject_type,
            'causer_id' => $this->causer_id,
            'causer_level' => getAuthLevelByUserId($this->causer_id),
            'causer_nik' => getAuthEmployeeNik($this->causer_id),
            'causer_name' => getEmployeeFullName(getAuthEmployeeNik($this->causer_id)),
            'causer_type' => $this->causer_type,
            'properties' => setProperties($this->subject_type, $this->properties),
            'prop' => $this->properties,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    public function getProperties($properties)
    {
        $array      = [];
        $hide_field = ['info', 'slug', 'detail','old_code'];
        foreach ($properties as $key => $data) {
            foreach ($data as $field => $value) {
                // Change Value
                $value = ($field=="is_priority") ? (($value==1) ? 'prioritas' : 'normal') : $value;
                $value = ($field=="borrowable") ? (($value==1) ? 'ya' : 'tidak') : $value;
                $value = ($field=="item_brand_id") ? getBrandName($value) : $value;

                // Change Filed
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
                $field = ($field=="max_stock") ? "Maksimum stok" : $field;
                $field = ($field=="min_stock") ? "Minimum stok" : $field;

               if (array_search($field, $hide_field) !== false) {
                    // Delete array
               } else {
                  $array[$key][$field] = $value;
               }
            }
        }

        return $array;
    }
}
