<?php
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] !== "admin") {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zarządzanie Użytkownikami</title>

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
    echo "<h1>Zarządzaj Użytkownikami</h1>";

    $conn = new mysqli("localhost", "root", "", "carrental");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Handle role updates
        if (isset($_POST["user_id"])) {
            $user_id = $_POST["user_id"];

            // Check if the delete button is clicked
            if (isset($_POST["delete_user"])) {
            // Delete the user from the Customers table first
            $delete_customer_query = "DELETE FROM Customers WHERE UserID = $user_id";
            $conn->query($delete_customer_query);

            // Now you can safely delete the user from the Users table
            $delete_user_query = "DELETE FROM Users WHERE UserID = $user_id";
            $conn->query($delete_user_query);

            // Redirect to prevent form resubmission on refresh
            header("Location: ".$_SERVER['PHP_SELF']);
            exit();
            }       


            // Retrieve the current role
            $current_role_query = "SELECT UserRole FROM Users WHERE UserID = $user_id";
            $current_role_result = $conn->query($current_role_query);

            if ($current_role_result->num_rows > 0) {
                $current_role = $current_role_result->fetch_assoc()["UserRole"];

                // Toggle the role
                $new_role = ($current_role === "admin") ? "user" : "admin";

                // Update the user role in the database
                $update_query = "UPDATE Users SET UserRole = '$new_role' WHERE UserID = $user_id";
                $conn->query($update_query);

                // Redirect to prevent form resubmission on refresh
                header("Location: ".$_SERVER['PHP_SELF']);
                exit();
            }
        }
    }

    // Fetch and display user data in a table
    $query = "SELECT * FROM Users";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        echo "<table border='1'>";
echo "<tr><th>UserID</th><th>Username</th><th>UserRole</th><th>Actions</th></tr>";
while ($row = $result->fetch_assoc()) {
    // Check if the current user is not the logged-in user
    if ($row["UserID"] != $_SESSION["user_id"]) {
        echo "<tr>";
        echo "<td>" . $row["UserID"] . "</td>";
        echo "<td>" . $row["Username"] . "</td>";
        echo "<td>" . $row["UserRole"] . "</td>";
        echo "<td>";

        // Add a container div for the buttons
        echo "<div style='display: flex;'>";

        // Add a form for changing the role
        echo "<form method='post' style='margin-right: 10px; background-color: transparent; box-shadow: none'>";
        echo "<button type='submit' name='user_id' value='" . $row["UserID"] . "' >Zmień rolę</button>";
        echo "</form>";

        // Add a form for deleting the user
        echo "<form method='post' style='background-color: transparent; box-shadow: none'>";
        echo "<input type='hidden' name='user_id' value='" . $row["UserID"] . "'>";
        echo "<button type='submit' name='delete_user'>Usuń użytkownika</button>";
        echo "</form>";

        // Close the container div
        echo "</div>";

        echo "</td>";
        echo "</tr>";
    } else {
        // Display user information without form elements
        echo "<tr>";
        echo "<td>" . $row["UserID"] . "</td>";
        echo "<td>" . $row["Username"] . "</td>";
        echo "<td>" . $row["UserRole"] . "</td>";
        echo "<td>Logged-in user</td>";
        echo "</tr>";
    }
}
echo "</table>";

    } else {
        echo "Brak użytkowników.";
    }

    // Add a button to go back to admin_dashboard.php
    echo "<a href='admin_dashboard.php'><br>Powrót do panelu admina</a>";

    $conn->close();
    ?>
</div>












