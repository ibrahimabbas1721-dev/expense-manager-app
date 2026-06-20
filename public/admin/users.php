<?php
require_once(__DIR__ . "/../../includes/auth.php");
requireAdmin();
require_once(__DIR__ . "/../../includes/db.php");
?>


<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
<link rel="stylesheet" href="/expense_app/assets/css/dashboard.css">
</head>
<body>

<?php
include '../../includes/admin_sideBar.php';
?>

<div class="main-content">
    <div class="topbar">
        <h3 class ="page-title">Manage Users</h3>
    </div>

    <a href="add_user.php" class="add-btn">+ Add User</a>

    <div id="table-con">
    <table class="table">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>

        <?php
        $result = $conn->query("SELECT * FROM users ORDER BY id DESC");
        while ($row = $result->fetch_assoc()):
        ?>
        <tr>

            <td><?= $row['id']; ?></td>
            <td><?= $row['name']; ?></td>
            <td><?= $row['email']; ?></td>
            <td><?= $row['role']; ?></td>
            <td>
                <a class="edit-btn" href="edit_user.php?id=<?= $row['id']; ?>">Edit</a>
                <a class="delete-btn" href="delete_user.php?id=<?= $row['id']; ?>">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    </div>
</div>
</body>
</html>
