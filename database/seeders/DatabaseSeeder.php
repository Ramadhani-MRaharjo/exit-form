<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\HandoverForm;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::updateOrCreate(['email' => 'admin@exitform.test'], [
            'nik' => '1000', 'name' => 'Andi Rahman', 'department' => 'People Ops',
            'type' => 'admin', 'allowed' => true, 'password' => Hash::make('password'),
        ]);
        $approver = User::updateOrCreate(['email' => 'approver@exitform.test'], [
            'nik' => '1001', 'name' => 'Sari Puspita', 'department' => 'People Ops',
            'type' => 'approver', 'allowed' => true, 'password' => Hash::make('password'),
        ]);
        $employee = User::updateOrCreate(['email' => 'shella@exitform.test'], [
            'nik' => '1040', 'name' => 'Shella Cintiya', 'department' => 'Engineering',
            'type' => 'employee', 'allowed' => true, 'approver_id' => $approver->id,
            'password' => Hash::make('password'),
        ]);
        $employeeRecord = Employee::updateOrCreate(['user_id' => $employee->id], [
            'department_id' => null, 'employee_number' => $employee->nik, 'name' => $employee->name,
            'email' => $employee->email, 'position' => 'Software Engineer',
        ]);

        HandoverForm::updateOrCreate(['form_number' => 'ST-2026-0001'], [
            'employee_id' => $employeeRecord->id, 'created_by' => $admin->id,
            'handover_date' => now()->toDateString(), 'status' => 'submitted',
            'urgent_activity' => 'Serah-terima deployment dan monitoring aplikasi',
            'urgent_document' => 'Dokumen deployment pada folder Engineering',
        ]);
        HandoverForm::updateOrCreate(['form_number' => 'ST-2026-0002'], [
            'employee_id' => $employeeRecord->id, 'created_by' => $admin->id,
            'handover_date' => now()->toDateString(), 'status' => 'draft',
        ]);
    }
}
