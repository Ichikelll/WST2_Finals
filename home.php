<?php
session_start();
if (!isset($_SESSION["logged_in"])) {
    header("Location: login.php");
    exit();
}

$xml = simplexml_load_file("members.xml");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Perkify Members List</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet" />
    <!-- Link to CSS in the same folder -->
    <link rel="stylesheet" href="style.css" />
</head>
<body class="default-body">

    <!-- Hero Header -->
    <div class="hero-header">
        <h1>🛒PERKIFY SHOPPING MEMBERSHIP SYSTEM</h1>
    </div>

    <div class="container">
        <div class="header-container">
            <a href="logout.php" class="logout-link">↩️Logout</a>
        </div>

        <a href="add.php">✚Add New Member</a>
        <table>
            <thead>
                <tr>
                    <th class="id-col">ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th class="actions-col">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($xml->member as $member): ?>
                <tr>
                    <td class="id-col"><?= htmlspecialchars($member->id) ?></td>
                    <td><?= htmlspecialchars($member->name) ?></td>
                    <td><?= htmlspecialchars($member->email) ?></td>
                    <td class="actions-col">
                        <a href="edit.php?id=<?= urlencode($member->id) ?>">✏️ Edit</a> |
                        <a href="delete.php?id=<?= urlencode($member->id) ?>" onclick="return confirm('Are you sure you want to delete this member?')">🗑 Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>
</html>