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
    <title>Wszystkie Zamówienia</title>

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
                <a class="navbar-brand" href="admin_dashboard.php">Home</a>
            </div>
            <div class="collapse navbar-collapse" id="myNavbar">
                <ul class="nav navbar-nav">
                    <li><a href="admin_view_orders.php">Zobacz wszystkie zamówienia</a></li>
                    <li><a href="manage_users.php">Zarządzaj użytkownikami</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span>Wyloguj się</a></li>
                </ul>
            </div>
        </div>
    </nav>
    
<div class="main_content">
    <?php
    echo "<h1>Wszystkie Zamówienia</h1>";

    $user_id = $_SESSION["user_id"];
    $conn = new mysqli("localhost", "root", "", "carrental");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Handle order deletion
        if (isset($_POST["order_id"])) {
            $order_id = $_POST["order_id"];

            // Delete the order from the database
            $delete_query = "DELETE FROM Orders WHERE OrderID = $order_id";
            $conn->query($delete_query);

            // Redirect to prevent form resubmission on refresh
            header("Location: ".$_SERVER['PHP_SELF']);
            exit();
        }
    }

    $query = "SELECT Orders.OrderID, Orders.UserID, Users.Username, Cars.Brand, Cars.Model, Orders.StartDate, Orders.EndDate
            FROM Orders
            JOIN Cars ON Orders.CarID = Cars.CarID
            JOIN Users ON Orders.UserID = Users.UserID";

    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        echo "<table border='1'>";
        echo "<tr><th>UserID</th><th>Username</th><th>Car</th><th>Start Date</th><th>End Date</th><th>Actions</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["UserID"] . "</td>";
            echo "<td>" . $row["Username"] . "</td>";
            echo "<td>" . $row["Brand"] . " " . $row["Model"] . "</td>";
            echo "<td>" . $row["StartDate"] . "</td>";
            echo "<td>" . $row["EndDate"] . "</td>";
            echo "<td>";

            // Add a form for deleting the order
            echo "<form method='post' style='display: inline-block; margin-right: 10px; margin-bottom: 10px; background-color: transparent; box-shadow: none'>";
            echo "<input type='hidden' name='order_id' value='" . $row["OrderID"] . "'>";
            echo "<button type='submit' name='delete_order'>Usuń zamówienie</button>";
            echo "</form>";

            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "Brak zamówień.";
    }

    echo "<a href='admin_dashboard.php'><br>Powrót do panelu użytkownika</a>";

    $conn->close();
    ?>
</div>

