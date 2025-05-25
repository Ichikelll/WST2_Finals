<?php
session_start();

// Uncomment the following lines to clear the session once for testing:
// session_destroy();
// header("Location: index.php");
// exit();

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: home.php");
    exit();
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $xml = simplexml_load_file("members.xml");

    foreach ($xml->member as $member) {
        if ((string)$member->email === $email && (string)$member->password === $password) {
            $_SESSION['logged_in'] = true;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_name'] = (string)$member->name;
            header("Location: home.php");  // Redirect after successful login
            exit();
        }
    }
    $error = "Invalid email or password";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - Membership System</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="login-body">
<div class="login-card">
    <h1>Login</h1>
    <?php if ($error): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="post" action="index.php">
        <label>Email:</label><br>
        <input type="email" name="email" required><br>
        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>
        <input type="submit" value="Login">
    </form>
    <p>Don't have an account? <a href="register.php">Register here</a></p>
</div>
</body>
</html>