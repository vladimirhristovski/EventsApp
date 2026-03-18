<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events Manager — Login</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
            padding: 40px;
            width: 100%;
            max-width: 400px;
        }

        .brand {
            text-align: center;
            margin-bottom: 32px;
        }

        .brand h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
        }

        .brand p {
            color: #64748b;
            font-size: 0.9rem;
            margin-top: 4px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
            margin-bottom: 6px;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 0.95rem;
            outline: none;
            transition: border-color 0.2s;
        }

        input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .error {
            color: #dc2626;
            font-size: 0.8rem;
            margin-top: 4px;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.875rem;
            color: #374151;
            margin-bottom: 24px;
        }

        button {
            width: 100%;
            padding: 11px;
            background: #3b82f6;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }

        button:hover {
            background: #2563eb;
        }
    </style>
</head>
<body>
<div class="card">
    <div class="brand">
        <h1>🏢 Events Manager</h1>
        <p>Sign in to your account</p>
    </div>

    <form method="POST" action="/login">
        @csrf

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
            @error('email')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div class="remember">
            <input type="checkbox" id="remember" name="remember">
            <label for="remember" style="margin:0;">Remember me</label>
        </div>

        <button type="submit">Sign In</button>
    </form>
</div>
</body>
</html>
