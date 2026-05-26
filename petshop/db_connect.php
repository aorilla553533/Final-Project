<?php
$host     = getenv('MYSQLHOST');
$dbname   = getenv('MYSQLDATABASE');
$username = getenv('MYSQLUSER');
$password = getenv('MYSQLPASSWORD');
$port     = getenv('MYSQLPORT') ?: 3306;

$conn = new mysqli($host, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}
$conn->set_charset('utf8');
?>
