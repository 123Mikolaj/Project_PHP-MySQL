<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Rental</title>

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
                <a class="navbar-brand" href="index.php">Home</a>
            </div>
            <div class="collapse navbar-collapse" id="myNavbar">
                <ul class="nav navbar-nav">
                    <li><a href="car_list.php">Nasze Samochody</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
                    <li><a href="register.php"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
                </ul>
            </div>
        </div>
    </nav>

<div class="forms">
    <?php
    // Połączenie z bazą danych i obsługa logowania

    session_start();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Pobieranie danych z formularza
        $username = $_POST["username"];
        $password = $_POST["password"];

        // Sprawdzanie danych w bazie danych (zakładając, że istnieje połączenie z bazą danych)
        $conn = new mysqli("localhost", "root", "", "carrental");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Pobieranie hasła dla danego użytkownika
        $query = "SELECT UserID, PasswordHash, UserRole FROM Users WHERE Username = '$username'";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Porównanie hasła
        if (password_verify($password, $row["PasswordHash"])) {
            $_SESSION["user_id"] = $row["UserID"];
            $_SESSION["user_role"] = $row["UserRole"];

            // Redirect based on user role
            if ($row["UserRole"] == 'admin') {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: user_dashboard.php");
            }

            exit();
        } else {
            echo "Nieprawidłowe hasło.";
            echo "<br><a href='index.php'>Powrót na stronę główną</a>";
        }
    } else {
        echo "Użytkownik nie istnieje.";
        echo "<br><a href='index.php'>Powrót na stronę główną</a>";
    }
    }
    ?>
</div>
</body>
</html>
