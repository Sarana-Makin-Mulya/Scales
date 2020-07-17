<?php

namespace Modules\Supplier\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Modules\Supplier\Entities\Supplier;
use Modules\Supplier\Entities\SupplierAddress;
use Modules\Supplier\Entities\SupplierCategory;
use Modules\Supplier\Entities\SupplierContact;
use Modules\Supplier\Transformers\SupplierCategory\SupplierCategoryResource;

class SupplierResource extends Resource
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
            'code' => $this->code,
            'name' => $this->name,
            'pic' => $this->pic,
            'supplier_category_id' => $this->supplier_category_id,
            'supplier_category' => new SupplierCategoryResource($this->supplierCategory),
            'taxable' => $this->taxable,
            'supplier_type' => $this->supplier_type,
            'taxpayer_registration_number' => $this->taxpayer_registration_number,
            'id_card_number' => $this->id_card_number,
            'sppkb_number' => $this->sppkb_number,
            'skt_number' => $this->skt_number,
            'full_address' => $this->getAddress($this->code, 1,'full','text'),
            'address' => $this->getAddress($this->code, 1,'address','text'),
            'province' => $this->getAddress($this->code, 1,'province','id'),
            'city' => $this->getAddress($this->code, 1,'city','id'),
            'zipcode' => $this->getAddress($this->code, 1,'zipcode','text'),
            'full_tax_address' => $this->getAddress($this->code, 2,'full','text'),
            'tax_address' => $this->getAddress($this->code, 2,'address','text'),
            'tax_province' => $this->getAddress($this->code, 2,'province','id'),
            'tax_city' => $this->getAddress($this->code, 2,'city','id'),
            'tax_zipcode' => $this->getAddress($this->code, 2,'zipcode','text'),
            'phone' => $this->getContact($this->code, 1),
            'fax' => $this->getContact($this->code, 2),
            'email' => $this->getContact($this->code, 3),
            'status' => (boolean) $this->is_active,
            'url_edit' => route('supplier.update', [$this->code]),
            'url_status_update' => route('supplier.update.status', [$this->code]),
            'url_delete' => route('ajax.supplier.destroy', [$this->code]),
        ];
    }

    public function getAddress($code, $type, $field, $value)
    {
        $addresses = SupplierAddress::query()
            ->where('supplier_code',$code)
            ->where('address_type',$type)
            ->first();

        if (!empty($addresses)) {
            $address_id = explode(',',$addresses->full_location_ids);
            $count_add  = count($address_id);

            if ($field=="address") {
                return $addresses->full_address;
            } elseif ($field=="province") {
                return ($value=="text") ? $addresses->province : $address_id[0];
            } elseif ($field=="city") {
                return ($value=="text") ? $addresses->regency : ($count_add>1) ? $address_id[1] : 0 ;
            } elseif ($field=="zipcode") {
                return $addresses->zipcode;
            } else {
                $full_address = null;
                $full_address = $addresses->full_address;
                $full_address .= (!empty($full_address) && !empty($addresses->village)) ? ", ".$addresses->village : "";
                $full_address .= (!empty($full_address) && !empty($addresses->district)) ? ", ".$addresses->district : "";
                $full_address .= (!empty($full_address) && !empty($addresses->regency)) ? ", ".$addresses->regency : "";
                $full_address .= (!empty($full_address) && !empty($addresses->province)) ? ", ".$addresses->province : "";
                $full_address .= (!empty($full_address) && !empty($addresses->zipcode)) ? ", ".$addresses->zipcode : "";
                return $full_address;
            }
        } else {
            return "";
        }
    }

    public function getContact($code, $type)
    {
        $contacts = SupplierContact::query()
            ->where('supplier_code',$code)
            ->where('general_contact_type_id',$type)
            ->first();

        return (!empty($contacts)) ? $contacts->contact : "";
    }
}
