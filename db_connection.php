<?php
$servername = "localhost";
$username = "root";
$password = ""; // often empty for local MySQL installations
$dbname = "discovercebu"; // replace with your actual database name

$con = new mysqli($servername, $username, $password, $dbname);

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

?>
