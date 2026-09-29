<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Form Serah Terima {{ $form->form_number }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="print-page">
    <div class="print-toolbar">
        <a href="{{ route('exit-form', ['role' => 'approver']) }}">Kembali</a>
        <button type="button" onclick="window.print()">Cetak / Simpan PDF</button>
    </div>
    <main class="print-sheet">
        <h1>Formulir Serah Terima Pekerjaan</h1>
        <p class="print-number">Nomor {{ $form->form_number }} · {{ $form->handover_date?->format('d/m/Y') }}</p>

        <dl class="print-employee">
            <div><dt>Nama</dt><dd>{{ $form->employee?->name ?? '-' }}</dd></div>
            <div><dt>NIK</dt><dd>{{ $form->employee?->employee_number ?? '-' }}</dd></div>
            <div><dt>Departemen</dt><dd>{{ $form->employee?->department?->name ?? $form->employee?->user?->department ?? '-' }}</dd></div>
        </dl>

        <section class="print-section">
            <h2>1. Tanggung jawab yang akan diterima dan harus segera ditindaklanjuti <small>(prioritas utama / urgent)</small></h2>
            <dl><div><dt>Aktivitas pekerjaan</dt><dd>{{ $form->urgent_activity ?: '-' }}</dd></div><div><dt>Dokumen pekerjaan</dt><dd>{{ $form->urgent_document ?: '-' }}</dd></div></dl>
        </section>
        <section class="print-section">
            <h2>2. Tanggung jawab pekerjaan rutin</h2>
            <dl><div><dt>Aktivitas pekerjaan</dt><dd>{{ $form->routine_activity ?: '-' }}</dd></div><div><dt>Dokumen pekerjaan</dt><dd>{{ $form->routine_document ?: '-' }}</dd></div></dl>
        </section>
        <section class="print-section">
            <h2>3. Project yang sedang dilaksanakan atau direncanakan</h2>
            <dl><div><dt>Project</dt><dd>{{ $form->project_name ?: '-' }}</dd></div><div><dt>Dokumen pekerjaan</dt><dd>{{ $form->project_document ?: '-' }}</dd></div></dl>
        </section>
        <section class="print-section print-notes">
            <h2>4. Catatan tambahan lainnya</h2>
            <p>{{ $form->additional_notes ?: '-' }}</p>
        </section>

        <section class="print-signatures">
            <div>
                <p>Diserahkan oleh</p>
                <div class="print-signature-space"></div>
                <strong>{{ $form->employee?->name ?? 'Nama' }}</strong>
                <span>Tanggal: __________________</span>
            </div>
            <div>
                <p>Diterima oleh</p>
                <div class="print-signature-space"></div>
                <strong>{{ $form->employee?->user?->approver?->name ?? 'Approver' }}</strong>
                <span>Tanggal: __________________</span>
            </div>
        </section>
    </main>
</body>
</html>