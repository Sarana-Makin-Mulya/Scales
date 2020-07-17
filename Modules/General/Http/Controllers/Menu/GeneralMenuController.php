<?php

namespace Modules\General\Http\Controllers\Menu;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\ApiSqlServer\Entities\Employee;
use Modules\Auth\Entities\UserGroup;
use Modules\Auth\Entities\UserGroupMenu;
use Modules\Auth\Entities\UserMenu;
use Modules\General\Entities\GeneralMenu;

class GeneralMenuController extends Controller
{

    public function index()
    {
        return view('general::menu.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'level' => 'required',
        ]);

        $generalMenu = new GeneralMenu();
        $parent_id      = $request->parent_id;
        $parent_sub_id  = $request->parent_sub_id;
        $level          = $request->level;
        $level_sub      = $request->level_sub;
        $level          = ($level>1) ? $level_sub : $level;
        $parent         = ($parent_sub_id>0 and $level>1) ? $parent_sub_id : $parent_id;
        $parent         = ($level<=1) ? 0 : $parent;
        $number         = $request->number;
        $number         = ($number<0) ? 0 : $number;

        $save = $generalMenu->create([
            'menu_number' => $number,
            'parent_id' => $parent,
            'name' => $request->name,
            'url' => $request->url,
            'route' => $request->route,
            'icon' => $request->icon,
            'level' => $level,
            'is_active' => 1,
        ]);

