<?php
require_once 'restaurantDatabase.php';

class RestaurantPortal {
    private $db;

    public function __construct() {
        $this->db = new RestaurantDatabase();
    }

    public function handleRequest() {
        $action = $_GET['action'] ?? 'home';

        switch ($action) {
            case 'addReservation':
                $this->addReservation();
                break;
            case 'viewReservations':
                $this->viewReservations();
                break;
            default:
                $this->home();
            case 'Customers':
                $this->Customers();
                break;  
        }
    }

    private function home() {
        include 'home.php';
    }

    private function addReservation() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $customerId = $_POST['Customer_ID'];
            $reservationTime = $_POST['Reservation_Time'];
            $numberOfGuests = $_POST['Number_of_Guests'];
            $specialRequests = $_POST['Special_Requests'];

            $this->db->addReservation($customerId, $reservationTime, $numberOfGuests, $specialRequests);
            header("Location: index.php?action=viewReservations&message=Reservation Added");
        } else {
            include 'addReservation.php';
        }
    }

    private function viewReservations() {
        $reservations = $this->db->getAllReservations();
        include 'viewReservations.php';
    }

     public function Customers()
    {
        $customers = $this->db->getResult("SELECT * FROM customers");
        include 'customers.php';
    }


}

$portal = new RestaurantPortal();
$portal->handleRequest();
