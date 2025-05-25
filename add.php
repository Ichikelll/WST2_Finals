<?php
session_start();
if (!isset($_SESSION["logged_in"])) {
    header("Location: index.php");
    exit();
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($name && $email && $password) {
        $xml = simplexml_load_file("members.xml");

        // Check for duplicate email
        foreach ($xml->member as $member) {
            if ((string)$member->email === $email) {
                $error = "Email already exists!";
                break;
            }
        }

        if (!$error) {
            // Generate new ID
            $ids = array_map(fn($m) => (int)$m->id, iterator_to_array($xml->member));
            $newId = $ids ? max($ids) + 1 : 1;

            $newMember = $xml->addChild('member');
            $newMember->addChild('id', $newId);
            $newMember->addChild('name', htmlspecialchars($name));
            $newMember->addChild('email', htmlspecialchars($email));
            $newMember->addChild('password', $password);

            $xml->asXML("members.xml");
            header("Location: home.php");
            exit();
        }
    } else {
        $error = "All fields are required!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Member - Perkify Membership</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="login-body">
    <div class="login-card">
        <h1>Add New Member</h1>

        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="post" action="add.php">
            <label>Name:</label><br>
            <input type="text" name="name" required><br>
            <label>Email:</label><br>
            <input type="email" name="email" required><br>
            <label>Password:</label><br>
            <input type="password" name="password" required><br>
            <input type="submit" value="Add Member">
        </form>

        <p><a href="home.php">⬅ Back to Home</a></p>
    </div>
</body>
</html>