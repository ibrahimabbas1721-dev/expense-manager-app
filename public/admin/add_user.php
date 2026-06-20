<?php
// Fixed paths for files
require_once(__DIR__ . "/../../includes/auth.php");
requireAdmin();
require_once(__DIR__ . "/../../includes/db.php");

$error = "";

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $password, $role);

    if ($stmt->execute()) {
        // Redirect to users.php after successful insertion
        header("Location: users.php");
        exit; // Stop further execution
    } else {
        $error = "Email already exists!";
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Add User</title>
    <!-- Fixed CSS path -->
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
</head>
<body>

<!-- Sidebar -->
    <?php include '../../includes/admin_sideBar.php'; ?>

<div class="main-content">
    <div class="topbar">
        <h3>Add User</h3>
    </div>

    <!-- <?php if ($error) echo "<div class='error'>$error</div>"; ?>
    <?php if ($success) echo "<div class='success'>$success</div>"; ?> -->

    <form class="form" method="POST">

        <label>Name</label>
        <input type="text" name="name" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <label>Role</label>
        <select name="role">
            <option value="admin">Admin</option>
            <option value="user" selected>User</option>
        </select>

        <button type="submit" name="submit" class="add-btn">Add User</button>
    </form>
</div>
</body>
</html>
