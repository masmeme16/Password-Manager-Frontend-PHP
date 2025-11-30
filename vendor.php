<?php
require_once 'auth.php';
require_login();
require_once 'api.php';

$msg = '';
// Create
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create') {
    $payload = ['vendor_name' => $_POST['vendor_name'] ?? ''];
    $res = api_request('POST', '/api/vendor', $payload);
    $msg = ($res['status'] === 201) ? 'Vendor berhasil dibuat.' : 'Gagal membuat vendor.';
}

// Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = intval($_POST['vendor_id']);
    $res = api_request('DELETE', '/api/vendor/' . $id);
    $msg = ($res['status'] === 200) ? 'Vendor berhasil dihapus.' : 'Gagal menghapus.';
}

$res = api_request('GET', '/api/vendor');
$vendors = [];
if (isset($res['body']['data'])) $vendors = $res['body']['data'];

?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Vendors - Password Manager</title>
    <link rel="stylesheet" href="assets/style.css">
    <script src="assets/app.js" defer></script>
</head>
<body>
<div class="container">
    <h1>Vendors</h1>
    <nav class="nav">
        <a href="dashboard.php">Dashboard</a>
        <a href="users.php">Users</a>
        <a href="pass.php">Passwords</a>
        <a href="?logout=1">Logout</a>
    </nav>

    <?php if ($msg): ?><p class="success"><?=htmlspecialchars($msg)?></p><?php endif; ?>

    <h2>Create Vendor</h2>
    <form method="post">
        <input type="hidden" name="action" value="create">
        <label>Name <input name="vendor_name" required></label>
        <button type="submit">Create</button>
    </form>

    <h2>All Vendors</h2>
    <table>
        <thead><tr><th>ID</th><th>Name</th><th>Created</th><th>Updated</th><th>Actions</th></tr></thead>
        <tbody>
        <?php foreach ($vendors as $v): ?>
            <tr>
                <td><?=htmlspecialchars($v['vendor_id'])?></td>
                <td><?=htmlspecialchars($v['vendor_name'])?></td>
                <td><?=htmlspecialchars($v['vendor_created'] ?? '')?></td>
                <td><?=htmlspecialchars($v['vendor_updated'] ?? '')?></td>
                <td>
                    <form method="post" onsubmit="return confirmDelete();">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="vendor_id" value="<?=htmlspecialchars($v['vendor_id'])?>">
                        <button type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
