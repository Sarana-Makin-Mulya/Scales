<?php

namespace Modules\Supplier\Http\Controllers\Supplier;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Modules\Regional\Entities\Province;
use Modules\Regional\Entities\Regency;
use Modules\Supplier\Entities\Supplier;
use Modules\Supplier\Entities\SupplierAddress;
use Modules\Supplier\Entities\SupplierContact;
use Modules\Supplier\Transformers\GetSupplierInfoResource;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('supplier::supplier.index');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $last_row = Supplier::count()+1;
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

         // Supplier identity
         $supplier = [
            'code' => $this->generateSupplierCode($last_row),
            'name' => $request->input('name'),
            'pic' => $request->input('pic'),
            'supplier_category_id' => $request->input('supplier_category'),
            'taxable' => $request->input('tax'),
            'supplier_type' => 2,
            'taxpayer_registration_number' => $request->input('npwp'),
            'id_card_number' => '-',
            'sppkb_number' =>  $request->input('sppkp'),
            'skt_number' => '-',
            'is_active'=> 1,
        ];

        // Supplier Contacts
        if (!empty($request->input('phone'))) {
            $contacts[] = [
                'general_contact_type_id' => 1,
                'contact' => $request->input('phone'),
                'note' => '-',
                'is_primary' => 1,
            ];
        }

        if (!empty($request->input('fax'))) {
            $contacts[] = [
                'general_contact_type_id' => 2,
                'contact' => $request->input('fax'),
                'note' => '-',
                'is_primary' => 0,
            ];
        }

        if (!empty($request->input('email'))) {
            $contacts[] = [
                'general_contact_type_id' => 3,
                'contact' => $request->input('email'),
                'note' => '-',
                'is_primary' => 0,
            ];
        }

        // Supplier Addresses
        $address = [
            'full_address' => $request->input('address'),
            'province' => $this->getProvinceName($request->input('province')),
            'regency' =>$this->getRegencyName($request->input('city')),
            'district' => 0,
            'village' => 0,
            'zipcode' => $request->input('zipcode'),
            'full_location_ids' => $request->input('province').",".$request->input('city').",0,0",
        ];

        $addresses[] = Arr::add(Arr::add($address,'address_type','1'),'is_primary','1');
        if (!empty($request->input('npwp')) and $request->input('tax')==1) {
            if ($request->status_tax_address == 1) {
                $addresses[] = Arr::add(Arr::add($address,'address_type','2'),'is_primary','0');
            } else {
                $addresses[] = [
                    'full_address' => $request->input('tax_address'),
                    'province' => $this->getProvinceName($request->input('tax_province')),
                    'regency' =>$this->getRegencyName($request->input('tax_city')),
                    'district' => 0,
                    'village' => 0,
                    'zipcode' => $request->input('tax_zipcode'),
                    'full_location_ids' => $request->input('tax_province').",".$request->input('tax_city').",0,0",
                    'address_type'=>'2',
                    'is_primary'=>'0',
                ];
            }
        }

        DB::beginTransaction();
        try {
            $supplier = Supplier::create($supplier);
            foreach ($contacts as $contact) {
                $contact = Arr::add($contact,'supplier_code',$supplier->code);
                SupplierContact::create($contact);
            }

            foreach ($addresses as $address) {
                $address = Arr::add($address,'supplier_code',$supplier->code);
                SupplierAddress::create($address);
            }

            DB::commit();

            return response()->json([
                'code' => $supplier->code,
                'changed' => true,
                'act' => "New",
                'message' => __('Berhasil menambahkan data supplier baru.'),
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function getProvinceName($id)
    {
        $province = Province::where('id',$id)->first();
        return !empty($province) ? $province->name : $id;
    }

    public function getRegencyName($id)
    {
        $regency = Regency::where('id',$id)->first();
        return !empty($regency) ? $regency->name : $id;
    }



    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $code)
    {
        $supplier = Supplier::find($code);

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

       $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Supplier identity
        $data = [
            'name' => $request->input('name'),
            'pic' => $request->input('pic'),
            'supplier_category_id' => $request->input('supplier_category'),
            'taxable' => $request->input('tax'),
            'supplier_type' => 2,
            'taxpayer_registration_number' => $request->input('npwp'),
            'id_card_number' => '-',
            'sppkb_number' =>  $request->input('sppkp'),
            'skt_number' => '-',
            'is_active'=> 1,
        ];

        // Supplier Contacts
        if (!empty($request->input('phone'))) {
            $contacts[] = [
                'general_contact_type_id' => 1,
                'contact' => $request->input('phone'),
                'note' => '-',
                'is_primary' => 1,
            ];
        }

        if (!empty($request->input('fax'))) {
            $contacts[] = [
                'general_contact_type_id' => 2,
                'contact' => $request->input('fax'),
                'note' => '-',
                'is_primary' => 0,
            ];
        }

        if (!empty($request->input('email'))) {
            $contacts[] = [
                'general_contact_type_id' => 3,
                'contact' => $request->input('email'),
                'note' => '-',
                'is_primary' => 0,
            ];
        }

        // Supplier Addresses
        $address = [
            'full_address' => $request->input('address'),
            'province' => $this->getProvinceName($request->input('province')),
            'regency' =>$this->getRegencyName($request->input('city')),
            'district' => 0,
            'village' => 0,
            'zipcode' => $request->input('zipcode'),
            'full_location_ids' => $request->input('province').",".$request->input('city').",0,0",
        ];

        $addresses[] = Arr::add(Arr::add($address,'address_type','1'),'is_primary','1');

        if (!empty($request->input('npwp')) and $request->input('tax')) {
            if ($request->status_tax_address == 1) {
                $addresses[] = Arr::add(Arr::add($address,'address_type','2'),'is_primary','0');
            } else {
                $addresses[] = [
                    'full_address' => $request->input('tax_address'),
                    'province' => $this->getProvinceName($request->input('tax_province')),
                    'regency' =>$this->getRegencyName($request->input('tax_city')),
                    'district' => 0,
                    'village' => 0,
                    'zipcode' => $request->input('tax_zipcode'),
                    'full_location_ids' => $request->input('tax_province').",".$request->input('tax_city').",0,0",
                    'address_type'=>'2',
                    'is_primary'=>'0',
                ];
            }
        }

        DB::beginTransaction();
        try {
            $supplier->update($data);
            foreach ($contacts as $contact) {
                $contact = Arr::add($contact,'supplier_code',$supplier->code);
                $contact_id = $this->getContactId($supplier->code, $contact['general_contact_type_id']);
                if ($contact_id>0) {
                    SupplierContact::where('id', $contact_id)->update($contact);
                } else {
                    SupplierContact::create($contact);
                }
            }

            foreach ($addresses as $address) {
                $address = Arr::add($address,'supplier_code',$supplier->code);
                $address_id = $this->getAddressId($supplier->code, $address['address_type']);
                if ($address_id>0) {
                    SupplierAddress::where('id', $address_id)->update($address);
                } else {
                    SupplierAddress::create($address);
                }
            }

            DB::commit();

            return response()->json([
                'code' => $code,
                'changed' => changeDetection($supplier),
                'act' => "Update",
                'message' => __('Berhasil mengubah data supplier.'),
                ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function getContactId($code, $type)
    {
        $contact = SupplierContact::query()
                ->where('supplier_code',$code)
                ->where('general_contact_type_id',$type)
                ->first();
        return (!empty($contact)) ? $contact->id : '';
    }

    public function getAddressId($code, $type)
    {
        $address = SupplierAddress::query()
                ->where('supplier_code',$code)
                ->where('address_type',$type)
                ->first();
        return (!empty($address)) ? $address->id : '';
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function updateStatus(Request $request, $id)
    {
        $message = 'Berhasil menon-aktifkan data supplier.';

        if ($request->status) {
            $message = 'Berhasil mengaktifkan data supplier.';
        }

        if ($supplier = Supplier::find($id)) {
            $supplier->update([ 'is_active' => $request->status]);
            return response()->json([ 'message' => $message ]);
        }

        return response()->json(['message' => 'Data tidak ditemukan']);
    }

    public function generateSupplierCode($number)
    {
        $item_code = "SP".str_pad($number ,4,0,STR_PAD_LEFT);

        if (Supplier::where('code', $item_code)->exists()) {
            $number++;
            return $this->generateSupplierCode($number);
        }

        return $item_code;
    }

    public function getSupplierInfo(Request $request)
    {
        $payment_code = (!empty($request->payment_code)) ? $request->payment_code : null;
        $supplier = Supplier::query()
                    ->select('*')
                    ->addSelect(DB::raw("'$payment_code' as payment_code"))
                    ->where('code', $request->code)->first();
        if (!empty($supplier)) {
            return new GetSupplierInfoResource($supplier);
        }

        return ['code' => null, 'pic' => null, 'phone' => null, 'address' => null, 'saldo' => null];
    }
}
