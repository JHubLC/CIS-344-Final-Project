<?php
require_once 'restaurantDatabase.php';
$database = new RestaurantDatabase();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customerId = $_POST['customer_id'];
    $favoriteTable = $_POST['favorite_table'];
    $dietaryRestrictions = $_POST['dietary_restrictions'];

    // Save preferences
    $stmt = $database->connection->prepare(
        "INSERT INTO DiningPreferences (CustomerID, FavoriteTable, DietaryRestrictions) VALUES (?, ?, ?) 
         ON DUPLICATE KEY UPDATE FavoriteTable = VALUES(FavoriteTable), DietaryRestrictions = VALUES(DietaryRestrictions)"
    );
    $stmt->bind_param("iss", $customerId, $favoriteTable, $dietaryRestrictions);
    $stmt->execute();
    $stmt->close();

    // Redirect to viewPreferences.php with a success message
    header("Location: viewPreferences.php?message=Preferences saved successfully");
    exit();
}

// Fetch customers for the dropdown menu
$customers = $database->connection->query("SELECT customerId, customerName FROM customers");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Preferences</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center mb-4">Add Dining Preferences</h1>
        <form method="post" class="p-4 border rounded">
            <div class="mb-3">
                <label for="customer_id" class="form-label">Customer Name:</label>
                <select id="customer_id" name="customer_id" class="form-select" required>
                    <option value="" disabled selected>Select a customer</option>
                    <?php while ($customer = $customers->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($customer['customerId']) ?>">
                            <?= htmlspecialchars($customer['customerName']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="favorite_table" class="form-label">Favorite Table:</label>
                <input type="text" id="favorite_table" name="favorite_table" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="dietary_restrictions" class="form-label">Dietary Restrictions:</label>
                <textarea id="dietary_restrictions" name="dietary_restrictions" class="form-control"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Save Preferences</button>
            <a href="viewPreferences.php" class="btn btn-secondary">View Preferences</a>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
