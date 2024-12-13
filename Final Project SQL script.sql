CREATE TABLE Customers (
    CustomerID INT NOT NULL AUTO_INCREMENT,
    CustomerName VARCHAR(45) NOT NULL,
    ContactInfo VARCHAR(200),
    PRIMARY KEY (CustomerID),
    UNIQUE (CustomerID)
);

CREATE TABLE Reservations (
    ReservationID INT NOT NULL AUTO_INCREMENT,
    customerID INT NOT NULL,
    ReservationTime DATETIME NOT NULL,
    NumberOfGuests INT NOT NULL,
    SpecialRequests VARCHAR(200),
    PRIMARY KEY (ReservationID),
    UNIQUE (ReservationID),
    FOREIGN KEY (CustomerID) REFERENCES Customers(CustomerID) 
);

CREATE TABLE DiningPreferences (
    PreferenceID INT NOT NULL AUTO_INCREMENT,
    CustomerID INT NOT NULL,
    FavoriteTable VARCHAR(45),
    DietaryRestrictions VARCHAR(200),
    PRIMARY KEY (preferenceId),
    UNIQUE (preferenceId),
    FOREIGN KEY (CustomerID) REFERENCES Customers(CustomerID) 
);

-- find reservation procedure --

DELIMITER $$

CREATE PROCEDURE findReservations(
    IN p_customerId INT
)
BEGIN
    SELECT 
        r.ReservationID, 
        r.ReservationTime, 
        r.NumberOfGuests, 
        r.SpecialRequests
    FROM Reservations r
    WHERE r.CustomerID = p_customerId;
END$$

DELIMITER ;

-- add special request procedure --

DELIMITER $$

CREATE PROCEDURE addSpecialRequest(
    IN p_reservationId INT,
    IN p_requests VARCHAR(200)
)
BEGIN
    UPDATE Reservations
    SET SpecialRequests = p_requests
    WHERE ReservationID = p_reservationId;
END$$

DELIMITER ;

-- add reservation --

DELIMITER $$

CREATE PROCEDURE addReservation(
    IN p_customerId INT,
    IN p_reservationTime DATETIME,
    IN p_numberOfGuests INT,
    IN p_specialRequests VARCHAR(200)
)
BEGIN
    INSERT INTO Reservations (CustomerID, ReservationTime, NumberOfGuests, SpecialRequests)
    VALUES (p_customerId, p_reservationTime, p_numberOfGuests, p_specialRequests);
END$$

DELIMITER ;
