<?php
require_once 'auth.php';
require_login();
require_once 'api.php';

$msg = '';
// Create
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create') {
    $payload = [
        'pass_user_id' => intval($_POST['pass_user_id'] ?? 0),
        'pass_email' => $_POST['pass_email'] ?? '',
        'pass_password' => $_POST['pass_password'] ?? '',
        'pass_desc' => $_POST['pass_desc'] ?? ''
    ];
    $res = api_request('POST', '/api/pass', $payload);
    $msg = ($res['status'] === 201) ? 'Password berhasil dibuat.' : 'Gagal membuat password.';
}

// Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = intval($_POST['pass_id']);
    $res = api_request('DELETE', '/api/pass/' . $id);
    $msg = ($res['status'] === 200) ? 'Password berhasil dihapus.' : 'Gagal menghapus.';
}

$res = api_request('GET', '/api/pass');
$passes = [];
if (isset($res['body']['data'])) $passes = $res['body']['data'];

// For selecting users in form
$u_res = api_request('GET', '/api/users');
$users = [];
if (isset($u_res['body']['data'])) $users = $u_res['body']['data'];

// For selecting vendors in form
$v_res = api_request('GET', '/api/vendor');
$vendors = [];
if (isset($v_res['body']['data'])) $vendors = $v_res['body']['data'];

// Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $id = intval($_POST['pass_id']);
    $payload = [
        'pass_user_id' => intval($_POST['pass_user_id'] ?? 0),
        'pass_vendor_id' => intval($_POST['pass_vendor_id'] ?? 0),
        'pass_email' => $_POST['pass_email'] ?? '',
        'pass_password' => $_POST['pass_password'] ?? '',
        'pass_desc' => $_POST['pass_desc'] ?? ''
    ];
    $res = api_request('PUT', '/api/pass/' . $id, $payload);
    $msg = ($res['status'] === 200) ? 'Password berhasil diupdate.' : 'Gagal mengupdate password.';
}

?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Passwords - Password Manager</title>
    <link rel="stylesheet" href="assets/style.css">
    <script src="assets/app.js" defer></script>
</head>
<body>
<div class="container">
    <h1>Passwords</h1>
    <nav class="nav">
        <a href="dashboard.php">Dashboard</a>
        <a href="users.php">Users</a>
        <a href="vendor.php">Vendors</a>
        <a href="?logout=1">Logout</a>
    </nav>

    <?php if ($msg): ?><p class="success"><?=htmlspecialchars($msg)?></p><?php endif; ?>

    <h2>Create Password</h2>
    <form method="post">
        <input type="hidden" name="action" value="create">
        <label>User
            <select name="pass_user_id">
                <?php foreach ($users as $u): ?>
                    <option value="<?=htmlspecialchars($u['user_id'])?>"><?=htmlspecialchars($u['user_username'])?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>Vendor
            <select name="pass_vendor_id">
                <option value="0">-- none --</option>
                <?php foreach ($vendors as $v): ?>
                    <option value="<?=htmlspecialchars($v['vendor_id'])?>"><?=htmlspecialchars($v['vendor_name'])?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>Email <input name="pass_email" required></label>
        <label>Password <input name="pass_password" required></label>
        <label>Desc <input name="pass_desc"></label>
        <button type="submit">Create</button>
    </form>

    <h2>All Passwords</h2>
    <table>
        <thead><tr><th>ID</th><th>User ID</th><th>Vendor</th><th>Email</th><th>Password</th><th>Desc</th><th>Created</th><th>Updated</th><th>Actions</th></tr></thead>
        <tbody>
        <?php foreach ($passes as $p): ?>
            <tr>
                <td><?=htmlspecialchars($p['pass_id'])?></td>
                <td><?=htmlspecialchars($p['pass_user_id'])?></td>
                <td>
                    <?php
                        $vid = $p['pass_vendor_id'] ?? 0;
                        $vname = '';
                        foreach ($vendors as $vv) { if (($vv['vendor_id'] ?? 0) == $vid) { $vname = $vv['vendor_name']; break; } }
                        echo htmlspecialchars($vname ?: $vid);
                    ?>
                </td>
                <td><?=htmlspecialchars($p['pass_email'])?></td>
                <td><?=htmlspecialchars($p['pass_password'])?></td>
                <td><?=htmlspecialchars($p['pass_desc'])?></td>
                <td><?=htmlspecialchars($p['pass_created'] ?? '')?></td>
                <td><?=htmlspecialchars($p['pass_updated'] ?? '')?></td>
                <td>
                    <form method="post" onsubmit="return confirmDelete();" style="display:inline">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="pass_id" value="<?=htmlspecialchars($p['pass_id'])?>">
                        <button type="submit">Delete</button>
                    </form>
                    <details>
                        <summary>Edit</summary>
                        <form method="post">
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="pass_id" value="<?=htmlspecialchars($p['pass_id'])?>">
                            <label>User
                                <select name="pass_user_id">
                                    <?php foreach ($users as $u): ?>
                                        <option value="<?=htmlspecialchars($u['user_id'])?>" <?=($u['user_id']==($p['pass_user_id']??0))?'selected':''?>><?=htmlspecialchars($u['user_username'])?></option>
                                    <?php endforeach; ?>
                                </select>
                            </label>
                            <label>Vendor
                                <select name="pass_vendor_id">
                                    <option value="0">-- none --</option>
                                    <?php foreach ($vendors as $v): ?>
                                        <option value="<?=htmlspecialchars($v['vendor_id'])?>" <?=($v['vendor_id']==($p['pass_vendor_id']??0))?'selected':''?>><?=htmlspecialchars($v['vendor_name'])?></option>
                                    <?php endforeach; ?>
                                </select>
                            </label>
                            <label>Email <input name="pass_email" value="<?=htmlspecialchars($p['pass_email'])?>"></label>
                            <label>Password <input name="pass_password" value="<?=htmlspecialchars($p['pass_password'])?>"></label>
                            <label>Desc <input name="pass_desc" value="<?=htmlspecialchars($p['pass_desc'])?>"></label>
                            <button type="submit">Save</button>
                        </form>
                    </details>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
