<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Support\Collection;
use App\Models\Employee;
use App\Models\Position;
use Illuminate\Support\Facades\Log;

trait GetDatas
{
    public function getHeaderEmployee(User $userModel): ?User // Mengembalikan User atau null
    {
        // Eager load relasi untuk efisiensi
        $currentUserEmployee = $userModel->loadMissing('employee.position', 'employee.subDepartment.department')->employee;
        $headUser = null;

        try {
            if (
                $currentUserEmployee &&
                $currentUserEmployee->position && // Pastikan posisi ada
                isset($currentUserEmployee->position->level_order) && // Pastikan level_order ada di posisi
                $currentUserEmployee->subDepartment && // Pastikan subDepartment ada
                $currentUserEmployee->subDepartment->department_id // Pastikan department_id ada di subDepartment
            ) {
                $departmentId = $currentUserEmployee->subDepartment->department_id;
                $currentPositionLevelOrder = $currentUserEmployee->position->level_order;

                // Cari posisi yang level_order-nya satu tingkat di atas (lebih kecil) dari posisi saat ini
                // dan merupakan level_order terdekat (paling besar yang lebih kecil dari current)
                $superiorPosition = Position::where('level_order', '<', $currentPositionLevelOrder)
                                            ->orderBy('level_order', 'desc')
                                            ->first();

                if ($superiorPosition) {
                    // Cari employee di departemen yang sama dengan posisi superior tersebut
                    $headEmployee = Employee::whereHas('subDepartment', function ($query) use ($departmentId) {
                                                $query->where('department_id', $departmentId);
                                            })
                                            ->where('position_id', $superiorPosition->id)
                                            ->with('user') // Eager load user
                                            ->first();

                    if ($headEmployee && $headEmployee->user) {
                        $headUser = $headEmployee->user;
                    }
                }
            } else {
                // Log jika data tidak lengkap
                $missingInfo = [];
                if (!$currentUserEmployee) $missingInfo[] = "employee";
                else {
                    if (!$currentUserEmployee->position) $missingInfo[] = "position";
                    elseif (!isset($currentUserEmployee->position->level_order)) $missingInfo[] = "position.level_order";

                    if (!$currentUserEmployee->subDepartment) $missingInfo[] = "subDepartment";
                    elseif (!$currentUserEmployee->subDepartment->department_id) $missingInfo[] = "subDepartment.department_id";
                }
                Log::warning("User {$userModel->id} ({$userModel->name}) tidak memiliki informasi lengkap untuk menentukan head employee: " . implode(", ", $missingInfo) . " tidak ditemukan.");
            }

            return $headUser;
        } catch (\Throwable $th) {
            Log::error("Error finding head user for User ID {$userModel->id}: " . $th->getMessage() . "\nStack Trace:\n" . $th->getTraceAsString());
            return null;
        }
    }

    public function getUserApprovals(User $user): \Illuminate\Support\Collection
    {
        $approvers = new Collection();
        // Eager load relasi employee beserta position, subDepartment, dan department untuk efisiensi
        $currentUserEmployee = $user->loadMissing('employee.position', 'employee.subDepartment.department')->employee;

        if (!$currentUserEmployee || !$currentUserEmployee->position || !$currentUserEmployee->subDepartment || !$currentUserEmployee->subDepartment->department_id) {
            Log::warning("User {$user->id} ({$user->name}) tidak memiliki informasi employee, posisi, sub-departemen, atau departemen yang lengkap untuk menentukan alur approval.");
            return $approvers; // Kembalikan koleksi kosong
        }

        $currentPositionLevelOrder = $currentUserEmployee->position->level_order;
        $departmentId = $currentUserEmployee->subDepartment->department_id;


        $approvalTargetLevelOrders = [
            7, // Level untuk Supervisor
            5, // Level untuk Manager
            2, // Level untuk General Manager
        ];

        try {
            foreach ($approvalTargetLevelOrders as $targetLevelOrder) {
                // Hanya pertimbangkan approver yang secara hierarki berada di atas pengguna saat ini
                if ($targetLevelOrder < $currentPositionLevelOrder) {
                    $potentialApproverEmployees = Employee::whereHas('subDepartment', fn($q) => $q->where('department_id', $departmentId))
                        ->whereHas('position', function ($query) use ($targetLevelOrder) {
                            $query->where('level_order', $targetLevelOrder);
                        })
                        ->with('user:id,name', 'subDepartment:id,name,department_id', 'position:id,name,level_order', 'subDepartment.department:id,name') // Eager load relasi user dari employee
                        ->get();

                    foreach ($potentialApproverEmployees as $approverEmployee) {
                        if ($approverEmployee->user && !$approvers->contains('id', $approverEmployee->user->id)) {
                            $approvers->push($approverEmployee);
                        }
                    }
                }
            }
        } catch (\Throwable $th) {
            Log::error("Error saat mencari approver untuk user ID {$user->id}: " . $th->getMessage() . "\nStack Trace:\n" . $th->getTraceAsString());
            return new Collection(); // Kembalikan koleksi kosong jika terjadi error
        }

        return $approvers;
    }
}
