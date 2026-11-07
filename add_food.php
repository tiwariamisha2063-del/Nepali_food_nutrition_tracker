
<?php
session_start();

require_once "includes/notification.php";
require_once "includes/db.php";

// Check whether the user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$message = "";
$message_type = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $food_name = trim($_POST["food_name"] ?? "");
    $category = trim($_POST["category"] ?? "");
    $calories = $_POST["calories"] ?? "";

    // If nutrition fields are empty, use 0
    $protein = trim($_POST["protein"] ?? "0");
    $carbohydrates = trim($_POST["carbohydrates"] ?? "0");
    $fat = trim($_POST["fat"] ?? "0");

    $protein = ($protein === "") ? "0" : $protein;
    $carbohydrates = ($carbohydrates === "") ? "0" : $carbohydrates;
    $fat = ($fat === "") ? "0" : $fat;

    $serving_size = trim($_POST["serving_size"] ?? "");

    // Validate required fields
    if (
        $food_name === "" ||
        $calories === "" ||
        $serving_size === ""
    ) {
        $message = "Please fill in food name, calories, and serving size.";
        $message_type = "error";

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
        $message_type = "error";

    } else {

        try {
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

            // Show success notification
            set_notification("Food added successfully!", "success");

            // Redirect to prevent duplicate insertion
            header("Location: add_food.php");
            exit;

        } catch (PDOException $e) {
            $message = "Unable to add food. Please try again.";
            $message_type = "error";
        }
    }

    // Show validation or database error notification
    if ($message !== "") {
        set_notification($message, $message_type);
        header("Location: add_food.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Add Food - Nepalese Food Nutrition Tracker</title>

    <link rel="stylesheet" href="css/style.css">

    
</head>

<body>

    <?php display_notification(); ?>

    <h1>Nepalese Food Nutrition Tracker</h1>
    <h2>Add New Food</h2>

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
        <input type="number" id="protein" name="protein"
               min="0" step="0.01" value="0" class="nutrition-input">
        <br><br>

        <label for="carbohydrates">Carbohydrates (g):</label><br>
        <input type="number" id="carbohydrates" name="carbohydrates"
               min="0" step="0.01" value="0" class="nutrition-input">
        <br><br>

        <label for="fat">Fat (g):</label><br>
        <input type="number" id="fat" name="fat"
               min="0" step="0.01" value="0" class="nutrition-input">
        <br><br>

        <label for="serving_size">Serving Size:</label><br>

        <div class="serving-input-wrapper">
            <input
                type="text"
                id="serving_size"
                name="serving_size"
                required
            >

            <span class="serving-hint">
                Example: 1 small bowl, 100 grams, 1 cup
            </span>
        </div>

        <br><br>

        <button type="submit">Add Food</button>

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

        // Hide serving size hint when the user starts typing
        const servingInput = document.getElementById("serving_size");
        const servingWrapper = document.querySelector(".serving-input-wrapper");

        servingInput.addEventListener("input", function() {
            if (this.value.trim() !== "") {
                servingWrapper.classList.add("has-value");
            } else {
                servingWrapper.classList.remove("has-value");
            }
        });
    </script>

</body>
</html>
