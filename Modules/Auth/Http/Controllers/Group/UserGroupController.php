<?php

namespace Modules\Auth\Http\Controllers\Group;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Routing\Controller;
use Modules\Auth\Entities\UserGroup;

class UserGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('auth::group.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('auth::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $data = [
            'name' => $request->input('name'),
            'slug' => Str::slug($request->input('name'), '-'),
            'description' => $request->input('description'),
        ];

        $userGroup = new UserGroup();
        $save = $userGroup->create($data);


        return response()->json([
            'id' => $save->id,
            'changed' => true,
            'act' => 'New',
            'message' => __('Berhasil menambahkan data user group baru.'),
            ]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('auth::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('auth::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        if ($userGroup = UserGroup::find($id)) {
            $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $userGroup->update([
                'name' => $request->name,
                'slug' => Str::slug($request->input('name'), '-'),
                'description' => $request->input('description'),
            ]);

            return response()->json([
                'id' => $id,
                'changed' => changeDetection($userGroup),
                'act' => 'Update',
                'message' => 'Berhasil memperbaharui data user group.',
            ]);
        }
        return response()->json(['message' => 'Data tidak ditemukan'], 404);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function updateStatus(Request $request, $id)
    {
        $message = 'Berhasil menon-aktifkan data user group.';

        if ($request->status) {
            $message = 'Berhasil mengaktifkan data user group.';
        }

        if ($userGroup = UserGroup::find($id)) {
            $userGroup->update([ 'is_active' => $request->status ]);
            return response()->json([ 'message' => $message ]);
        }

        return response()->json(['message' => 'Data tidak ditemukan']);
    }
}
