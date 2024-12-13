<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body class="bg-light">
    <div class="container my-5">
        <h1 class="text-center mb-4">Welcome!</h1>

        <div class="card shadow">
            <div class="card-body">
                <h3 class="card-title text-center">Welcome to the Restaurant Portal</h3>
                <p class="text-center">Manage reservations, customers, and dining preferences easily.</p>
                <div class="d-flex justify-content-center flex-wrap gap-3">
                    <a href="addReservation.php" class="btn btn-primary">Add Reservation</a>
                    <a href="viewReservations.php" class="btn btn-secondary">View Reservations</a>
                    <a href="customers.php" class="btn btn-info">View Customers</a>
                    <a href="addPreferences.php" class="btn btn-success">Add Preferences</a>
                    <a href="ViewPreferences.php" class="btn btn-warning">View Preferences</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
