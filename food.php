
<?php
require_once "includes/db.php";

// Retrieve all food records from the database
$sql = "SELECT * FROM foods ORDER BY food_name ASC";
$stmt = $pdo->query($sql);

// Store the retrieved records in an array
$foods = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nepalese Food Nutrition Tracker</title>
</head>
<body>

    <h1>Nepalese Food Nutrition Tracker</h1>
    <h2>Food List</h2>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>Food Name</th>
                <th>Category</th>
                <th>Calories</th>
                <th>Protein (g)</th>
                <th>Carbohydrates (g)</th>
                <th>Fat (g)</th>
                <th>Serving Size</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($foods as $food): ?>
                <tr>
                    <td><?= htmlspecialchars($food["food_name"]) ?></td>
                    <td><?= htmlspecialchars($food["category"] ?? "") ?></td>
                    <td><?= htmlspecialchars($food["calories"]) ?></td>
                    <td><?= htmlspecialchars($food["protein"]) ?></td>
                    <td><?= htmlspecialchars($food["carbohydrates"]) ?></td>
                    <td><?= htmlspecialchars($food["fat"]) ?></td>
                    <td><?= htmlspecialchars($food["serving_size"]) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <p>Total foods: <?= count($foods) ?></p>

</body>
</html>
