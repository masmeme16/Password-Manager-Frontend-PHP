<?php
require_once 'api.php';
require_once 'auth.php';

if (is_logged_in()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $res = api_request('GET', '/api/users');
    if (isset($res['body']['data']) && is_array($res['body']['data'])) {
        $found = null;
        foreach ($res['body']['data'] as $u) {
            if (($u['user_username'] ?? '') === $username && ($u['user_password'] ?? '') === $password) {
                $found = $u; break;
            }
        }

        if ($found) {
            $_SESSION['user'] = ['user_id' => $found['user_id'], 'user_name' => $found['user_name'], 'user_username' => $found['user_username']];
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Username atau password salah.';
        }
    } else {
        $error = 'Gagal menghubungi API.';
    }
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Login - Password Manager</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="container">
    <h1>Login</h1>
    <?php if ($error): ?>
        <p class="error"><?=htmlspecialchars($error)?></p>
    <?php endif; ?>

    <form method="post">
        <label>Username</label>
        <input name="username" required>
        <label>Password</label>
        <input name="password" type="password" required>
        <button type="submit">Login</button>
    </form>

    <p>Belum punya akun? <a href="register.php">Register</a></p>
    <p>API base: <code>http://localhost:8080</code></p>
</div>
</body>
</html>
