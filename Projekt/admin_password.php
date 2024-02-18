<?php
$password = 'haslo';
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
echo $hashed_password;

//INSERT INTO Users (Username, PasswordHash, UserRole) VALUES ('admin', '$2y$10$yFa2A.L8.TcJblY3HX6vSuT3Egy9PeILfGwe895jX0WLTLDSuYfii', 'admin');
?>

