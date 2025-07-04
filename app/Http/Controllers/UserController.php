<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\UserRequest;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        $roles = Role::all();

        return view('back-end.user.index', compact('users', 'roles'));
    }

    public function show(User $user): JsonResponse
    {
        if(!empty($user)){
            $roles = Role::get();
            $idRole = [];
            if(!empty($user->roles)){
                foreach($user->roles as $user_role){
                    $idRole[] = $user_role->id;
                }
            }
            if(!empty($roles)){
                foreach($roles as $key => $role){
                    $roles[$key]->selected = '';
                    if(in_array($role->id, $idRole)){
                        $roles[$key]->selected = 'selected';
                    }
                }
            }


            return response()->json([
                'status'  => true,
                'data'    => $user,
                'roles'   => $roles,
                'message' => 'Data berhasil diambil.',
            ], JsonResponse::HTTP_OK);
        }else{
            return response()->json([
                'message' => 'Data Tidak Ada.',
                'data'    => [],
                'roles'   => [],
                'status' => false,
            ], JsonResponse::HTTP_NOT_FOUND);
        }
    }

    public function store(UserRequest $request)
    {
        return $this->createData(model: new User(), request: $request, route: 'user.index',
            beforeSubmit: function ($model, $request, &$validatedData) { // Updated signature to receive validatedData by reference
                if(!empty($validatedData['password'])) {
                    $validatedData['password'] = bcrypt($validatedData['password']);
                } else {
                    unset($validatedData['password']); // Remove password if null/empty
                }
            },
            afterSubmit: function ($createdInstance, $request) {
                $createdInstance->syncRoles(Role::where('id', $request->role_id)->first()->name);
            }
        );
    }

    public function update(UserRequest $request, $id)
    {
        // Note: The updateData trait method's beforeSubmit signature needs to be updated
        // to receive the validated data by reference to handle password correctly.
        return $this->updateData(new User(), $request, $id, route: 'user.index',
            beforeSubmit: function ($instance, $request, &$validatedData) { // Updated signature
                if (isset($validatedData['password']) && !empty($validatedData['password'])) {
                    $validatedData['password'] = bcrypt($validatedData['password']);
                } else {
                    unset($validatedData['password']); // Don't update password if not provided
                }
            },
            afterSubmit: function ($instance, $request) { // This callback is fine
                $instance->syncRoles(Role::where('id', $request->role_id)->first()->name);
        });
    }

    public function destroy($id)
    {
        return $this->deleteData(new User(), $id, route: 'user.index');
    }

}
