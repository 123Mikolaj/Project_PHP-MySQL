<?php
session_start();

// Sprawdzanie, czy użytkownik jest zalogowany
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
    <title>Profil Użytkownika</title>

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
$conn = new mysqli("localhost", "root", "", "carrental");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Sprawdzanie, czy użytkownik ma już przypisane dane w tabeli Customers
$userID = $_SESSION['user_id'];
$sqlCheckCustomerData = "SELECT * FROM Customers WHERE UserID = '$userID'";
$resultCheckCustomerData = $conn->query($sqlCheckCustomerData);

// Inicjowanie zmiennych pustymi ciągami
$customerFirstName = "";
$customerLastName = "";
$customerEmail = "";
$customerPhoneNumber = "";
$customerAddress = "";

// Dane istnieją, wypełniamy formularz aktualnymi danymi
if ($resultCheckCustomerData->num_rows > 0) {
    $customerData = $resultCheckCustomerData->fetch_assoc();
    $customerFirstName = $customerData['FirstName'];
    $customerLastName = $customerData['LastName'];
    $customerEmail = $customerData['Email'];
    $customerPhoneNumber = $customerData['PhoneNumber'];
    $customerAddress = $customerData['Address'];
}

// Obsługa formularza
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pobieranie danych z formularza
    $customerFirstName = $_POST['firstName'];
    $customerLastName = $_POST['lastName'];
    $customerEmail = $_POST['email'];
    $customerPhoneNumber = $_POST['phoneNumber'];
    $customerAddress = $_POST['address'];

    // Dodawanie danych do tabeli Customers z przypisaniem do konkretnego użytkownika
    if ($resultCheckCustomerData->num_rows > 0) {
        // Aktualizacja danych, jeżeli już istnieją
        $sqlUpdateCustomer = "UPDATE Customers 
                              SET FirstName = '$customerFirstName', LastName = '$customerLastName', 
                                  Email = '$customerEmail', PhoneNumber = '$customerPhoneNumber', 
                                  Address = '$customerAddress'
                              WHERE UserID = '$userID'";
    } else {
        // Dodanie nowych danych, jeżeli nie istnieją
        $sqlUpdateCustomer = "INSERT INTO Customers (UserID, FirstName, LastName, Email, PhoneNumber, Address)
                              VALUES ('$userID', '$customerFirstName', '$customerLastName', '$customerEmail', '$customerPhoneNumber', '$customerAddress')";
    }

    if ($conn->query($sqlUpdateCustomer) === TRUE) {
        echo "Zaktualizowano dane klienta pomyślnie!";
    } else {
        echo "Błąd: " . $sqlUpdateCustomer . "<br>" . $conn->error;
    }
}

$conn->close();
?>    
    <h1>Formularz dodawania/edycji danych klienta</h1>

<div class="forms">
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="firstName">Imię:</label>
    <input type="text" name="firstName" value="<?php echo $customerFirstName; ?>" required>

    <label for="lastName">Nazwisko:</label>
    <input type="text" name="lastName" value="<?php echo $customerLastName; ?>" required>

    <label for="email">Email:</label>
    <input type="email" name="email" value="<?php echo $customerEmail; ?>" required>

    <label for="phoneNumber">Numer telefonu:</label>
    <input type="text" name="phoneNumber" value="<?php echo $customerPhoneNumber; ?>">

    <label for="address">Adres:</label>
    <textarea name="address" rows="4" required><?php echo $customerAddress; ?></textarea>

    <input type="submit" value="Zapisz">
    <br><a href='user_dashboard.php'>Powrót do panelu użytkownika</a>
</form>
</div>
</div>

</body>
</html>


