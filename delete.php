<?php

require "database.php";

session_start();

if (!isset($_SESSION["user"])) {
    header("location: login.php");
    return;
  }
//Bucle Para que no borren o ingresen a un contacto con id que no existe
$id = $_GET["id"];

$statement = $conn->prepare("SELECT * FROM contacts WHERE id = :id LIMIT 1");
$statement->execute([":id" => $id]);

if ($statement->rowCount() == 0) {
    http_response_code(404);
    echo("HTTP 404 NOT FOUND");
    return;
}

$contact = $statement->fetch(PDO::FETCH_ASSOC);

if ($contact["user_id"] !== $_SESSION["user"]["id"]) {
  http_response_code(403);
  echo "HTTP 403 UNAUTHORIZED";
  return;
}

// Para borrar los contactos de la base de datos
$conn->prepare("DELETE FROM contacts WHERE id = :id")->execute([":id"=> $id]);

$_SESSION["flash"] = ["message" => "Contact {$contact['name']} deleted."];

header("Location: home.php");
