<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "examen_pablo";

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Conexión fallida: " . $conn->connect_error]);
    exit();
}

if (isset($_POST['id'])) {

    $id = $_POST['id'];

    // Preparar y ejecutar la consulta de eliminación
    $stmt = $conn->prepare("DELETE FROM disco WHERE IdDIsco = ?");
    $stmt->bind_param("i", $id);

    // Ejecutar la eliminación
    if ($stmt->execute()) {
        $response = array("success" => true, "message" => "Registro eliminado con éxito");
    } else {
        $response = array("success" => false, "error" => "Error al eliminar el registro: " . $stmt->error);
    }

    $stmt->close();
} else {
    $response = array("success" => false, "message" => "ID inválido o no proporcionado");
}

$conn->close();

// Devolver la respuesta como JSON
echo json_encode($response);
