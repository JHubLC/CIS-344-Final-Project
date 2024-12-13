<?php
class RestaurantDatabase {
    private $host = "localhost";
    private $port = "3306";
    private $database = "restaurant_reservations";
    private $user = "root";
    private $password = "";
    public  $connection;

    public function __construct() {
        $this->connect();
    }

    private function connect() {
        $this->connection = new mysqli($this->host, $this->user, $this->password, $this->database, $this->port);
        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
        echo "Successfully connected to the database";
    }

    public function addReservation($customerId, $reservationTime, $numberOfGuests, $specialRequests) {
        $stmt = $this->connection->prepare(
            "INSERT INTO reservations (CustomerID, ReservationTime, NumberOfGuests, SpecialRequests) VALUES (?, ?, ?, ?)"
        );
        $stmt->bind_param("isis", $customerId, $reservationTime, $numberOfGuests, $specialRequests);
        $stmt->execute();
        $stmt->close();
        echo "Reservation added successfully";
    }

    public function getAllReservations() {
        $result = $this->connection->query("SELECT * FROM reservations");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    

    public function addCustomer($customerName, $contactInfo) {
        $stmt = $this->connection->prepare(
            "SELECT customerId FROM customers WHERE customerName = ? AND contactInfo = ?"
        );
        $stmt->bind_param("ss", $customerName, $contactInfo);
        $stmt->execute();
        $result = $stmt->get_result();
        $existingCustomer = $result->fetch_assoc();
        $stmt->close();

        if ($existingCustomer) {
            return $existingCustomer['customerId'];
        }

        $stmt = $this->connection->prepare(
            "INSERT INTO customers (customerName, contactInfo) VALUES (?, ?)"
        );
        $stmt->bind_param("ss", $customerName, $contactInfo);
        $stmt->execute();
        $customerId = $this->connection->insert_id;
        $stmt->close();

        return $customerId;
    }
    
    public function getCustomerPreferences($customerId) {
        $stmt = $this->connection->prepare(
            "SELECT * FROM diningPreferences WHERE customerId = ?"
        );
        $stmt->bind_param("i", $customerId);
        $stmt->execute();
        $result = $stmt->get_result();
        $preferences = $result->fetch_assoc();
        $stmt->close();

        return $preferences;
    }

   

    public function addSpecialRequest($reservationId, $specialRequest) {
        // Check if the reservation exists
        $stmt = $this->connection->prepare(
            "SELECT ReservationID FROM reservations WHERE reservationId = ?"
        );
        $stmt->bind_param("i", $reservationId);
        $stmt->execute();
        $result = $stmt->get_result();
        $reservation = $result->fetch_assoc();
        $stmt->close();
    
        if (!$reservation) {
            // If the reservation does not exist, return false
            return "Reservation not found.";
        }
    
        // Update the special request for the reservation
        $stmt = $this->connection->prepare(
            "UPDATE reservations SET SpecialRequests = ? WHERE ReservationId = ?"
        );
        $stmt->bind_param("si", $specialRequest, $reservationId);
    
        if ($stmt->execute()) {
            $stmt->close();
            return "Special request updated successfully.";
        } else {
            $stmt->close();
            return "Failed to update special request.";
        }
    }
    
    public function deleteCustomer($customerId)
    {
        $customer = $this->connection->query("DELETE FROM customers WHERE customerId='$customerId'");
        return $customer;
    }

    public function deletePreference($preferenceId)
    {
        $preferences = $this->connection->query("DELETE FROM diningpreferences WHERE preferenceId='$preferenceId'");
        return $preferences;
    }

    public function getResult($sql)
    {
        $result = $this->connection->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function deleteReservation($reservationId) {
        $stmt = $this->connection->prepare("DELETE FROM reservations WHERE reservationId = ?");
        $stmt->bind_param("i", $reservationId);
        $stmt->execute();
        $stmt->close();
    }

}
?>
