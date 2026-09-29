<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Exit Form</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="requirement-app">
<div class="requirement-shell">
    <aside class="requirement-sidebar">
        <button class="requirement-sidebar-close" type="button" data-req-menu-close aria-label="Tutup sidebar">×</button>
        <a class="requirement-brand" href="{{ route('exit-form') }}"><span>ef</span> Exit Form</a>
        <p class="requirement-label">MENU</p>
        @if($role === 'admin')
            <a class="requirement-nav {{ $section === 'users' ? 'selected' : '' }}" href="{{ route('users.index') }}">▦ <span>User</span></a>
            <a class="requirement-nav {{ $section === 'clearance' ? 'selected' : '' }}" href="{{ route('exit-clearance.index') }}">✓ <span>Exit Clearance</span></a>
        @endif
        <a class="requirement-nav {{ $section === 'handover' ? 'selected' : '' }}" href="{{ route('handover.index') }}">⇄ <span>Serah Terima</span></a>
        <div class="requirement-sidebar-bottom"><span class="avatar">{{ strtoupper(substr($currentUser->name, 0, 2)) }}</span><span><b>{{ $currentUser->name }}</b><small>{{ ucfirst($role) }}</small></span></div>
        <form method="post" action="{{ route('logout') }}" class="sidebar-logout">@csrf<button type="submit">Keluar</button></form>
    </aside>
    <main class="requirement-main">
        <header class="requirement-topbar"><button class="requirement-menu" data-req-menu>☰</button><span class="requirement-breadcrumb">Exit Form <b>/</b> {{ $sectionName }}</span><span class="requirement-user">{{ strtoupper(substr($currentUser->name, 0, 2)) }}</span></header>
        <div class="requirement-content">
            @if(session('status')) <div class="requirement-alert">{{ session('status') }}</div> @endif
            @if(session('success')) <div class="requirement-alert">✓ {{ session('success') }}</div> @endif
            @if($errors->any()) <div class="requirement-alert requirement-alert-error">{{ $errors->first() }}</div> @endif
            @if($section === 'users')
                <section class="requirement-heading"><div><p class="eyebrow">ADMINISTRATOR</p><h1>User</h1><p>Kelola akun, akses, dan approver pada sistem Exit Form.</p></div><button class="requirement-primary" data-user-modal>＋ Add New User</button></section>
                <section class="requirement-panel">
                    <div class="requirement-filters"><label>Search<input type="search" placeholder="NIK atau nama lengkap" data-req-search></label><label>Filter<select><option>Departemen</option><option>Engineering</option><option>Finance</option><option>Marketing</option></select></label><label>Filter value<input placeholder="Masukkan nilai filter"></label><button class="requirement-secondary">Reset</button></div>
                    <form method="post" action="{{ route('users.destroy') }}" data-delete-users>
                        @csrf
                        @method('DELETE')
                        <div class="action-line"><button class="link-action" type="submit">Delete</button></div>
                        <div class="req-table-wrap"><table class="req-table"><thead><tr><th></th><th>NIK</th><th>Nama Lengkap</th><th>Departemen</th><th>Email</th><th>Type</th><th>Allowed</th><th>Approver</th><th>Created by</th><th>Created date</th><th>Updated By</th><th>Last Updated</th></tr></thead><tbody data-req-list>@forelse($users as $user)<tr><td><input type="checkbox" name="user_ids[]" value="{{ $user->id }}"></td><td><a href="#edit-user-{{ $user->id }}" data-user-edit data-update-url="{{ route('users.update', $user) }}" data-nik="{{ $user->nik }}" data-name="{{ $user->name }}" data-department="{{ $user->department }}" data-email="{{ $user->email }}" data-type="{{ $user->type }}" data-allowed="{{ $user->allowed ? '1' : '0' }}" data-approver-id="{{ $user->approver_id }}">{{ $user->nik ?? '-' }}</a></td><td>{{ $user->name }}</td><td>{{ $user->department ?? '-' }}</td><td>{{ $user->email }}</td><td><span class="role-pill {{ $user->type }}">{{ ucfirst($user->type) }}</span></td><td>{{ $user->allowed ? 'Yes' : 'No' }}</td><td>{{ $user->approver?->name ?? '-' }}</td><td>Admin</td><td>{{ $user->created_at?->format('d M Y') }}</td><td>-</td><td>{{ $user->updated_at?->format('d M Y') }}</td></tr>@empty<tr><td colspan="12" class="empty-row">Belum ada data user.</td></tr>@endforelse</tbody></table></div>
                    </form>
                </section>
            @elseif($section === 'clearance')
                <section class="requirement-heading"><div><p class="eyebrow">ADMINISTRATOR</p><h1>Exit Clearance</h1></div></section>
                <section class="requirement-panel"><p class="empty-row">Belum ada data Exit Clearance.</p></section>
            @else
                <section class="requirement-heading"><div><p class="eyebrow">{{ strtoupper($role) }}</p><h1>{{ $sectionName }}</h1><p>{{ $role === 'approver' ? 'Tinjau form dari employee yang ditugaskan kepada Anda.' : ($role === 'admin' ? 'Kelola form serah-terima karyawan.' : 'Lengkapi form serah-terima sebelum meninggalkan perusahaan.') }}</p></div>@if($role === 'employee')<button class="requirement-primary" data-employee-form>＋ Isi Form</button>@elseif($role === 'admin')<button class="requirement-primary" data-handover-modal>＋ Add New Form</button>@endif</section>
                @if($role === 'employee')
                    <section class="employee-form requirement-panel">
                        <div class="employee-summary"><span class="mini-avatar coral-bg">ST</span><div><b>Form Serah Terima</b><small>{{ $employee->employee_number }} · {{ $employee->name }}</small></div><span class="status-pill draft">Draft</span></div>
                        <form method="post" action="{{ route('handover.store') }}">
                            @csrf
                            <input type="hidden" name="handover_date" value="{{ now()->format('Y-m-d') }}">
                            <h2>1. Tanggung jawab yang akan diterima dan harus segera ditindaklanjuti <small>(prioritas utama / urgent)</small></h2>
                            <div class="req-form-grid"><label>Aktivitas pekerjaan<textarea name="urgent_activity" placeholder="Tuliskan aktivitas pekerjaan...">{{ old('urgent_activity') }}</textarea></label><label>Dokumen pekerjaan<textarea name="urgent_document" placeholder="Tuliskan dokumen atau lokasi penyimpanan...">{{ old('urgent_document') }}</textarea></label></div>
                            <h2>2. Tanggung jawab pekerjaan rutin</h2>
                            <div class="req-form-grid"><label>Aktivitas pekerjaan<textarea name="routine_activity">{{ old('routine_activity') }}</textarea></label><label>Dokumen pekerjaan<textarea name="routine_document">{{ old('routine_document') }}</textarea></label></div>
                            <h2>3. Project yang sedang dilaksanakan atau direncanakan</h2>
                            <div class="req-form-grid"><label>Project<textarea name="project_name">{{ old('project_name') }}</textarea></label><label>Dokumen pekerjaan<textarea name="project_document">{{ old('project_document') }}</textarea></label></div>
                            <h2>4. Catatan tambahan lainnya</h2>
                            <textarea name="additional_notes" class="wide-textarea">{{ old('additional_notes') }}</textarea>
                            <div class="employee-actions"><button class="requirement-secondary" type="submit" name="status" value="draft">Save as Draft</button><button class="requirement-primary" type="submit" name="status" value="submitted">Submit</button></div>
                        </form>
                    </section>
                @elseif($role === 'admin')
                    <section class="requirement-panel">
                        <div class="req-table-wrap"><table class="req-table"><thead><tr><th>NIK</th><th>Nama Lengkap</th><th>Departemen</th><th>Status</th><th>Created by</th><th>Created date</th><th>Last Updated</th><th>Action</th></tr></thead><tbody data-req-list>
                            @forelse($forms as $form)
                                <tr><td><a href="{{ route('handover.preview', $form) }}">{{ $form->employee?->employee_number ?? '-' }}</a></td><td>{{ $form->employee?->name }}</td><td>{{ $form->employee?->department?->name ?? $form->employee?->user?->department ?? '-' }}</td><td><span class="status-pill {{ $form->status }}">{{ ucfirst($form->status) }}</span></td><td>{{ $form->creator?->name ?? '-' }}</td><td>{{ $form->created_at?->format('d M Y') }}</td><td>{{ $form->updated_at?->format('d M Y') }}</td><td>
                                    @if($form->status === 'draft')
                                        <form method="post" action="{{ route('handover.destroy', $form) }}" class="inline-actions" data-delete-handover>@csrf @method('DELETE')<button class="reject" type="submit">Delete</button></form>
                                    @elseif($form->status === 'approved')
                                        <a class="requirement-secondary print-action" href="{{ route('handover.print', $form) }}" target="_blank" rel="noopener">PDF</a>
                                    @else
                                        -
                                    @endif
                                </td></tr>
                            @empty
                                <tr><td colspan="8" class="empty-row">Belum ada form serah-terima.</td></tr>
                            @endforelse
                        </tbody></table></div>
                    </section>
                @else
                    <section class="requirement-panel">
                        <div class="requirement-filters">
                            <label>Search<input type="search" placeholder="NIK atau nama lengkap" data-req-search></label>
                            <label>Filter<select><option>Status</option><option>Submitted</option><option>Approved</option></select></label>
                            <label>Filter value<select><option>Semua departemen</option><option>Engineering</option><option>Finance</option></select></label>
                        </div>
                        <div class="req-table-wrap">
                            <table class="req-table">
                                <thead><tr><th></th><th>NIK</th><th>Nama Lengkap</th><th>Departemen</th><th>Status</th><th>Created by</th><th>Created date</th><th>Updated by</th><th>Last Updated</th><th>Action</th></tr></thead>
                                <tbody data-req-list>
                                    @forelse($forms->whereIn('status', ['submitted', 'approved']) as $form)
                                        <tr>
                                            <td><input type="checkbox"></td>
                                            <td><a href="#form-{{ $form->id }}">{{ $form->employee?->employee_number ?? '-' }}</a></td>
                                            <td>{{ $form->employee?->name }}</td>
                                            <td>{{ $form->employee?->department?->name ?? $form->employee?->user?->department ?? '-' }}</td>
                                            <td><span class="status-pill {{ $form->status }}">{{ ucfirst($form->status) }}</span></td>
                                            <td>{{ $form->creator?->name ?? 'Admin' }}</td>
                                            <td>{{ $form->created_at?->format('d M Y') }}</td>
                                            <td>-</td>
                                            <td>{{ $form->updated_at?->format('d M Y') }}</td>
                                            <td>
                                                @if($form->status === 'approved')
                                                    <a class="requirement-secondary print-action" href="{{ route('handover.print', $form) }}" target="_blank" rel="noopener">Print / Save PDF</a>
                                                @else
                                                    <form method="post" action="{{ route('handover.status', $form) }}" class="inline-actions">@csrf @method('PATCH')<input type="hidden" name="status" value="approved"><button>Approve</button></form>
                                                    <form method="post" action="{{ route('handover.status', $form) }}" class="inline-actions">@csrf @method('PATCH')<input type="hidden" name="status" value="rejected"><button class="reject">Reject</button></form>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="10" class="empty-row">Belum ada form yang menunggu persetujuan atau siap dicetak.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </section>
                @endif
            @endif
        </div>
    </main>
