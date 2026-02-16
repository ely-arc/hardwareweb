<?php
/*
    NAME: SEVIDAL, JELSY F.
    FILE NAME: db.php
    DATE FINISHED: 04-8-2025
    PURPOSE: stablish a connection to the MySQL database (hardware_store) PDO to connect so login.php and register.php can interact with the database.
*/

$servername = "localhost";
$username = "root";  
$password = "";     
$databname = "hardware_store";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$databname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo("connection failed: " . $e->getMessage());
}
?>

