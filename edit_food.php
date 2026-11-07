
<?php
session_start();

require_once "includes/db.php";
require_once "includes/notification.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}



$message = "";

// Get the food ID from the URL
$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    die("Invalid food ID.");
}

// Retrieve the existing food record
$stmt = $pdo->prepare("SELECT * FROM foods WHERE id = :id");
$stmt->execute(["id" => $id]);
$food = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$food) {
    die("Food not found.");
}

// Update the food when the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $food_name = trim($_POST["food_name"] ?? "");
    $category = trim($_POST["category"] ?? "");
    $calories = trim($_POST["calories"] ?? "");

    // Set empty nutrition fields to zero
    $protein = trim($_POST["protein"] ?? "0");
    $carbohydrates = trim($_POST["carbohydrates"] ?? "0");
    $fat = trim($_POST["fat"] ?? "0");

    $protein = ($protein === "") ? "0" : $protein;
    $carbohydrates = ($carbohydrates === "") ? "0" : $carbohydrates;
    $fat = ($fat === "") ? "0" : $fat;

    $serving_size = trim($_POST["serving_size"] ?? "");

    if (
        $food_name === "" ||
        $calories === "" ||
        $serving_size === ""
    ) {
        $message = "Food name, calories, and serving size are required.";

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

        $sql = "UPDATE foods
                SET food_name = :food_name,
                    category = :category,
                    calories = :calories,
                    protein = :protein,
                    carbohydrates = :carbohydrates,
                    fat = :fat,
                    serving_size = :serving_size
                WHERE id = :id";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
    "food_name" => $food_name,
    "category" => $category,
    "calories" => $calories,
    "protein" => $protein,
    "carbohydrates" => $carbohydrates,
    "fat" => $fat,
    "serving_size" => $serving_size,
    "id" => $id
]);

set_notification("Food updated successfully!", "success");

header("Location: food.php");
exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Food - Nepalese Food Nutrition Tracker</title>
</head>

<body>
<?php display_notification(); ?>
    <h1>Nepalese Food Nutrition Tracker</h1>
    <h2>Edit Food</h2>

    <?php if ($message !== ""): ?>
        <p style="color: green;">
            <?= htmlspecialchars($message) ?>
        </p>
    <?php endif; ?>

    <form method="POST" action="edit_food.php?id=<?= (int) $id ?>">

        <label for="food_name">Food Name:</label><br>
        <input type="text" id="food_name" name="food_name"
               value="<?= htmlspecialchars($food['food_name']) ?>" required>
        <br><br>

        <label for="category">Category:</label><br>
        <input type="text" id="category" name="category"
               value="<?= htmlspecialchars($food['category'] ?? '') ?>">
        <br><br>

        <label for="calories">Calories (kcal):</label><br>
        <input type="number" id="calories" name="calories"
               min="0" step="0.01"
               value="<?= htmlspecialchars((string) $food['calories']) ?>" required>
        <br><br>

        <label for="protein">Protein (g):</label><br>
        <input type="number" id="protein" name="protein"
               min="0" step="0.01"
               value="<?= htmlspecialchars((string) $food['protein']) ?>"
               class="nutrition-input" required>
        <br><br>

        <label for="carbohydrates">Carbohydrates (g):</label><br>
        <input type="number" id="carbohydrates" name="carbohydrates"
               min="0" step="0.01"
               value="<?= htmlspecialchars((string) $food['carbohydrates']) ?>"
               class="nutrition-input" required>
        <br><br>

        <label for="fat">Fat (g):</label><br>
        <input type="number" id="fat" name="fat"
               min="0" step="0.01"
               value="<?= htmlspecialchars((string) $food['fat']) ?>"
               class="nutrition-input" required>
        <br><br>

        <label for="serving_size">Serving Size:</label><br>
        <input type="text" id="serving_size" name="serving_size"
               value="<?= htmlspecialchars($food['serving_size']) ?>" required>
        <br><br>

        <button type="submit">Update Food</button>

    </form>

    <p><a href="food.php">Back to Food List</a></p>

    <script>
        // Handle default zero in nutrition fields
        const nutritionInputs = document.querySelectorAll(".nutrition-input");

        nutritionInputs.forEach(function(input) {

            // Remove zero when the user clicks the field
            input.addEventListener("focus", function() {
                if (this.value === "0") {
                    this.value = "";
                }
            });

            // Restore zero if the field is left empty
            input.addEventListener("blur", function() {
                if (this.value.trim() === "") {
                    this.value = "0";
                }
            });

        });
    </script>

</body>
</html>
