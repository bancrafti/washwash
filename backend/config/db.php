<?php
$host = 'localhost';
$db   = 'washwash'; 
$user = 'root'; 
$pass = ''; 
try {
    // Set DSN (Data Source Name)
    $dsn = "mysql:host=$host;dbname=$db;charset=utf8";
    // Create a PDO instance
    $pdo = new PDO($dsn, $user, $pass);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // If the connection is successful, display a message
    echo "Connected successfully to the database '$db'!";
} catch (PDOException $e) {
    // If there's an error, display the error message
    die("Connection failed: " . $e->getMessage());
}
?>
