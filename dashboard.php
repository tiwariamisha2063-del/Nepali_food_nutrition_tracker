
<?php
session_start();

require_once "includes/notification.php";

// Check whether the user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$user_name = $_SESSION["user_name"];
$user_role = $_SESSION["user_role"] ?? "user";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nutrition Tracker Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>

  <body>

    <?php display_notification(); ?>

    <h1>Welcome to Nepalese Food Nutrition Tracker</h1>

    <h1>Welcome to Nepalese Food Nutrition Tracker</h1>

    <h2>Hello, <?php echo htmlspecialchars($user_name); ?>!</h2>

    <p>You are successfully logged in.</p>

    <h3>Dashboard</h3>

    <?php if ($user_role === "admin"): ?>

        <h3>Admin Dashboard</h3>

        <p>Welcome, Admin! You can manage food items and review the system.</p>

        <a href="food.php">Manage Food Items</a>

        <br><br>

        <a href="food_diary.php">View Food Diary</a>

    <?php else: ?>

        <h3>User Dashboard</h3>

        <p>Welcome to your personal nutrition tracker.</p>

        <a href="food_diary.php">My Food Diary</a>

    <?php endif; ?>

    <br><br>

    <a href="logout.php">Logout</a>

</body>
</html>
