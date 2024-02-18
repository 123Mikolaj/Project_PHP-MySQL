<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Użytkownika</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar navbar-inverse navbar-toggleable-md">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="user_dashboard.php">Home</a>
            </div>
            <div class="collapse navbar-collapse" id="myNavbar">
                <ul class="nav navbar-nav">
                    <li><a href="add_customer_data.php">Profil</a></li>
                    <li><a href="user_view_orders.php">Zamówienia</a></li>
                    <li><a href="make_reservation.php">Zarezerwuj samochód</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span>Wyloguj się</a></li>
                </ul>
            </div>
        </div>
    </nav>

<div class="main_content">    
    <?php
    // Create a new mysqli connection
    $conn = new mysqli("localhost", "root", "", "carrental");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $car_id = $_POST["car"];
        $start_date = $_POST["start_date"];
        $end_date = $_POST["end_date"];
        $user_id = $_SESSION["user_id"];


        if ($end_date <= $start_date) {
            echo "Nieprawidłowy zakres dat.";
            exit();
        }

        $add_order_query = "INSERT INTO Orders (CarID, UserID, StartDate, EndDate)
                    VALUES ($car_id, $user_id, '$start_date', '$end_date')";

        if ($conn->query($add_order_query) === TRUE) {
            echo "Rezerwacja zakończona sukcesem.";
            echo "<br><a href='user_dashboard.php'>Powrót do panelu użytkownika</a>";
        } else {
            echo "Błąd podczas dodawania rezerwacji: " . $conn->error;
            echo "<br><a href='user_dashboard.php'>Powrót do panelu użytkownika</a>";
        }

        $conn->close(); // Close the connection when done
    }
    ?>
</div>
</body>
</html>

