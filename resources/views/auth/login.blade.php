<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Exit Form</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="requirement-app auth-screen">
    <main class="auth-panel">
        <a class="requirement-brand auth-brand" href="{{ route('login') }}"><span>ef</span> Exit Form</a>
        <p class="eyebrow">WORKSPACE EXIT FORM</p>
        <h1>Masuk ke akun</h1>
        <p class="auth-intro">Gunakan akun yang telah diberikan oleh administrator.</p>
        @if(session('status'))
            <div class="requirement-alert">{{ session('status') }}</div>
        @endif
        @if($errors->any())
            <div class="requirement-alert requirement-alert-error">{{ $errors->first() }}</div>
        @endif
        <form method="post" action="{{ route('login.store') }}" class="auth-form">
            @csrf
            <label>Email<input type="email" name="email" value="{{ old('email') }}" autocomplete="username" required autofocus></label>
            <label>Password<input type="password" name="password" autocomplete="current-password" required></label>
            <button class="requirement-primary" type="submit">Masuk</button>
        </form>
    </main>
</body>
</html>