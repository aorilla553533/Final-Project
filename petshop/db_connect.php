<?php
// -----------------------------------------------
// db_connect.php — Database connection using mysqli
// Edit these values to match your phpMyAdmin setup
// -----------------------------------------------

$host     = "localhost";       // usually 'localhost'
$dbname   = "petshop_db";     // your database name in phpMyAdmin
$username = "root";            // phpMyAdmin username (default: root)
$password = "";                // phpMyAdmin password (default: empty for XAMPP)

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}
$conn->set_charset('utf8');
?>