</div>
@if($section === 'users')
    <div class="requirement-modal" data-user-modal-box><div class="requirement-modal-card"><button class="modal-x" type="button" data-close-user aria-label="Tutup">×</button><p class="eyebrow">ADMINISTRATOR</p><h2 data-user-modal-title>Add New User</h2><form method="post" action="{{ route('users.store') }}" data-user-form data-create-url="{{ route('users.store') }}">@csrf<div class="req-form-grid"><label>NIK<input name="nik" placeholder="1040" required></label><label>Nama Lengkap<input name="name" placeholder="Nama user" required></label><label>Departemen<input name="department" placeholder="Departemen" required></label><label>Email<input name="email" type="email" placeholder="email@company.com" required></label><label>Type<select name="type"><option value="employee">Employee</option><option value="approver">Approver</option><option value="admin">Admin</option></select></label><label>Allowed<select name="allowed"><option value="1">Yes</option><option value="0">No</option></select></label></div><label>Approver<select name="approver_id"><option value="">Pilih approver</option>@foreach($users->where('type', 'approver') as $approver)<option value="{{ $approver->id }}">{{ $approver->nik }} - {{ $approver->name }}</option>@endforeach</select></label><button class="requirement-primary" type="submit" data-user-submit>Save User</button></form></div></div>
@endif
@if($role === 'admin' && $section === 'handover')
    <div class="requirement-modal" data-handover-modal-box><div class="requirement-modal-card"><button class="modal-x" type="button" data-close-handover aria-label="Tutup">×</button><p class="eyebrow">ADMINISTRATOR</p><h2>Add New Form</h2><form method="post" action="{{ route('handover.store') }}">@csrf<label>Employee<select name="employee_id" required><option value="">Pilih employee</option>@foreach($employees as $formEmployee)<option value="{{ $formEmployee->id }}">{{ $formEmployee->employee_number }} - {{ $formEmployee->name }}</option>@endforeach</select></label><input type="hidden" name="handover_date" value="{{ now()->format('Y-m-d') }}"><button class="requirement-primary" type="submit">Save</button></form></div></div>
@endif
</body>
</html>
