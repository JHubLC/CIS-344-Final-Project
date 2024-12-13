<?php
require_once 'restaurantDatabase.php';
$database = new RestaurantDatabase();

// Handle delete action
if (isset($_GET['action']) && $_GET['action'] === 'deletePreference' && isset($_GET['preferenceId'])) {
    $preferenceId = intval($_GET['preferenceId']); // Ensure it's an integer

    // Prepare and execute the delete query
    $stmt = $database->connection->prepare("DELETE FROM diningPreferences WHERE preferenceId = ?");
    $stmt->bind_param("i", $preferenceId);
    $stmt->execute();
    $stmt->close();

    // Redirect with a success message
    header("Location: viewPreferences.php?message=Preference deleted successfully");
    exit();
}

// Fetch all dining preferences with customer names
$query = "
    SELECT d.PreferenceID, c.CustomerName, d.FavoriteTable, d.DietaryRestrictions
    FROM diningPreferences d
    INNER JOIN customers c ON d.CustomerID = c.CustomerID
";
$preferences = $database->connection->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>View Preferences</title>
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center mb-4">Dining Preferences</h1>

        <!-- Display success message -->
        <?php if (isset($_GET['message'])): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($_GET['message']) ?>
            </div>
        <?php endif; ?>

        <!-- Preferences Table -->
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>Favorite Table</th>
                    <th>Dietary Restrictions</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $preferences->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['CustomerName']) ?></td>
                        <td><?= htmlspecialchars($row['FavoriteTable']) ?></td>
                        <td><?= htmlspecialchars($row['DietaryRestrictions']) ?></td>
                        <td>
                            <a href="?action=deletePreference&preferenceId=<?= htmlspecialchars($row['PreferenceID']) ?>" 
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Are you sure you want to delete this preference?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <a href="home.php" class="btn btn-primary">Back to Home</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
