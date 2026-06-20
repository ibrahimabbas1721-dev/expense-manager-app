<?php
require_once(__DIR__ . "/../../includes/auth.php");
requireAdmin();
require_once(__DIR__ . "/../../includes/db.php");

$id = $_GET['id'];

$user = $conn->query("SELECT * FROM users WHERE id = $id")->fetch_assoc();

if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $role = $_POST['role'];

    $stmt = $conn->prepare("UPDATE users SET name=?, role=? WHERE id=?");
    $stmt->bind_param("ssi", $name, $role, $id);
    $stmt->execute();

    header("Location: users.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
    <link rel="stylesheet" href="../../assets/css/dashboard.css">

</head>
<body>

<!-- Sidebar -->
    <?php include '../../includes/admin_sideBar.php'; ?>
<div class="main-content">
    <div class="topbar">
        <h3>Edit User</h3>
    </div>

    <form class="form" method="POST">
        <label>Name</label>
        <input type="text" name="name" value="<?= $user['name']; ?>" required>

        <label>Role</label>
        <select name="role">
            <option <?= $user['role']=='admin'?'selected':''; ?>>admin</option>
            <option <?= $user['role']=='user'?'selected':''; ?>>user</option>
        </select>

        <button type="submit" name="update" class="add-btn">Update</button>
    </form>

</div>
</body>
</html>
