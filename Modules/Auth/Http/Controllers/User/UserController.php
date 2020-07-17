<?php

namespace Modules\Auth\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Modules\Auth\Entities\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('auth::user.index');
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
            'employee_nik' => 'required',
            'user_group_id' => 'required',
            'username' => 'required|unique:users',
            'password' => 'required',
        ]);

        $data = [
            'employee_nik' => $request->input('employee_nik'),
            'user_group_id' => $request->input('user_group_id'),
            'username' => $request->input('username'),
            'password' => Hash::make($request->input('password')),
        ];

        $user = new User();
        $save = $user->create($data);


        return response()->json([
            'id' => $save->id,
            'changed' => true,
            'act' => 'New',
            'message' => __('Berhasil menambahkan data user baru.'),
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
        if ($user = User::find($id)) {
            $request->validate([
                'employee_nik' => 'required',
                'user_group_id' => 'required',
                'username' => 'required|unique:users,id,'.$id,
            ]);

            $data = [
                'employee_nik' => $request->input('employee_nik'),
                'user_group_id' => $request->input('user_group_id'),
                'username' => $request->input('username'),
            ];

            if (!empty($request->input('password'))) {
             $data = array_merge($data, ['password' => Hash::make($request->input('password'))]);
            }

            $user->update($data);

            return response()->json([
                'id' => $id,
                'changed' => changeDetection($user),
                'act' => 'Update',
                'message' => __('Berhasil memperbaharui data user.'),
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
        $message = 'Berhasil mem-banned user '.$request->username;

        if ($request->status==1) {
            $message = 'Berhasil mengaktifkan user '.$request->username;
        }

        if ($user = User::find($id)) {
            $user->update([ 'account_status' => $request->status]);
            return response()->json([ 'message' => $message ]);
        }

        return response()->json(['message' => 'Data tidak ditemukan']);
    }
}
