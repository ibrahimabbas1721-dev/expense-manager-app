<?php
session_start();
require_once "../includes/db.php";

if (isset($_POST['login'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    // Find user in DB
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {

            // Set session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            // Role redirect
            if ($user['role'] == 'admin') {
                header("Location: admin/dashboard.php");
            } else {
                header("Location: user/user_dashboard.php");
            }
            exit();
        } else {
            echo "Incorrect password!";
        }
    } else {
        echo "User not found!";
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="login-container">
    <h2>Login</h2>

    <?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>

    <form action="" method="POST">
        <label>Email:</label><br>
        <input type="email" name="email" required>

        <label>Password:</label><br>
        <input type="password" name="password" required>

        <button type="submit" name="login">Login</button>
    </form>
</div>

</body>
</html>
