<?php
require_once 'auth.php';
require_login();
require_once 'api.php';

$msg = '';
// Handle actions: delete, update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $id = intval($_POST['user_id']);
        $res = api_request('DELETE', '/api/users/' . $id);
        $msg = ($res['status'] === 200) ? 'User berhasil dihapus.' : 'Gagal menghapus.';
    }
    if (isset($_POST['action']) && $_POST['action'] === 'update') {
        $id = intval($_POST['user_id']);
        $payload = [
            'user_name' => $_POST['user_name'] ?? '',
            'user_username' => $_POST['user_username'] ?? '',
            'user_password' => $_POST['user_password'] ?? ''
        ];
        $res = api_request('PUT', '/api/users/' . $id, $payload);
        $msg = ($res['status'] === 200) ? 'User berhasil diupdate.' : 'Gagal update.';
    }
}

$res = api_request('GET', '/api/users');
$users = [];
if (isset($res['body']['data'])) $users = $res['body']['data'];

?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Users - Password Manager</title>
    <link rel="stylesheet" href="assets/style.css">
    <script src="assets/app.js" defer></script>
</head>
<body>
<div class="container">
    <h1>Users</h1>
    <nav class="nav">
        <a href="dashboard.php">Dashboard</a>
        <a href="pass.php">Passwords</a>
        <a href="vendor.php">Vendors</a>
        <a href="?logout=1">Logout</a>
    </nav>

    <?php if ($msg): ?><p class="success"><?=htmlspecialchars($msg)?></p><?php endif; ?>

    <table>
        <thead>
            <tr><th>ID</th><th>Name</th><th>Username</th><th>Created</th><th>Updated</th><th>Actions</th></tr>
        </thead>
        <tbody>
        <?php foreach ($users as $u): ?>
            <tr>
                <td><?=htmlspecialchars($u['user_id'])?></td>
                <td><?=htmlspecialchars($u['user_name'])?></td>
                <td><?=htmlspecialchars($u['user_username'])?></td>
                <td><?=htmlspecialchars($u['user_created'] ?? '')?></td>
                <td><?=htmlspecialchars($u['user_updated'] ?? '')?></td>
                <td>
                    <form method="post" style="display:inline" onsubmit="return confirmDelete();">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="user_id" value="<?=htmlspecialchars($u['user_id'])?>">
                        <button type="submit">Delete</button>
                    </form>
                    <details>
                        <summary>Edit</summary>
                        <form method="post">
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="user_id" value="<?=htmlspecialchars($u['user_id'])?>">
                            <label>Name <input name="user_name" value="<?=htmlspecialchars($u['user_name'])?>"></label>
                            <label>Username <input name="user_username" value="<?=htmlspecialchars($u['user_username'])?>"></label>
                            <label>Password <input name="user_password" value="<?=htmlspecialchars($u['user_password'])?>"></label>
                            <button type="submit">Save</button>
                        </form>
                    </details>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <p><a href="register.php">Create new user</a></p>
</div>
</body>
</html>
