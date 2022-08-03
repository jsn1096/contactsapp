<?php

$host = "localhost";
$database = "contacts_app";
$user = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$database", $user, $password);
} catch (PDOException $e) {
    die("PDO Connection Error: " . $e->getMessage());
}