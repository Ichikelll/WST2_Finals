<?php
session_start();
if (!isset($_SESSION["logged_in"])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'] ?? null;
$xml = simplexml_load_file("members.xml");

$member = null;
foreach ($xml->member as $m) {
    if ((string)$m->id === $id) {
        $member = $m;
        break;
    }
}

if (!$member) {
    die("Member not found.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $member->name = htmlspecialchars($_POST['name']);
    $member->email = htmlspecialchars($_POST['email']);
    if (!empty($_POST['password'])) {
        $member->password = $_POST['password'];
    }

    $xml->asXML("members.xml");
    header("Location: home.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Member</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="login-body">
    <div class="login-card">
        <h1>Edit Member</h1>
        <form method="post" action="">
            <label>Name:</label><br>
            <input type="text" name="name" value="<?= htmlspecialchars($member->name) ?>" required><br>
            <label>Email:</label><br>
            <input type="email" name="email" value="<?= htmlspecialchars($member->email) ?>" required><br>
            <label>New Password (leave blank to keep current):</label><br>
            <input type="password" name="password"><br>
            <input type="submit" value="Update Member">
        </form>
        <p><a href="home.php">⬅ Back to Home</a></p>
    </div>
</body>
</html>