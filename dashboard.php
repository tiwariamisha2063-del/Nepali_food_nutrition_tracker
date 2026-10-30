<?php
session_start();

// Check whether the user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$user_name = $_SESSION["user_name"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nutrition Tracker Dashboard</title>
</head>
<body>

    <h1>Welcome to Nepalese Food Nutrition Tracker</h1>

    <h2>Hello, <?php echo htmlspecialchars($user_name); ?>!</h2>

    <p>You are successfully logged in.</p>

    <h3>Dashboard</h3>

    <p>Welcome to your personal nutrition tracker.</p>

    <a href="food.php">Manage Food Items</a>

    <br><br>

    <a href="logout.php">Logout</a>

</body>
</html>