<?php
header("Content-Type: application/json");
// Datos de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "examen_pablo";

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}


// Preparar la consulta SQL

$sql = "SELECT * FROM merchan";
$result = $conn->query($sql);

$merch = array();

if ($result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {
        $merch[] = $row;
    }
} else {
    // Si no hay resultados
    echo json_encode(array("success" => false, "error" => "No se encontraron discos"));
    exit();
}


// Cerrar la conexión
$conn->close();

echo json_encode(array("success" => true, "merch" => $merch));