        return response()->json([
            'id' => $save->id,
            'changed' => true,
            'act' => 'New',
            'message' => __('Berhasil menambahkan data menu.'),
        ]);
    }

    public function checkUserGroupMenu($menu_id, $user_group_id)
    {
        return UserGroupMenu::where('menu_id', $menu_id)->where('user_group_id', $user_group_id)->first();

    }

    public function checkUserMenu($menu_id, $nik)
    {
        return UserMenu::where('menu_id', $menu_id)->where('employee_nik', $nik)->first();

    }

    public function checkParentMenu($menu_id)
    {
        $GeneralMenu = GeneralMenu::where('id', $menu_id)->first();
        return !empty($GeneralMenu) ? $GeneralMenu->parent_id : 0;
    }

    public function checkMenuNumber($menu_id)
    {
        $GeneralMenu = GeneralMenu::where('id', $menu_id)->first();
        return !empty($GeneralMenu) ? $GeneralMenu->menu_number : 0;
    }

    public function storeMenuGroup(Request $request, $id)
    {
        if (UserGroup::find($id)) {
            $menus = $request->menus;
            $total_menu = count($menus);
            DB::beginTransaction();
            try {
                UserGroupMenu::where('user_group_id', $request->user_group_id)->update(['is_active' => 0]);
                if ($total_menu>0) {
                    for ($i=0; $i<$total_menu; $i++) {
                        $parent  = $this->checkParentMenu($menus[$i]);
                        $parent_already = $this->checkUserGroupMenu($parent, $request->user_group_id);
                        $already = $this->checkUserGroupMenu($menus[$i], $request->user_group_id);

                        if ($parent>0) {
                            if (empty($parent_already)) {
                                UserGroupMenu::create([
                                    'user_group_id' => $request->user_group_id,
                                    'menu_id' => $parent,
                                    'is_active' => 1,
                                ]);
                            } else {
                                UserGroupMenu::where('id', $parent_already->id)->update([
                                    'user_group_id' => $request->user_group_id,
                                    'menu_id' => $parent,
                                    'is_active' => 1,
                                ]);
                            }
                        }

                        if (empty($already)) {
                            UserGroupMenu::create([
                                'user_group_id' => $request->user_group_id,
                                'menu_id' => $menus[$i],
                                'is_active' => 1,
                            ]);
                        } else {
                            UserGroupMenu::where('id', $already->id)->update([
                                'user_group_id' => $request->user_group_id,
                                'menu_id' => $menus[$i],
                                'is_active' => 1,
                            ]);
                        }
                    }
                }

                UserGroupMenu::where('user_group_id', $request->user_group_id)->where('is_active', 0)->delete();
                DB::commit();
                return response()->json([
                 'message' => 'Berhasil memperbaharui data menu.',
                ]);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['message' => $e->getMessage()], 400);
            }
        }

        return response()->json(['message' => 'Data tidak ditemukan'], 404);
    }

    public function storeMenuUser(Request $request, $nik)
    {

        $menus = $request->menus;
        $total_menu = count($menus);
        DB::beginTransaction();
        try {
            UserMenu::where('employee_nik', $nik)->update(['is_active' => 0]);
            if ($total_menu>0) {
                for ($i=0; $i<$total_menu; $i++) {
                    $parent  = $this->checkParentMenu($menus[$i]);
                    $parent_already = $this->checkUserMenu($parent, $nik);
                    $already = $this->checkUserMenu($menus[$i], $nik);

                    if ($parent>0) {
                        if (empty($parent_already)) {
                            UserMenu::create([
                                'user_group_id' => $request->user_group_id,
                                'menu_number' => $this->checkMenuNumber($parent),
                                'employee_nik' => $nik,
                                'menu_id' => $parent,
                                'is_active' => 1,
                            ]);
                        } else {
                            UserMenu::where('id', $parent_already->id)->update([
                                'user_group_id' => $request->user_group_id,
                                'menu_number' => $this->checkMenuNumber($parent),
                                'employee_nik' => $nik,
                                'menu_id' => $parent,
                                'is_active' => 1,
                            ]);
                        }
                    }

                    if (empty($already)) {
                        UserMenu::create([
                            'user_group_id' => $request->user_group_id,
                            'menu_number' => $this->checkMenuNumber($menus[$i]),
                            'employee_nik' => $nik,
                            'menu_id' => $menus[$i],
                            'is_active' => 1,
                        ]);
                    } else {
                        UserMenu::where('id', $already->id)->update([
                            'user_group_id' => $request->user_group_id,
                            'menu_number' => $this->checkMenuNumber($menus[$i]),
                            'employee_nik' => $nik,
                            'menu_id' => $menus[$i],
                            'is_active' => 1,
                        ]);
                    }
                }
            }

            UserMenu::where('employee_nik', $nik)->where('is_active', 0)->delete();
            DB::commit();
            return response()->json([
                'message' => 'Berhasil memperbaharui data menu.',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }


    public function update(Request $request, $id)
    {
        if ($generalMenu = GeneralMenu::find($id)) {
            $request->validate([
                'name' => 'required',
                'level' => 'required',
            ]);

            $parent_id      = $request->parent_id;
            $parent_sub_id  = $request->parent_sub_id;
            $level          = $request->level;
            $level_sub      = $request->level_sub;
            $level          = ($level>1) ? $level_sub : $level;
            $parent         = ($parent_sub_id>0 and $level>1) ? $parent_sub_id : $parent_id;
            $parent         = ($level<=1) ? 0 : $parent;
            $number         = $request->number;
            $number         = ($number<0) ? 0 : $number;

            $generalMenu->update([
                'menu_number' => $number,
                'parent_id' => $parent,
                'name' => $request->name,
                'url' => $request->url,
                'route' => $request->route,
                'icon' => $request->icon,
                'level' => $level,
                'is_active' => 1,
            ]);

            return response()->json([
                'id' => $id,
                'changed' => changeDetection($generalMenu),
                'act' => 'Update',
                'message' => 'Berhasil memperbaharui data menu.',
            ]);
        }

        return response()->json(['message' => 'Data tidak ditemukan'], 404);
    }

    public function updateStatus(Request $request, $id)
    {
        $message = 'Berhasil menon-aktifkan data menu.';

        if ($request->status) {
            $message = 'Berhasil mengaktifkan data menu.';
        }

        if ($generalMenu = GeneralMenu::find($id)) {
            $generalMenu->update([ 'is_active' => $request->status]);
            return response()->json([ 'message' => $message ]);
        }

        return response()->json(['message' => 'Data tidak ditemukan']);
    }

}
