
<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

require_once "includes/db.php";

$message = "";

// Get the food ID from the URL
$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    die("Invalid food ID.");
}

// Find the food record
$stmt = $pdo->prepare("SELECT * FROM foods WHERE id = :id");
$stmt->execute(["id" => $id]);

$food = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$food) {
    die("Food not found.");
}

// Delete the food only after confirmation
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $stmt = $pdo->prepare(
        "DELETE FROM foods WHERE id = :id"
    );

    $stmt->execute(["id" => $id]);

    header("Location: food.php?message=deleted");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Food - Nepalese Food Nutrition Tracker</title>
</head>
<body>

    <h1>Nepalese Food Nutrition Tracker</h1>
    <h2>Delete Food</h2>

    <p>Are you sure you want to delete this food?</p>

    <p>
        <strong>Food Name:</strong>
        <?= htmlspecialchars($food['food_name']) ?>
    </p>

    <p>
        <strong>Serving Size:</strong>
        <?= htmlspecialchars($food['serving_size']) ?>
    </p>

    <form method="POST"
          action="delete_food.php?id=<?= (int) $id ?>"
          onsubmit="return confirm('Are you sure you want to delete this food?');">

        <button type="submit">Yes, Delete Food</button>

    </form>

    <p>
        <a href="food.php">Cancel and Return to Food List</a>
    </p>

</body>
</html>
