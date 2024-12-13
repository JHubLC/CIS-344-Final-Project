<?php
require_once 'RestaurantDatabase.php';

$database = new RestaurantDatabase();

// Handle deletion
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $reservationId = intval($_GET['delete']);
    $database->deleteReservation($reservationId);
    header("Location: viewReservations.php?message=Reservation Deleted");
    exit();
}

// Fetch reservations
$reservations = $database->getAllReservations();
?>
<?php
require_once 'restaurantDatabase.php';
$database = new RestaurantDatabase();

// create search variable
$searchName = $_GET['searchName'] ?? '';

// Fetch reservations with optional search
$query = "
    SELECT 
        r.ReservationID AS ReservationID, 
        r.CustomerID AS CustomerID, 
        r.ReservationTime AS ReservationTime, 
        r.NumberofGuests AS NumberofGuests, 
        r.SpecialRequests AS SpecialRequests, 
        c.CustomerName AS CustomerName 
    FROM reservations r
    INNER JOIN customers c ON r.CustomerID = c.CustomerID
";

// If a search term is given, add a WHERE clause to filter by customer ID
if (!empty($searchName)) {
    $query .= " WHERE c.CustomerID LIKE ?";
    $stmt = $database->connection->prepare($query);
    $searchTerm = '%' . $searchName . '%';
    $stmt->bind_param('s', $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $database->connection->query($query);
}

if ($result) {
    $reservations = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $reservations = [];
    echo "Error fetching reservations: " . $database->connection->error;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Reservations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-5">
    <h1 class="text-center mb-4">View Reservations</h1>

    <h1>All Reservations</h1>

    <!-- Search Form -->
    <form method="GET" action="viewReservations.php" class="mb-4">
        <label for="searchName">Search by Customer ID:</label>
        <div class="input-group">
        <input type="text" id="searchName" class="form-control" name="searchName" value="<?= htmlspecialchars($searchName) ?>">
        <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>

    <?php if (isset($_GET['message'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_GET['message']) ?></div>
    <?php endif; ?>

    <table class="table table-striped">
        <thead>
        <tr>
            <th>Reservation ID</th>
            <th>Customer ID</th>
            <th>Reservation Time</th>
            <th>Number of Guests</th>
            <th>Special Requests</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($reservations as $reservation): ?>
            <tr>
                <td><?= htmlspecialchars($reservation['ReservationID']) ?></td>
                <td><?= htmlspecialchars($reservation['CustomerID']) ?></td>
                <td><?= htmlspecialchars($reservation['ReservationTime']) ?></td>
                <td><?= htmlspecialchars($reservation['NumberofGuests']) ?></td>
                <td><?= htmlspecialchars($reservation['SpecialRequests']) ?></td>
                <td>
                    <a href="viewReservations.php?delete=<?= $reservation['ReservationID'] ?>" 
                       class="btn btn-danger btn-sm" 
                       onclick="return confirm('Are you sure you want to delete this reservation?');">
                        Delete
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <a href="home.php" class="btn btn-primary">Back to Home</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
