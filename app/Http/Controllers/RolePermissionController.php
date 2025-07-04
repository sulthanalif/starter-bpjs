<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;

class RolePermissionController extends Controller
{

    public function index()
    {
        $roles = Role::with('permissions:id,name')->get();
        $permissions = Permission::select('id', 'name')->get();


        return view('back-end.role-permission.index', compact('roles', 'permissions'));
    }

    public function show($role): JsonResponse
    {
        // Periksa apakah instance Role benar-benar ada di database
        $role = Role::where('id', $role)->first();
        if ($role->exists) {
            // Ambil permissions yang terkait dengan role ini, pilih kolom id dan name
            $permissions = $role->permissions()->select('id', 'name')->get();
            return response()->json([
                'status'      => true,
                'data'        => $role, // Akan berisi data role lengkap
                'permissions' => $permissions,
                'message'     => 'Data berhasil diambil.'
            ], JsonResponse::HTTP_OK);
        } else {
            // Jika $role->exists false, berarti role dengan ID yang diminta tidak ditemukan
            return response()->json([
                'message' => 'Role tidak ditemukan.',
                'data'    => null, // Atau bisa juga $role (yang akan menjadi {"guard_name":"web"}) atau []
                'status'  => false,
            ], JsonResponse::HTTP_NOT_FOUND);
        }
    }

    public function getPermissions(): JsonResponse
    {
        $permissions = Permission::select('id', 'name')->get();
        if (!empty($permissions)) {
            return response()->json([
                'status' => true,
                'data' => $permissions,
                'message' => 'Data berhasil diambil.'
            ], JsonResponse::HTTP_OK);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan.',
                'data' => []
            ], JsonResponse::HTTP_NOT_FOUND);
        }
    }

    public function permissionStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:permissions,name',
        ]);

        DB::beginTransaction();
        try {
            $permission = Permission::create(['name' => $request->name, 'guard_name' => 'web']);

            DB::commit();

            $role = Role::where('name', 'super-admin')->first();

            $role->givePermissionTo($permission->name);

            return redirect()->route('role-permission.index')->with('success', 'Permission berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect()->route('role-permission.index')->with('error', 'Terjadi kesalahan saat menambahkan permission.');
        }
    }

    public function permissionDestroy($id)
    {
        $permission = Permission::find($id);
        if ($permission) {
            $permission->delete();
            return redirect()->route('role-permission.index')->with('success', 'Permission berhasil dihapus.');
        } else {
            return redirect()->route('role-permission.index')->with('error', 'Permission tidak ditemukan.');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'integer|exists:permissions,id',
        ]);

        DB::beginTransaction();
        try {
            $role = Role::create(['name' => $request->name, 'guard_name' => 'web']);

            if ($request->has('permissions') && !empty($request->permissions)) {
                $permissions = Permission::whereIn('id', $request->permissions)->get();
                $role->syncPermissions($permissions);
            }

            DB::commit();

            return redirect()->route('role-permission.index')->with('success', 'Role berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect()->route('role-permission.index')->with('error', 'Terjadi kesalahan saat menambahkan role.');
        }
    }

    public function update(Request $request, $roleId)
    {

        // dd($request->all());
        $request->validate([
            'name_edit' => 'required|string',
            'permissions' => 'nullable|array',
            'permissions.*' => 'integer|exists:permissions,id',
        ]);

        $role = Role::find($roleId);

        if (!$role) {
            return response()->json(['status' => false, 'message' => 'Role tidak ditemukan.'], JsonResponse::HTTP_NOT_FOUND);
        }

        DB::beginTransaction();
        try {
            $role->name = $request->name_edit;
            $role->save();

            // Get permission IDs from the request, default to an empty array if not provided
            $permissionIds = $request->input('permissions', []);

            if (count($permissionIds) > 0) {
                // Fetch the Permission models corresponding to the IDs and the role's guard
                $permissionsToSync = Permission::whereIn('id', $permissionIds)
                                               ->where('guard_name', $role->guard_name) // Ensure permissions match role's guard
                                               ->get();
                $role->syncPermissions($permissionsToSync);
            } else {
                $role->syncPermissions([]); // If no permissions are sent, remove all permissions from the role
            }

            DB::commit();

           return redirect()->route('role-permission.index')->with('success', 'Role berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e);
            return redirect()->route('role-permission.index')->with('error', 'Terjadi kesalahan saat memperbarui role.');
        }
    }

    public function destroy($roleId)
    {
        $role = Role::find($roleId);
        if ($role) {
            $role->delete();
            return redirect()->route('role-permission.index')->with('success', 'Role berhasil dihapus.');
        } else {
            return redirect()->route('role-permission.index')->with('error', 'Role tidak ditemukan.');
        }
    }
}
