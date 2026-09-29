<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Employee;
use App\Models\HandoverForm;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use DatabaseTransactions;

    public function test_guests_are_sent_to_login_and_allowed_users_can_sign_in(): void
    {
        $this->get('/')->assertRedirect(route('login'));

        $user = User::create([
            'nik' => 'AUTH-'.substr(Str::uuid()->toString(), 0, 8),
            'name' => 'Auth Test',
            'department' => 'People Ops',
            'email' => 'auth-'.Str::uuid().'@example.test',
            'type' => 'approver',
            'allowed' => true,
            'password' => 'password',
        ]);

        $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect(route('exit-form'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_the_application_returns_a_successful_response(): void
    {
        $this->get('/')->assertRedirect(route('login'));
    }

    public function test_employee_form_uses_real_employee_records_and_explicit_status_buttons(): void
    {
        $employee = $this->createUser('employee');

        $this->actingAs($employee)->get(route('handover.index'))
            ->assertOk()
            ->assertSee($employee->employee->employee_number)
            ->assertDontSee('name="employee_id"', false)
            ->assertSee('name="status" value="draft"', false)
            ->assertSee('name="status" value="submitted"', false);
    }

    public function test_creating_an_employee_user_also_creates_its_employee_profile(): void
    {
        $suffix = Str::uuid()->toString();
        $approver = User::create([
            'nik' => 'APP-'.substr($suffix, 0, 8),
            'name' => 'Approver Test',
            'department' => 'People Ops',
            'email' => 'approver-'.$suffix.'@example.test',
            'type' => 'approver',
            'allowed' => true,
            'password' => 'password',
        ]);

        $this->actingAs($this->createUser('admin'))->post('/users', [
            'nik' => 'EMP-'.substr($suffix, 0, 8),
            'name' => 'Employee Test',
            'department' => 'Engineering',
            'email' => 'employee-'.$suffix.'@example.test',
            'type' => 'employee',
            'allowed' => '1',
            'approver_id' => $approver->id,
        ])->assertRedirect();

        $this->assertDatabaseHas('employees', [
            'employee_number' => 'EMP-'.substr($suffix, 0, 8),
            'name' => 'Employee Test',
            'user_id' => User::where('email', 'employee-'.$suffix.'@example.test')->value('id'),
        ]);
        $this->assertDatabaseHas('departments', ['name' => 'Engineering']);
    }

    public function test_updating_an_employee_user_updates_its_employee_profile(): void
    {
        $suffix = Str::uuid()->toString();
        $approver = User::create([
            'nik' => 'APP-'.substr($suffix, 0, 8),
            'name' => 'Approver Test',
            'department' => 'People Ops',
            'email' => 'approver-'.$suffix.'@example.test',
            'type' => 'approver',
            'allowed' => true,
            'password' => 'password',
        ]);
        $user = User::create([
            'nik' => 'EMP-'.substr($suffix, 0, 8),
            'name' => 'Employee Test',
            'department' => 'Engineering',
            'email' => 'employee-'.$suffix.'@example.test',
            'type' => 'employee',
            'allowed' => true,
            'approver_id' => $approver->id,
            'password' => 'password',
        ]);
        $department = Department::create(['name' => 'Engineering']);
        $employee = Employee::create([
            'user_id' => $user->id,
            'department_id' => $department->id,
            'employee_number' => $user->nik,
            'name' => $user->name,
            'email' => $user->email,
        ]);

        $this->actingAs($this->createUser('admin'))->patch(route('users.update', $user), [
            'nik' => 'UPDATED-'.substr($suffix, 0, 8),
            'name' => 'Updated Employee',
            'department' => 'Finance',
            'email' => 'updated-'.$suffix.'@example.test',
            'type' => 'employee',
            'allowed' => '1',
            'approver_id' => $approver->id,
        ])->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'nik' => 'UPDATED-'.substr($suffix, 0, 8),
            'department' => 'Finance',
        ]);
        $this->assertDatabaseHas('employees', [
            'id' => $employee->id,
            'employee_number' => 'UPDATED-'.substr($suffix, 0, 8),
            'name' => 'Updated Employee',
            'email' => 'updated-'.$suffix.'@example.test',
        ]);
        $this->assertDatabaseHas('departments', ['name' => 'Finance']);
    }

    public function test_submitting_a_handover_form_records_its_submission_time(): void
    {
        $employeeUser = $this->createUser('employee');
        $otherEmployee = $this->createUser('employee');
        $employee = $employeeUser->employee;

        $this->actingAs($employeeUser)->post('/serah-terima', [
            'employee_id' => $otherEmployee->employee->id,
            'handover_date' => now()->toDateString(),
            'status' => 'submitted',
        ])->assertRedirect(route('handover.index'));

        $form = HandoverForm::where('employee_id', $employee->id)->firstOrFail();
        $this->assertSame('submitted', $form->status);
        $this->assertNotNull($form->submitted_at);
    }

    public function test_approved_forms_are_listed_with_a_print_action_and_can_be_printed(): void
    {
        $approver = $this->createUser('approver');
        $form = $this->createHandoverForm('approved', $approver);

        $this->actingAs($approver)->get(route('handover.index'))
            ->assertOk()
            ->assertSee('Print / Save PDF');

        $this->get(route('handover.print', $form))
            ->assertOk()
            ->assertSee('Formulir Serah Terima Pekerjaan')
            ->assertSee($form->employee->name);
    }

    public function test_draft_forms_cannot_be_opened_for_printing(): void
    {
        $approver = $this->createUser('approver');
        $form = $this->createHandoverForm('draft', $approver);

        $this->actingAs($approver)->get(route('handover.print', $form))->assertNotFound();
    }

    public function test_selected_user_is_deleted_from_the_database(): void
    {
        $suffix = Str::uuid()->toString();
        $user = User::create([
            'nik' => 'DEL-'.substr($suffix, 0, 8),
            'name' => 'Delete Test User',
            'department' => 'Testing',
            'email' => 'delete-'.$suffix.'@example.test',
            'type' => 'approver',
            'allowed' => true,
            'password' => 'password',
        ]);

        $this->actingAs($this->createUser('admin'))
            ->delete(route('users.destroy'), ['user_ids' => [$user->id]])
            ->assertRedirect();

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_employee_with_handover_history_cannot_be_deleted(): void
    {
        $suffix = Str::uuid()->toString();
        $user = User::create([
            'nik' => 'HIST-'.substr($suffix, 0, 8),
            'name' => 'Employee With History',
            'department' => 'Testing',
            'email' => 'history-'.$suffix.'@example.test',
            'type' => 'employee',
            'allowed' => true,
            'password' => 'password',
        ]);
        $employee = Employee::create([
            'user_id' => $user->id,
            'employee_number' => 'HIST-'.substr($suffix, 0, 8),
            'name' => $user->name,
        ]);
        HandoverForm::create([
            'employee_id' => $employee->id,
            'form_number' => 'ST-HIST-'.substr($suffix, 0, 8),
            'handover_date' => now()->toDateString(),
            'status' => 'approved',
        ]);

        $this->actingAs($this->createUser('admin'))
            ->delete(route('users.destroy'), ['user_ids' => [$user->id]])
            ->assertSessionHasErrors('user_ids');

        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }

    public function test_approver_only_sees_and_updates_forms_assigned_to_them(): void
    {
        $approver = $this->createUser('approver');
        $otherApprover = $this->createUser('approver');
        $assignedForm = $this->createHandoverForm('submitted', $approver);
        $unassignedForm = $this->createHandoverForm('submitted', $otherApprover);

        $this->actingAs($approver)->get(route('handover.index'))
            ->assertOk()
            ->assertSee($assignedForm->employee->name)
            ->assertDontSee($unassignedForm->employee->name);

        $this->patch(route('handover.status', $unassignedForm), ['status' => 'approved'])
            ->assertForbidden();
    }

    public function test_role_middleware_blocks_employee_from_admin_actions(): void
    {
        $employee = $this->createUser('employee');

        $this->actingAs($employee)->post('/users', [])->assertForbidden();
        $this->actingAs($employee)->get('/?role=admin')->assertRedirect(route('handover.index'));
        $this->actingAs($employee)->get(route('users.index'))->assertForbidden();
    }

    public function test_admin_sees_all_three_required_menu_items(): void
    {
        $admin = $this->createUser('admin');

        $this->actingAs($admin)->get(route('users.index'))
            ->assertOk()
            ->assertSee('User')
            ->assertSee('Exit Clearance')
            ->assertSee('Serah Terima');
    }

    public function test_admin_can_create_and_delete_only_draft_handover_forms(): void
    {
        $admin = $this->createUser('admin');
        $employee = $this->createUser('employee');

        $this->actingAs($admin)->post(route('handover.store'), [
            'employee_id' => $employee->employee->id,
            'handover_date' => now()->toDateString(),
        ])->assertRedirect(route('handover.index'));

        $form = HandoverForm::where('employee_id', $employee->employee->id)->firstOrFail();
        $this->assertSame('draft', $form->status);

        $this->delete(route('handover.destroy', $form))->assertRedirect();
        $this->assertDatabaseMissing('handover_forms', ['id' => $form->id]);
    }

    public function test_new_handover_number_uses_highest_existing_sequence_when_numbers_have_gaps(): void
    {
        $admin = $this->createUser('admin');
        $employee = $this->createUser('employee');
        $year = now()->format('Y');
        $prefix = 'ST-'.$year.'-';
        $initialCount = HandoverForm::count();
        $existingSequences = HandoverForm::query()
            ->where('form_number', 'like', $prefix.'%')
            ->pluck('form_number')
            ->map(fn (string $number) => (int) substr($number, strlen($prefix)))
            ->all();

        for ($newRows = 1; ; $newRows++) {
            $countBasedSequence = $initialCount + $newRows + 1;
            if (! in_array($countBasedSequence, $existingSequences, true)) {
                break;
            }
        }

        $fillerSequences = collect(range(1, $countBasedSequence - 1))
            ->reject(fn (int $sequence) => in_array($sequence, $existingSequences, true))
            ->take($newRows - 1)
            ->all();
        $sequencesToInsert = [...$fillerSequences, $countBasedSequence];

        foreach ($sequencesToInsert as $sequence) {
            HandoverForm::create([
                'employee_id' => $employee->employee->id,
                'created_by' => $admin->id,
                'form_number' => $prefix.str_pad((string) $sequence, 4, '0', STR_PAD_LEFT),
                'handover_date' => now()->toDateString(),
                'status' => 'draft',
            ]);
        }

        $this->actingAs($admin)->post(route('handover.store'), [
            'employee_id' => $employee->employee->id,
            'handover_date' => now()->toDateString(),
        ])->assertRedirect(route('handover.index'));

        $expectedSequence = max([...$existingSequences, ...$sequencesToInsert]) + 1;
        $this->assertDatabaseHas('handover_forms', [
            'employee_id' => $employee->employee->id,
            'form_number' => $prefix.str_pad((string) $expectedSequence, 4, '0', STR_PAD_LEFT),
        ]);
    }

    private function createUser(string $type, ?User $approver = null): User
    {
        $suffix = Str::uuid()->toString();
        $user = User::create([
            'nik' => strtoupper(substr($type, 0, 3)).'-'.substr($suffix, 0, 8),
            'name' => ucfirst($type).' Test '.substr($suffix, 0, 6),
            'department' => 'Testing',
            'email' => $type.'-'.$suffix.'@example.test',
            'type' => $type,
            'allowed' => true,
            'approver_id' => $approver?->id,
            'password' => 'password',
        ]);

        if ($type === 'employee') {
            $department = Department::firstOrCreate(['name' => 'Testing']);
            Employee::create([
                'user_id' => $user->id,
                'department_id' => $department->id,
                'employee_number' => $user->nik,
                'name' => $user->name,
                'email' => $user->email,
            ]);
        }

        return $user;
    }

    private function createHandoverForm(string $status, ?User $approver = null): HandoverForm
    {
        $suffix = Str::uuid()->toString();
        $employeeUser = $this->createUser('employee', $approver);

        return HandoverForm::create([
            'employee_id' => $employeeUser->employee->id,
            'form_number' => 'ST-'.substr($suffix, 0, 12),
            'handover_date' => now()->toDateString(),
            'status' => $status,
            'urgent_activity' => 'Serah terima pekerjaan test',
        ]);
    }
}
