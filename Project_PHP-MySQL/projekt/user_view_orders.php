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
    <title>Zamówienia Użytkownika</title>

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
echo "<h2>Twoje Zamówienia</h2>";

$user_id = $_SESSION["user_id"];
$conn = new mysqli("localhost", "root", "", "carrental");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = "SELECT Orders.OrderID, Cars.Brand, Cars.Model, Orders.StartDate, Orders.EndDate
          FROM Orders
          JOIN Cars ON Orders.CarID = Cars.CarID
          WHERE Orders.UserID = $user_id";

$result = $conn->query($query);

if ($result->num_rows > 0) {
    echo "<ul>";
    while ($row = $result->fetch_assoc()) {
        echo "<li>Zamówienie ID: " . $row["OrderID"] . ", Samochód: " . $row["Brand"] . " " . $row["Model"] . ", Data rozpoczęcia: " . $row["StartDate"] . ", Data zakończenia: " . $row["EndDate"] . "</li>";
    }
    echo "</ul>";
} else {
    echo "Brak zamówień.";
}

echo "<br><a href='user_dashboard.php'>Powrót do panelu użytkownika</a>";

$conn->close();
?>
</div>

</body>
<html>

