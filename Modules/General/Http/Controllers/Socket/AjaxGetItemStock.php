<?php

namespace Modules\General\Http\Controllers\Socket;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\General\Entities\Notification;
use Modules\General\Transformers\Socket\NotificationResource;
use Modules\Stock\Entities\Item;

class AjaxGetItemStock extends Controller
{
    public function __invoke(Request $request)
    {
        $item = Item::where('code', 'ATK0001')->first();
        $min_stock      = $item->min_stock;
        $current_stock  = getItemStock($item->code);
        if ($current_stock >= $min_stock) {
            $status = 0;
        } else {
            $status = 0;
        }

        return ['status' => $status];
    }
}
