<?php
require_once 'RestaurantDatabase.php';
$database = new RestaurantDatabase();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_customer'])) {
        // Add a new customer
        $customerName = $_POST['new_customer_name'];
        $contactInfo = $_POST['new_contact_info'];
        $customerId = $database->addCustomer($customerName, $contactInfo);

        if ($customerId) {
            echo "New customer added successfully with ID: " . $customerId;
        } else {
            echo "Failed to add new customer. Please try again.";
        }
    } else {
        // Add a reservation
        $customerId = $_POST['customer_id'];
        $reservationTime = $_POST['reservation_time'];
        $numberOfGuests = $_POST['number_of_guests'];
        $specialRequests = $_POST['special_requests'];

        $database->addReservation($customerId, $reservationTime, $numberOfGuests, $specialRequests);
        header("Location: index.php?action=viewReservations&message=Reservation Added");
        exit();
    }
}

// Fetch all customers for the dropdown
$customers = $database->connection->query("SELECT customerId, customerName FROM customers");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Customer</title>
</head>
<body>
    <h1>Add Reservation and a Customer</h1>
    
    <h2>1. Add a New Customer (unless already added)</h2>
    <form method="post">
        <label for="new_customer_name">New Customer Name:</label>
        <input type="text" id="new_customer_name" name="new_customer_name" required>
        <br><br>
        
        <label for="new_contact_info">New Customer Contact Info:</label>
        <input type="text" id="new_contact_info" name="new_contact_info" required>
        <br><br>
        
        <button type="submit" name="add_customer">Add Customer</button>
    </form>

    <hr>

    <h2>2. Add a Reservation</h2>
    <form method="post">
        <label for="customer_id">Customer Name:</label>
        <select id="customer_id" name="customer_id" required>
            <option value="" disabled selected>Select a customer</option>
            <?php while ($customer = $customers->fetch_assoc()): ?>
                <option value="<?= $customer['customerId'] ?>">
                    <?= htmlspecialchars($customer['customerName']) ?>
                </option>
            <?php endwhile; ?>
        </select>
        <br><br>

        <label for="reservation_time">Reservation Time:</label>
        <input type="datetime-local" id="reservation_time" name="reservation_time" required>
        <br><br>

        <label for="number_of_guests">Number of Guests:</label>
        <input type="number" id="number_of_guests" name="number_of_guests" min="1" required>
        <br><br>

        <label for="special_requests">Special Requests:</label>
        <textarea id="special_requests" name="special_requests"></textarea>
        <br><br>

        <button type="submit">Add Reservation</button>
    </form>
    <a href="home.php" class="btn btn-primary">Go Back Home</a>
</body>
</html>
