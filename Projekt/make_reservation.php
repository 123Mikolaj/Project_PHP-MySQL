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
    <title>Rezerwacja Samochodu</title>

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
echo "<h1>Zarezerwuj Samochód</h1>";

$conn = new mysqli("localhost", "root", "", "carrental");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Sprawdzanie, czy użytkownik ma już przypisane dane w tabeli Customers
$userID = $_SESSION['user_id'];
$sqlCheckCustomerData = "SELECT * FROM Customers WHERE UserID = '$userID'";
$resultCheckCustomerData = $conn->query($sqlCheckCustomerData);

// Dane istnieją, wypisujemy aktualne dane
if ($resultCheckCustomerData->num_rows > 0) {
    $customerData = $resultCheckCustomerData->fetch_assoc();
    $customerFirstName = $customerData['FirstName'];
    $customerLastName = $customerData['LastName'];
    $customerEmail = $customerData['Email'];
    $customerPhoneNumber = $customerData['PhoneNumber'];
    $customerAddress = $customerData['Address'];

    echo "<div>";
    echo "<p><strong>Imię:</strong> $customerFirstName</p>";
    echo "<p><strong>Nazwisko:</strong> $customerLastName</p>";
    echo "<p><strong>Email:</strong> $customerEmail</p>";
    echo "<p><strong>Numer telefonu:</strong> $customerPhoneNumber</p>";
    echo "<p><strong>Adres:</strong> $customerAddress</p>";
    echo "</div>";

    // Wyświetlanie formularza rezerwacji
    $query = "SELECT * FROM Cars";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        echo "<form action='reservation_process.php' method='post'>";
        echo "<label for='car'>Wybierz samochód:</label>";
        echo "<select id='car' name='car' required>";
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . $row["CarID"] . "'>" . $row["Brand"] . " " . $row["Model"] . "</option>";
        }
        echo "</select>";

        echo "<label for='start_date'>Data rozpoczęcia rezerwacji:</label>";
        echo "<input type='date' id='start_date' name='start_date' required>";

        echo "<label for='end_date'>Data zakończenia rezerwacji:</label>";
        echo "<input type='date' id='end_date' name='end_date' required>";

        // ... (inne pola formularza)

        echo "<input type='submit' value='Zarezerwuj'>";
        echo "<br><a href='user_dashboard.php'>Powrót do panelu użytkownika</a>";
        echo "</form>";
    } else {
        echo "Brak dostępnych samochodów.";
    }
} else {
    echo "Uzupełnij swoje dane przed zarezerwowaniem samochodu.";
    echo "<br><a href='add_customer_data.php'>Profil</a>";
}

$conn->close();
?>
</div>
</body>
</html>


