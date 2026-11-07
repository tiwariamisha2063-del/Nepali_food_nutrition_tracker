
<?php
session_start();

require_once "includes/db.php";
require_once "includes/notification.php";

$message = "";
$message_type = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if (empty($email) || empty($password)) {

        $message = "Please enter your email and password.";
        $message_type = "error";

    } else {

        $stmt = $pdo->prepare(
            "SELECT * FROM users WHERE email = :email"
        );

        $stmt->execute(["email" => $email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user["password"])) {

            session_regenerate_id(true);

            $_SESSION["user_id"] = $user["id"];
            $_SESSION["user_name"] = $user["full_name"];
            $_SESSION["user_role"] = $user["role"];

            set_notification("Login successful! Welcome back.", "success");

            header("Location: dashboard.php");
            exit;

        } else {

            $message = "Invalid email or password.";
            $message_type = "error";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>User Login</title>

    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <?php display_notification(); ?>

    <?php if ($message !== ""): ?>
        <?php set_notification($message, $message_type); ?>
        <?php display_notification(); ?>
    <?php endif; ?>

    <h2>Login to Your Account</h2>

    <form method="POST" action="login.php">

        <label>Email:</label><br>
        <input type="email" name="email" required>
        <br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required>
        <br><br>

        <button type="submit">Login</button>

    </form>

    <p>Don't have an account?
        <a href="signup.php">Register here</a>
    </p>

</body>
</html>
