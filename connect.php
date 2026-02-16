<?php
/*
    NAME: SEVIDAL, JELSY F.
    FILE NAME: connect.php
    DATE FINISHED: 04-20-2025
    PURPOSE: Establish a secure connection to the hardware_store database using PDO
*/

$host = "localhost";         
$db = "hardware_store";      
$user = "root";              
$pass = "";                  
$charset = "utf8mb4";       

try {
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $pdo = new PDO($dsn, $user, $pass);
    
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die('Database Connection Failed: ' . $e->getMessage());
}
?>
