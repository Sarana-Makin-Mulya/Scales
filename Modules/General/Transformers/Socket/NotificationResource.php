<?php

namespace Modules\General\Transformers\Socket;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\Resource;

class NotificationResource extends Resource
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
            'from' => $this->from,
            'from_name' => ucwords(str_replace('-',' ',$this->from)),
            'issued_by' => $this->issued_by,
            'issued_by_name' => getEmployeeFullName($this->issued_by),
            'to' => $this->to,
            'transaction_type' => $this->transaction_type,
            'transaction_code' => $this->transaction_code,
            'transaction_id' => $this->transaction_id,
            'description' => $this->message,
            'info' => Carbon::createFromFormat('Y-m-d H:i:s',$this->created_at)->diffForHumans(),
            'url' => $this->url,
            'status' => $this->status,
            'read_at' => $this->read_at,
            'date' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];
    }
}
