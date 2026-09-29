<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\HandoverForm;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExitFormController extends Controller
{
    public function index(Request $request)
    {
        if ($request->routeIs('exit-form')) {
            return redirect()->route($request->user()->type === 'admin' ? 'users.index' : 'handover.index');
        }

        $currentUser = $request->user();
        $role = $currentUser->type;
        $section = match (true) {
            $request->routeIs('users.index') => 'users',
            $request->routeIs('exit-clearance.index') => 'clearance',
            default => 'handover',
        };
        $employee = $role === 'employee'
            ? $currentUser->employee()->with('department')->first()
            : null;

        abort_if($role === 'employee' && ! $employee, 403);

        $users = $section === 'users'
            ? User::query()->with('approver')->orderBy('name')->get()
            : collect();

        $formsQuery = HandoverForm::query()
            ->with(['employee.user.approver', 'employee.department', 'creator'])
            ->latest();

        if ($section !== 'handover') {
            $formsQuery->whereRaw('1 = 0');
        } elseif ($role === 'employee') {
            $formsQuery->whereHas('employee', fn ($query) => $query->where('user_id', $currentUser->id));
        } elseif ($role === 'approver') {
            $formsQuery->whereHas('employee.user', fn ($query) => $query->where('approver_id', $currentUser->id));
        }

        $employees = $section === 'handover' && $role === 'admin'
            ? Employee::query()
                ->whereHas('user', fn ($query) => $query->where('type', 'employee')->where('allowed', true))
                ->with(['user', 'department'])
                ->orderBy('name')
                ->get()
            : collect();

        return view('exit-form.index', [
            'role' => $role,
            'section' => $section,
            'currentUser' => $currentUser,
            'sectionName' => match ($section) {
                'users' => 'User',
                'clearance' => 'Exit Clearance',
                default => 'Serah Terima',
            },
            'users' => $users,
            'forms' => $formsQuery->get(),
            'employee' => $employee,
            'employees' => $employees,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => [$request->user()->type === 'admin' ? 'required' : 'nullable', 'exists:employees,id'],
            'handover_date' => ['required', 'date'],
            'urgent_activity' => ['nullable', 'string'],
            'urgent_document' => ['nullable', 'string'],
            'routine_activity' => ['nullable', 'string'],
            'routine_document' => ['nullable', 'string'],
            'project_name' => ['nullable', 'string'],
            'project_document' => ['nullable', 'string'],
            'additional_notes' => ['nullable', 'string'],
            'status' => ['nullable', 'in:draft,submitted'],
        ]);

        if ($request->user()->type === 'admin') {
            $employee = Employee::query()
                ->whereKey($validated['employee_id'])
                ->whereHas('user', fn ($query) => $query->where('type', 'employee')->where('allowed', true))
                ->firstOrFail();
            $validated['status'] = 'draft';
        } else {
            $employee = $request->user()->employee;
            abort_unless($employee, 403);
        }

        $validated['status'] = $validated['status'] ?? 'draft';
        $validated['submitted_at'] = $validated['status'] === 'submitted' ? now() : null;

        DB::transaction(function () use (&$validated, $employee, $request): void {
            $validated['form_number'] = $this->nextFormNumber();
            $validated['employee_id'] = $employee->id;
            $validated['created_by'] = $request->user()->id;
            HandoverForm::create($validated);
        });

        $message = $validated['status'] === 'submitted'
            ? 'Form berhasil dikirim untuk persetujuan.'
            : 'Form berhasil disimpan sebagai draft.';

        return redirect()->route('handover.index')->with('success', $message);
    }

    private function nextFormNumber(): string
    {
        $prefix = 'ST-'.now()->format('Y').'-';
        $lastSequence = HandoverForm::query()
            ->where('form_number', 'like', $prefix.'%')
            ->lockForUpdate()
            ->pluck('form_number')
            ->map(static function (string $formNumber) use ($prefix): int {
                $sequence = substr($formNumber, strlen($prefix));

                return ctype_digit($sequence) ? (int) $sequence : 0;
            })
            ->max() ?? 0;

        do {
            $lastSequence++;
            $formNumber = $prefix.str_pad((string) $lastSequence, 4, '0', STR_PAD_LEFT);
        } while (HandoverForm::query()->where('form_number', $formNumber)->exists());

        return $formNumber;
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'nik' => ['required', 'string', 'max:50', 'unique:users,nik'],
            'name' => ['required', 'string', 'max:255'],
            'department' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'type' => ['required', 'in:employee,approver,admin'],
            'allowed' => ['required', 'boolean'],
            'approver_id' => ['nullable', 'exists:users,id'],
        ]);

        if ($validated['type'] === 'employee' && empty($validated['approver_id'])) {
            return back()->withErrors(['approver_id' => 'Approver wajib diisi untuk employee.'])->withInput();
        }

        DB::transaction(function () use ($validated): void {
            $user = User::create($validated + ['password' => 'password']);

            if ($user->type === 'employee') {
                $department = Department::firstOrCreate(['name' => $user->department]);

                Employee::create([
                    'user_id' => $user->id,
                    'department_id' => $department->id,
                    'employee_number' => $user->nik,
                    'name' => $user->name,
                    'email' => $user->email,
                ]);
            }
        });

        return back()->with('success', 'User baru berhasil ditambahkan.');
    }

    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'nik' => ['required', 'string', 'max:50', 'unique:users,nik,'.$user->id],
            'name' => ['required', 'string', 'max:255'],
            'department' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,'.$user->id],
            'type' => ['required', 'in:employee,approver,admin'],
            'allowed' => ['required', 'boolean'],
            'approver_id' => ['nullable', 'exists:users,id'],
        ]);

        if ($validated['type'] === 'employee' && empty($validated['approver_id'])) {
            return back()->withErrors(['approver_id' => 'Approver wajib diisi untuk employee.'])->withInput();
        }

        DB::transaction(function () use ($validated, $user): void {
            $user->update($validated);

            if ($user->type === 'employee') {
                $department = Department::firstOrCreate(['name' => $user->department]);

                $user->employee()->updateOrCreate([], [
                    'department_id' => $department->id,
                    'employee_number' => $user->nik,
                    'name' => $user->name,
                    'email' => $user->email,
                ]);
            }
        });

        return back()->with('success', 'Data user berhasil diperbarui.');
    }

    public function destroyUsers(Request $request)
    {
        $validated = $request->validate([
            'user_ids' => ['required', 'array', 'min:1'],
            'user_ids.*' => ['required', 'integer', 'distinct', 'exists:users,id'],
        ]);

        $users = User::query()->with('employee')->whereIn('id', $validated['user_ids'])->get();

        foreach ($users as $user) {
            if ($user->employee?->handoverForms()->exists()) {
                return back()->withErrors([
                    'user_ids' => "User {$user->name} tidak dapat dihapus karena sudah memiliki histori serah-terima.",
                ]);
            }
        }

        DB::transaction(function () use ($users): void {
            foreach ($users as $user) {
                $user->employee?->delete();
                $user->delete();
            }
        });

        return back()->with('success', 'User yang dipilih berhasil dihapus.');
    }

    public function destroyHandover(HandoverForm $handoverForm)
    {
        abort_unless($handoverForm->status === 'draft', 409);

        $handoverForm->delete();

        return back()->with('success', 'Form draft berhasil dihapus.');
    }

    public function updateStatus(Request $request, HandoverForm $handoverForm)
    {
        abort_unless($handoverForm->employee?->user?->approver_id === $request->user()->id, 403);

        $validated = $request->validate([
            'status' => ['required', 'in:approved,rejected'],
        ]);
        abort_unless($handoverForm->status === 'submitted', 409);

        $status = $validated['status'];
        $handoverForm->update([
            'status' => $status,
            'approved_at' => $status === 'approved' ? now() : null,
        ]);

        return back()->with('success', 'Status form berhasil diperbarui.');
    }

    public function print(Request $request, HandoverForm $handoverForm)
    {
        abort_unless($handoverForm->status === 'approved', 404);
        abort_unless(
            $request->user()->type === 'admin'
                || $handoverForm->employee?->user?->approver_id === $request->user()->id,
            403,
        );

        $handoverForm->load(['employee.user.approver', 'employee.department']);

        return view('exit-form.print', ['form' => $handoverForm]);
    }

    public function preview(HandoverForm $handoverForm)
    {
        $handoverForm->load(['employee.user.approver', 'employee.department']);

        return view('exit-form.print', ['form' => $handoverForm]);
    }
}
