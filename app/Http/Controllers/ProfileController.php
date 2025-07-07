<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index()
    {

        return view('back-end.profile');
    }

    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $user = Auth::user();
            $user->name = $request->name;
            $user->save();

            DB::commit();
            return redirect()->back()->with('success', 'Profile berhasil diperbarui.');
        } catch (\Throwable $th) {
            Log::error($th);
            DB::rollBack();

            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui profile.');
        }
    }

    public function updatePassword(Request $request)
    {
        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Password saat ini salah.'])->withInput();
        }

        $validator = Validator::make($request->all(), [
            'current_password' => 'required|min:3',
            'new_password' => 'required|min:3|same:new_password_confirmation',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $user = Auth::user();
            $user->password = $request->new_password;
            $user->save();

            DB::commit();
            return redirect()->back()->with('success', 'Password berhasil diperbarui.');
        } catch (\Throwable $th) {
            Log::error($th);
            DB::rollBack();

            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui password.');
        }
    }

}
