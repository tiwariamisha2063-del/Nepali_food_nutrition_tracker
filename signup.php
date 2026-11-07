
<?php
session_start();

require_once "includes/db.php";
require_once "includes/notification.php";

$message = "";
$message_type = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $full_name = trim($_POST["full_name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if (empty($full_name) || empty($email) || empty($password)) {
        $message = "Please fill in all fields.";
        $message_type = "error";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Please enter a valid email address.";
        $message_type = "error";

    } elseif (strlen($password) < 8) {
        $message = "Password must be at least 8 characters long.";
        $message_type = "error";

    } else {

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        try {
            $stmt = $pdo->prepare(
                "INSERT INTO users (full_name, email, password)
                 VALUES (:full_name, :email, :password)"
            );

            $stmt->execute([
                "full_name" => $full_name,
                "email" => $email,
                "password" => $hashed_password
            ]);

           set_notification("Registration successful!", "success");

header("Location: signup.php");
exit;

            header("Location: signup.php");
            exit;

        } catch (PDOException $e) {
            if ($e->getCode() === "23000") {
                $message = "This email is already registered.";
            } else {
                $message = "Something went wrong. Please try again.";
            }

            $message_type = "error";
        }
    }
}

// Get successful registration notification
if (isset($_SESSION["notification"])) {
    $message = $_SESSION["notification"]["message"];
    $message_type = $_SESSION["notification"]["type"];

    unset($_SESSION["notification"]);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>User Registration</title>
    <link rel="stylesheet" href="css/style.css">

    
          
</head>

<body>

    <?php display_notification(); ?>

    <h2>Create an Account</h2>

    <form method="POST" action="signup.php">

        <label>Full Name:</label><br>
        <input type="text" name="full_name" required>
        <br><br>

        <label>Email:</label><br>
        <input type="email" name="email" required>
        <br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required>
        <br><br>

        <button type="submit">Register</button>

    </form>

    <script>
        // Automatically hide notification after 4 seconds
        setTimeout(function () {
            const notification = document.getElementById("notification");

            if (notification) {
                notification.remove();
            }
        }, 4000);
    </script>

</body>
</html>
