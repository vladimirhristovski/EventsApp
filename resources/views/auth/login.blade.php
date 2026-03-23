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
            background: linear-gradient(135deg, #1e3a5f 0%, #4c1d95 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .wrapper {
            display: flex;
            width: 100%;
            max-width: 480px;
            min-height: auto;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.35);
            margin: 20px;
        }

        .features li {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.82rem;
            opacity: 0.85;
        }

        .features li span.dot {
            width: 6px;
            height: 6px;
            background: rgba(255, 255, 255, 0.7);
            border-radius: 50%;
            flex-shrink: 0;
        }

        .right {
            flex: 1;
            background: white;
            padding: 50px 44px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 32px;
        }

        .brand-icon {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, #1e3a5f, #4c1d95);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
        }

        .brand-text h1 {
            font-size: 1.2rem;
            font-weight: 800;
            color: #1e293b;
        }

        .brand-text p {
            font-size: 0.8rem;
            color: #64748b;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 11px 16px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.95rem;
            outline: none;
            transition: all 0.2s;
            color: #1e293b;
            background: #f8fafc;
        }

        input:focus {
            border-color: #4c1d95;
            background: white;
            box-shadow: 0 0 0 4px rgba(76, 29, 149, 0.08);
        }

        .error {
            color: #dc2626;
            font-size: 0.8rem;
            margin-top: 5px;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.875rem;
            color: #374151;
            margin-bottom: 22px;
            cursor: pointer;
        }

        input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #4c1d95;
        }

        button {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, #1e3a5f, #4c1d95);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            letter-spacing: 0.3px;
        }

        button:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(76, 29, 149, 0.3);
        }

        button:active {
            transform: translateY(0);
        }

        .hint {
            text-align: center;
            margin-top: 18px;
            font-size: 0.78rem;
            color: #94a3b8;
            line-height: 1.5;
        }

        .hint span {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.75rem;
            margin: 0 2px;
        }

        .hint .admin-badge {
            background: #dbeafe;
            color: #1e3a5f;
        }

        .hint .employee-badge {
            background: #ede9fe;
            color: #4c1d95;
        }

    </style>
</head>
<body>
<div class="wrapper">
    <div class="right">
        <div class="brand">
            <div class="brand-icon">🏢</div>
            <div class="brand-text">
                <h1>Events Manager</h1>
                <p>Company Event Management System</p>
            </div>
        </div>

        <form method="POST" action="/login">
            @csrf

            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                       placeholder="you@company.com">
                @error('email')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="••••••••">
                @error('password')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <label class="remember">
                <input type="checkbox" name="remember">
                Remember me for 30 days
            </label>

            <button type="submit">Sign In →</button>

            <p class="hint">
                <span class="admin-badge">Admins</span> are redirected to the Admin panel
                <span class="employee-badge">Employees</span> to their portal
            </p>
        </form>
    </div>
</div>
</body>
</html>
