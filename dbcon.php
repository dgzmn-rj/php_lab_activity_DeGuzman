<?php
$conn = new mysqli("localhost" , "root", "", "php.db");

if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

?>