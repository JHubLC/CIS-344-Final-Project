<?php
require_once 'restaurantDatabase.php';
$database = new RestaurantDatabase();

// Handle customer deletion
if (isset($_GET['delete_customer_id'])) {
    $customerId = intval($_GET['delete_customer_id']);

    // Delete preferences and reservations linked to the customer first
    $database->connection->query("DELETE FROM DiningPreferences WHERE CustomerID = $customerId");
    $database->connection->query("DELETE FROM Reservations WHERE CustomerID = $customerId");

    // Delete the customer
    $stmt = $database->connection->prepare("DELETE FROM Customers WHERE CustomerID = ?");
    $stmt->bind_param("i", $customerId);
    $stmt->execute();
    $stmt->close();

    $message = "Customer deleted successfully.";
}

// Handle search
$searchTerm = $_GET['search'] ?? '';
$query = "SELECT * FROM customers WHERE CustomerName LIKE ?";
$stmt = $database->connection->prepare($query);
$searchWildcard = "%" . $searchTerm . "%";
$stmt->bind_param("s", $searchWildcard);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-5">
    <h1 class="text-center mb-4">Customers</h1>

    <!-- Display message -->
    <?php if (!empty($message)): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <!-- Search form -->
    <form method="get" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search by customer name" value="<?= htmlspecialchars($searchTerm) ?>">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>

    <!-- Customers table -->
    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <th>Customer ID</th>
            <th>Customer Name</th>
            <th>Contact Info</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($customer = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($customer['CustomerID']) ?></td>
                <td><?= htmlspecialchars($customer['CustomerName']) ?></td>
                <td><?= htmlspecialchars($customer['ContactInfo']) ?></td>
                <td>
                    <a href="?delete_customer_id=<?= $customer['CustomerID'] ?>" 
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('Are you sure you want to delete this customer?')">
                        Delete
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
<a href="home.php" class="btn btn-primary">Back to Home</a>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
