<?php

namespace Modules\Supplier\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Modules\Supplier\Entities\SupplierAddress;
use Modules\Supplier\Entities\SupplierContact;

class GetSupplierInfoResource extends Resource
{

    public function toArray($request)
    {
        return [
            'code' => $this->code,
            'name' => $this->name,
            'pic' => $this->pic,
            'address' => $this->getAddress($this->code, 1,'full','text'),
            'phone' => $this->getContact($this->code, 1),
            'saldo' => getSupplierSaldo($this->code, $this->payment_code),
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
