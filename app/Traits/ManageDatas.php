<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

trait ManageDatas
{
    public function createData(Model $model, Request $request, callable $beforeSubmit = null, callable $afterSubmit = null, $route = null, $routeParameter = null)
    {
        $validatedData = $request->validated();
        if (is_callable($beforeSubmit)) {
            // Pass validatedData by reference to the callback
            $response = $beforeSubmit($model, $request, $validatedData);
            if ($response instanceof \Illuminate\Http\JsonResponse) {
                return $response;
            }
        }

        DB::beginTransaction();
        try {
            $createdInstance = $model->create($validatedData); // Use the potentially modified data

            DB::commit();

            if (is_callable($afterSubmit)) {
                $afterSubmit($createdInstance, $request);
            }

            return redirect()->route($route, $routeParameter)->with('success', 'Data berhasil dibuat.');
        } catch (\Throwable $th) {
            DB::rollBack();
            \Log::error($th);
            return redirect()->route($route, $routeParameter)->with('error', 'Gagal membuat data: Terjadi kesalahan pada sistem.');
        }
    }

    public function updateData(Model $model, Request $request, $id, callable $beforeSubmit = null, callable $afterSubmit = null, $route = null, $routeParameter = null)
    {
        $instance = $model->findOrFail($id);

        $validatedData = $request->validated();
        if (is_callable($beforeSubmit)) {
            // Pass validatedData by reference to the callback
            $response = $beforeSubmit($instance, $request, $validatedData);
            if ($response instanceof \Illuminate\Http\JsonResponse) {
                return $response;
            }
        }

        DB::beginTransaction();
        try {
            $instance->update($validatedData); // Use the potentially modified data

            DB::commit();

            if (is_callable($afterSubmit)) {
                $afterSubmit($instance, $request);
            }

            return redirect()->route($route, $routeParameter)->with('success', 'Data berhasil diperbarui.');
        } catch (\Throwable $th) {
            DB::rollBack();
            \Log::error($th);
            return redirect()->route($route, $routeParameter)->with('error', 'Gagal memperbarui data: Terjadi kesalahan pada sistem.');
        }
    }

    public function deleteData(Model $model, $id, callable $beforeSubmit = null, callable $afterSubmit = null, $route = null, $routeParameter = null)
    {
        $instance = $model->findOrFail($id);

        if (is_callable($beforeSubmit)) {
            $response = $beforeSubmit($instance);
            if ($response instanceof \Illuminate\Http\JsonResponse) {
                return $response;
            }
        }

        DB::beginTransaction();
        try {
            $instance->delete();

            DB::commit();

            if (is_callable($afterSubmit)) {
                $afterSubmit($instance); // Instance data before deletion
            }

            return redirect()->route($route, $routeParameter)->with('success', 'Data berhasil dihapus.');
        } catch (\Throwable $th) {
            DB::rollBack();
            \Log::error($th);
            return redirect()->route($route, $routeParameter)->with('error', 'Gagal menghapus data: Terjadi kesalahan pada sistem.');
        }
    }

    public function importData(
        Model $model,
        Request $request,
        callable $processImportCallback,
        callable $beforeImport = null,
        callable $afterImport = null,
        $route = null,
        $routeParameter = null
    ) {
        if (is_callable($beforeImport)) {
            $response = $beforeImport($request);
            if ($response instanceof \Illuminate\Http\RedirectResponse || $response instanceof \Illuminate\Http\JsonResponse) {
                return $response;
            }
        }

        DB::beginTransaction();
        try {
            $importResult = $processImportCallback($model, $request);

            if (!is_array($importResult) || !isset($importResult['count']) || !isset($importResult['errors'])) {
                throw new \InvalidArgumentException("Callback proses impor harus mengembalikan array dengan key 'count' dan 'errors'.");
            }

            if (!empty($importResult['errors'])) {
                DB::rollBack();
                return redirect()->route($route, $routeParameter)
                                 ->with('error', 'Terjadi kesalahan saat impor data.')
                                 ->with('import_errors', $importResult['errors'])
                                 ->withInput();
            }

            DB::commit();

            if (is_callable($afterImport)) {
                $afterImport($importResult, $request);
            }
            return redirect()->route($route, $routeParameter)->with('success', $importResult['count'] . ' data berhasil diimpor.');
        } catch (\Throwable $th) {
            DB::rollBack();
            \Log::error("Kesalahan saat impor: " . $th->getMessage() . "\n" . $th->getTraceAsString());
            return redirect()->route($route, $routeParameter)->with('error', 'Gagal mengimpor data: Terjadi kesalahan pada sistem.');
        }
    }
}
