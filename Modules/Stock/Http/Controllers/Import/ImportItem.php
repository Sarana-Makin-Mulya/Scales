<?php

namespace Modules\Stock\Http\Controllers\Import;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Stock\Entities\Item;

class ImportItem extends Controller
{
    public function __invoke(Request $request)
    {
            $items = Item::get();
            foreach ($items as $item) {
                $alias  = !empty($item->nickname) ? "/".$item->nickname : "";
                $detail = trim($item->name.$alias." ".$item->type." ".$item->size." ".$item->color." ".getBrandName($item->item_brand_id));
                $info = $item->code." ".trim($item->name.$alias." ".$item->type." ".$item->size." ".$item->color." ".getBrandName($item->item_brand_id)." ".getCategoryName($item->item_category_id));
                echo $item->code."->".$detail." -> ".$info."<br>";

                if ($item->item_category_id==16) {
                 Item::where('code', $item->code)->update(['item_category_id' => 8, 'detail' => $detail, 'info' => $info]);
                } else {
                    Item::where('code', $item->code)->update(['detail' => trimed($detail), 'info' => trimed($info)]);
                }
            }
    }
}
