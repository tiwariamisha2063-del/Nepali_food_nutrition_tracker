
<?php
require_once "includes/db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $food_name = trim($_POST["food_name"] ?? "");
    $category = trim($_POST["category"] ?? "");
    $calories = $_POST["calories"] ?? "";
    $protein = $_POST["protein"] ?? 0;
    $carbohydrates = $_POST["carbohydrates"] ?? 0;
    $fat = $_POST["fat"] ?? 0;
    $serving_size = trim($_POST["serving_size"] ?? "");

    if (
        $food_name === "" ||
        $calories === "" ||
        $serving_size === ""
    ) {
        $message = "Please fill in food name, calories, and serving size.";
    } elseif (
        !is_numeric($calories) ||
        !is_numeric($protein) ||
        !is_numeric($carbohydrates) ||
        !is_numeric($fat) ||
        $calories < 0 ||
        $protein < 0 ||
        $carbohydrates < 0 ||
        $fat < 0
    ) {
        $message = "Nutrition values must be valid, non-negative numbers.";
    } else {
        $sql = "INSERT INTO foods
                (food_name, category, calories, protein, carbohydrates, fat, serving_size)
                VALUES
                (:food_name, :category, :calories, :protein, :carbohydrates, :fat, :serving_size)";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            "food_name" => $food_name,
            "category" => $category,
            "calories" => $calories,
            "protein" => $protein,
            "carbohydrates" => $carbohydrates,
            "fat" => $fat,
            "serving_size" => $serving_size
        ]);

        $message = "Food added successfully!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Food - Nepalese Food Nutrition Tracker</title>
</head>
<body>

    <h1>Nepalese Food Nutrition Tracker</h1>
    <h2>Add New Food</h2>

    <p><?= htmlspecialchars($message) ?></p>

    <form method="POST" action="add_food.php">

        <label for="food_name">Food Name:</label><br>
        <input type="text" id="food_name" name="food_name" required>
        <br><br>

        <label for="category">Category:</label><br>
        <input type="text" id="category" name="category">
        <br><br>

        <label for="calories">Calories (kcal):</label><br>
        <input type="number" id="calories" name="calories" min="0" step="0.01" required>
        <br><br>

        <label for="protein">Protein (g):</label><br>
        <input type="number" id="protein" name="protein" min="0" step="0.01" value="0">
        <br><br>

        <label for="carbohydrates">Carbohydrates (g):</label><br>
        <input type="number" id="carbohydrates" name="carbohydrates" min="0" step="0.01" value="0">
        <br><br>

        <label for="fat">Fat (g):</label><br>
        <input type="number" id="fat" name="fat" min="0" step="0.01" value="0">
        <br><br>

        <label for="serving_size">Serving Size:</label><br>
        <input type="text" id="serving_size" name="serving_size" placeholder="e.g. 1 serving (200 g)" required>
        <br><br>

        <button type="submit">Add Food</button>

    </form>

    <p><a href="food.php">Back to Food List</a></p>

</body>
</html>