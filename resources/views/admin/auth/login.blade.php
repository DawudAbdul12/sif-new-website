<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login | SIF CMS</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap"
    rel="stylesheet">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">

  <style>
    :root {
      --sif-navy: #06105A;
      --sif-navy-2: #081B78;
      --sif-green: #09A747;
      --sif-yellow: #F4C400;
      --gray-50: #F8FAFC;
      --gray-100: #F3F4F6;
      --gray-200: #E5E7EB;
      --gray-500: #6B7280;
      --gray-700: #374151;
      --gray-900: #111827;
    }

    * {
      box-sizing: border-box;
    }

    body {
      min-height: 100vh;
      background:
        radial-gradient(circle at top left, rgba(9, 167, 71, .10), transparent 34%),
        linear-gradient(180deg, #FFFFFF 0%, var(--gray-50) 52%);
      color: var(--gray-900);
      font-family: Montserrat, sans-serif;
      font-size: 14px;
      letter-spacing: 0;
      -webkit-font-smoothing: antialiased;
    }

    .admin-auth-page {
      min-height: 100vh;
      display: grid;
      place-items: center;
      padding: 32px 16px;
    }

    .admin-auth-card {
      width: min(100%, 440px);
      background: #fff;
      border: 1px solid var(--gray-200);
      border-radius: 10px;
      box-shadow: 0 24px 60px rgba(17, 24, 39, 0.14);
      padding: 28px;
    }

    .admin-auth-logo {
      height: auto;
      width: 116px;
      display: block;
      margin-bottom: 24px;
    }

    .admin-auth-title {
      color: var(--gray-900);
      font-size: 22px;
      font-weight: 700;
      line-height: 1.2;
      margin-bottom: 6px;
    }

    .admin-auth-subtitle {
      color: var(--gray-500);
      font-size: 13px;
      line-height: 1.45;
      margin-bottom: 24px;
    }

    .admin-field-label {
      color: var(--gray-500);
      display: block;
      font-size: 11px;
      font-weight: 800;
      letter-spacing: .05em;
      margin-bottom: 6px;
      text-transform: uppercase;
    }

    .admin-control {
      width: 100%;
      border: 1px solid var(--gray-200);
      border-radius: 8px;
      color: var(--gray-900);
      min-height: 42px;
      outline: none;
      padding: 10px 12px;
      transition: border-color .15s ease, box-shadow .15s ease;
    }

    .admin-control:focus {
      border-color: rgba(6, 16, 90, .55);
      box-shadow: 0 0 0 4px rgba(6, 16, 90, .09);
    }

    .form-check-input:checked {
      background-color: var(--sif-navy);
      border-color: var(--sif-navy);
    }

    .admin-submit {
      width: 100%;
      border: 0;
      border-radius: 8px;
      background: var(--sif-navy);
      color: #fff;
      font-size: 13px;
      font-weight: 800;
      min-height: 42px;
      padding: 10px 18px;
      transition: background .15s ease, transform .15s ease;
    }

    .admin-submit:hover {
      background: var(--sif-navy-2);
      transform: translateY(-1px);
    }

    .admin-auth-accent {
      align-items: center;
      background: #ECFDF3;
      border-radius: 999px;
      color: #067647;
      display: inline-flex;
      font-size: 11px;
      font-weight: 800;
      gap: 6px;
      margin-bottom: 14px;
      min-height: 24px;
      padding: 4px 9px;
      text-transform: uppercase;
    }

    .admin-auth-accent::before {
      background: var(--sif-green);
      border-radius: 999px;
      content: "";
      height: 7px;
      width: 7px;
    }
  </style>
</head>

<body>
  <main class="admin-auth-page">
    <section class="admin-auth-card">
      <img src="{{ asset('images/sif-logo-new.png') }}" alt="The Social Investment Fund" class="admin-auth-logo">

      <div class="admin-auth-accent">CMS Access</div>
      <h1 class="admin-auth-title">Admin Login</h1>
      <p class="admin-auth-subtitle">Sign in to manage the SIF website.</p>

      <form method="POST" action="{{ route('admin.login.store') }}">
        @csrf

        <div class="mb-3">
          <label for="email" class="admin-field-label">Email address</label>
          <input id="email" type="email" name="email" value="{{ old('email') }}" class="admin-control" autocomplete="email" autofocus required>
          @error('email')
            <div class="text-danger small mt-2">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-3">
          <label for="password" class="admin-field-label">Password</label>
          <input id="password" type="password" name="password" class="admin-control" autocomplete="current-password" required>
          @error('password')
            <div class="text-danger small mt-2">{{ $message }}</div>
          @enderror
        </div>

        <div class="form-check mb-4">
          <input id="remember" type="checkbox" name="remember" value="1" class="form-check-input">
          <label for="remember" class="form-check-label">Remember me</label>
        </div>

        <button type="submit" class="admin-submit">Sign In</button>
      </form>
    </section>
  </main>
</body>

</html>
