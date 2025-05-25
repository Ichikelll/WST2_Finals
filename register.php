<?php
$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!$name || !$email || !$password) {
        $error = "All fields are required.";
    } else {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Invalid email format.";
        } else {
            $xml = simplexml_load_file("members.xml");

            // Check if email already exists
            foreach ($xml->member as $member) {
                if ((string)$member->email === $email) {
                    $error = "Email is already registered.";
                    break;
                }
            }

            if (!$error) {
                $ids = [];
                foreach ($xml->member as $member) {
                    $ids[] = (int)$member->id;
                }
                $newId = $ids ? max($ids) + 1 : 1;

                $newMember = $xml->addChild('member');
                $newMember->addChild('id', $newId);
                $newMember->addChild('name', htmlspecialchars($name));
                $newMember->addChild('email', htmlspecialchars($email));
                $newMember->addChild('password', $password); // Note: plain text passwords not secure!

                $xml->asXML("members.xml");
                $success = "Registration successful! You can now <a href='index.php'>login</a>.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - Membership System</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="login-body">
<div class="login-card">
    <h1>Register</h1>
    <?php if ($error): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <?php if ($success): ?>
        <p style="color:green;"><?= $success ?></p>
    <?php else: ?>
    <form method="post" action="register.php">
        <label>Name:</label><br>
        <input type="text" name="name" required><br>
        <label>Email:</label><br>
        <input type="email" name="email" required><br>
        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>
        <input type="submit" value="Register">
    </form>
    <p>Already have an account? <a href="index.php">Login here</a></p>
    <?php endif; ?>
</div>
</body>
</html>