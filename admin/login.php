<?php
require_once 'includes/config.php';

// Redirect if already logged in
if (!empty($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email && $password) {
        $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id']        = $admin['id'];
            $_SESSION['admin_email']     = $admin['email'];
            header('Location: index.php');
            exit;
        } else {
            $error = 'Invalid email address or password. Please try again.';
        }
    } else {
        $error = 'Please fill in both fields.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login | KNCCI Nyeri</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'Inter', sans-serif;
      background: #0f172a;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      overflow: hidden;
    }

    /* Animated gradient background */
    body::before {
      content: '';
      position: absolute;
      inset: 0;
      background: radial-gradient(ellipse at 20% 50%, rgba(59, 130, 246, 0.15) 0%, transparent 60%),
                  radial-gradient(ellipse at 80% 20%, rgba(99, 102, 241, 0.1) 0%, transparent 50%),
                  radial-gradient(ellipse at 60% 80%, rgba(16, 185, 129, 0.08) 0%, transparent 50%);
      pointer-events: none;
    }

    /* Grid pattern */
    body::after {
      content: '';
      position: absolute;
      inset: 0;
      background-image: linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px),
                        linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px);
      background-size: 64px 64px;
      pointer-events: none;
    }

    .login-wrapper {
      position: relative;
      z-index: 10;
      width: 100%;
      max-width: 440px;
      padding: 24px;
    }

    /* Logo / Brand */
    .login-brand {
      text-align: center;
      margin-bottom: 40px;
    }
    .login-brand-icon {
      width: 56px;
      height: 56px;
      background: linear-gradient(135deg, #3b82f6, #6366f1);
      border-radius: 16px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      font-weight: 800;
      color: white;
      margin-bottom: 16px;
      box-shadow: 0 8px 24px rgba(59, 130, 246, 0.35);
    }
    .login-brand h1 {
      font-size: 1.4rem;
      font-weight: 700;
      color: #f8fafc;
      letter-spacing: -0.3px;
    }
    .login-brand p {
      color: #64748b;
      font-size: 0.875rem;
      margin-top: 4px;
    }

    /* Card */
    .login-card {
      background: rgba(255, 255, 255, 0.04);
      border: 1px solid rgba(255, 255, 255, 0.08);
      border-radius: 20px;
      padding: 40px;
      backdrop-filter: blur(20px);
      box-shadow: 0 24px 64px rgba(0, 0, 0, 0.4);
    }

    .card-title {
      font-size: 1.2rem;
      font-weight: 600;
      color: #f1f5f9;
      margin-bottom: 8px;
    }
    .card-subtitle {
      font-size: 0.875rem;
      color: #64748b;
      margin-bottom: 32px;
    }

    /* Form */
    .form-group {
      margin-bottom: 20px;
    }
    .form-label {
      display: block;
      font-size: 0.8rem;
      font-weight: 600;
      color: #94a3b8;
      text-transform: uppercase;
      letter-spacing: 0.8px;
      margin-bottom: 8px;
    }
    .form-input {
      width: 100%;
      padding: 14px 16px;
      background: rgba(255, 255, 255, 0.06);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 10px;
      font-family: inherit;
      font-size: 0.95rem;
      color: #f1f5f9;
      transition: all 0.2s;
      outline: none;
    }
    .form-input::placeholder { color: #475569; }
    .form-input:focus {
      border-color: #3b82f6;
      background: rgba(59, 130, 246, 0.08);
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
    }

    /* Error */
    .error-msg {
      background: rgba(239, 68, 68, 0.1);
      border: 1px solid rgba(239, 68, 68, 0.25);
      color: #fca5a5;
      padding: 12px 16px;
      border-radius: 10px;
      font-size: 0.875rem;
      margin-bottom: 24px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    /* Submit */
    .btn-login {
      width: 100%;
      padding: 14px;
      background: linear-gradient(135deg, #3b82f6, #6366f1);
      border: none;
      border-radius: 10px;
      font-family: inherit;
      font-size: 1rem;
      font-weight: 600;
      color: white;
      cursor: pointer;
      transition: all 0.2s;
      margin-top: 8px;
      box-shadow: 0 4px 16px rgba(59, 130, 246, 0.3);
    }
    .btn-login:hover {
      transform: translateY(-1px);
      box-shadow: 0 8px 24px rgba(59, 130, 246, 0.4);
    }
    .btn-login:active { transform: translateY(0); }

    .forgot-link {
      display: block;
      text-align: center;
      margin-top: 20px;
      color: #64748b;
      font-size: 0.875rem;
      text-decoration: none;
      transition: color 0.2s;
    }
    .forgot-link:hover { color: #3b82f6; }

    .login-footer {
      text-align: center;
      margin-top: 32px;
      color: #334155;
      font-size: 0.8rem;
    }
  </style>
</head>
<body>

  <div class="login-wrapper">
    <div class="login-brand">
      <div class="login-brand-icon">K</div>
      <h1>KNCCI Nyeri</h1>
      <p>Content Management System</p>
    </div>

    <div class="login-card">
      <h2 class="card-title">Welcome back</h2>
      <p class="card-subtitle">Sign in to manage your website content.</p>

      <?php if ($error): ?>
      <div class="error-msg">
        <span>⚠</span> <?php echo htmlspecialchars($error); ?>
      </div>
      <?php endif; ?>

      <form method="POST" action="">
        <div class="form-group">
          <label class="form-label" for="email">Email Address</label>
          <input type="email" id="email" name="email" class="form-input"
                 placeholder="admin@knccinyeri.co.ke"
                 value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                 required autofocus>
        </div>
        <div class="form-group">
          <label class="form-label" for="password">Password</label>
          <input type="password" id="password" name="password" class="form-input"
                 placeholder="Enter your password" required>
        </div>
        <button type="submit" class="btn-login">Sign In to Dashboard</button>
      </form>

      <a href="forgot-password.php" class="forgot-link">Forgot your password?</a>
    </div>

    <p class="login-footer">© <?php echo date('Y'); ?> KNCCI Nyeri Chapter. Secure Admin Portal.</p>
  </div>

</body>
</html>
