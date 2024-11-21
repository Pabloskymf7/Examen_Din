<?php
header('Content-Type: application/json');
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

// Verificar si se han enviado datos por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $tipo = $_POST['tipo'];

    $sql = "INSERT INTO merchan (NombreProd, TipoProd, Precio) VALUES (?, ?, ?)";

    if (isset($_POST['nombre'], $_POST['precio'], $_POST['tipo'])) {
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Error al preparar la consulta: " . $conn->error);
        }

        $stmt->bind_param("ssd", $nombre, $tipo, $precio);

        if ($stmt->execute()) {
            $response = array("success" => true);
        } else {
            $response = array("success" => false, "message" => "Error al insertar los datos " . $stmt->error);
        }

        $stmt->close();
    } else {
        $response = array("success" => false, "message" => "No se recibió el dato 'ResultadoTotal'.");
    }

    // Cerrar la declaración

} else {
    echo "No se recibieron datos por POST.";
}

echo json_encode($response);
// Cerrar la conexión
$conn->close();
