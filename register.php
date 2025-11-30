<?php
require_once 'api.php';
require_once 'auth.php';

if (is_logged_in()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $payload = [
        'user_name' => $name,
        'user_username' => $username,
        'user_password' => $password
    ];

    $res = api_request('POST', '/api/users', $payload);
    if ($res['status'] === 201 || (isset($res['body']['status']) && $res['body']['status'] == 201)) {
        $success = 'Registrasi berhasil. Silakan login.';
    } else {
        $error = 'Gagal register. ' . ($res['body']['message'] ?? $res['raw'] ?? '');
    }
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Register - Password Manager</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="container">
    <h1>Register</h1>
    <?php if ($error): ?>
        <p class="error"><?=htmlspecialchars($error)?></p>
    <?php endif; ?>
    <?php if ($success): ?>
        <p class="success"><?=htmlspecialchars($success)?></p>
    <?php endif; ?>

    <form method="post">
        <label>Nama</label>
        <input name="name" required>
        <label>Username</label>
        <input name="username" required>
        <label>Password</label>
        <input name="password" type="password" required>
        <button type="submit">Register</button>
    </form>

    <p>Sudah punya akun? <a href="index.php">Login</a></p>
</div>
</body>
</html>
