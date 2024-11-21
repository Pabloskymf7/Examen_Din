<?php
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
session_start();

// Verificar si se han enviado datos por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $email = $_POST['email'];
    $passwd = md5($_POST['passwd']);
    $usuario = $_POST['user'];

    // Preparar la consulta SQL

    $sql = "SELECT NombreUsuario,Rol FROM usuarios WHERE Email='$email' AND Passwd='$passwd'";
    $result = $conn->query($sql);


    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['NombreUsuario'] = $row['NombreUsuario'];
        $rol = $row['Rol'];

        // Redirigir según el rol
        if ($rol == "Admin") {
            header('Location: index.html');
        } elseif ($rol == "Trabajador") {
            header('Location: logicaInicioSesion.php');
        }
    } else {
        echo "Usuario no encontrado.";
    }
}

// Cerrar la conexión
$conn->close();
?>

<html>

<body>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Venta Discos</title>
        <link rel="preload" href="styles.css" as="style" />
        <link href="styles.css" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $(document).ready(function() {

                    console.log("Página cargada, cargando discos..");

                    cargarDiscos();
                    cargarMerch();
                });

                function cargarDiscos() {
                    $.ajax({
                        type: "GET",
                        url: "logicaMostrarDiscos.php",
                        dataType: "json",
                        success: function(respuesta) {
                            if (respuesta.success) {
                                const tbody = $('#discosTableBody');
                                tbody.empty();
                                if (respuesta.discos && respuesta.discos.length > 0) {
                                    respuesta.discos.forEach(function(discos) {
                                        const row = `<tr>
                                <td>${discos.IdDisco}</td>
                                <td>${discos.NombreDisco}</td>
                                <td>${discos.Artista}</td>
                                <td>${discos.Genero}</td>
                                <td>${discos.Discografia}</td>
                                <td>${discos.Precio}</td>
                            </tr>`;
                                        tbody.append(row);
                                    });
                                } else {
                                    tbody.append("<tr><td colspan='4'>No se encontraron palabras para traducir</td></tr>");
                                }
                            } else {
                                alert("Error: " + respuesta.error);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("Error al cargar discos:", error);
                            alert("Error al cargar traduccion");
                        },
                    });
                }



                function cargarMerch() {
                    $.ajax({
                        type: "GET",
                        url: "logicaMostrarMerch.php",
                        dataType: "json",
                        success: function(respuesta) {
                            if (respuesta.success) {
                                const tbody = $('#ingresosTabla');
                                tbody.empty();
                                if (respuesta.merch && respuesta.merch.length > 0) {
                                    respuesta.merch.forEach(function(merch) {
                                        const row = `<tr>
                                    <td>${merch.NombreProd}</td>
                                    <td>${merch.TipoProd}</td>
                                    <td>${merch.Precio}</td>
                                </tr>`;
                                        tbody.append(row);
                                    });
                                } else {
                                    tbody.append("<tr><td colspan='4'>No se encontraron ingresos</td></tr>");
                                }
                            } else {
                                alert("Error: " + respuesta.error);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("Error al cargar gastos:", error);
                            alert("Error al cargar gastos");
                        },
                    });
                }



            });
        </script>
    </head>

    <body>
        <div id="MuestraDiscos">
            <div class="container mt-5">
                <h1 class="text-center">Discos de la tienda: </h1>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Referencia Disco</th>
                            <th>Nombre Disco</th>
                            <th>Artista</th>
                            <th>Genero</th>
                            <th>Discografia</th>
                            <th>Precio</th>
                        </tr>
                    </thead>
                    <tbody id="discosTableBody">
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-5">
            <h2 class="text-center mb-3">Lista de Gastos</h2>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-primary text-center">
                        <tr>
                            <th>Nombre Producto</th>
                            <th>Tipo Producto</th>
                            <th>Precio</th>
                        </tr>
                    </thead>
                    <tbody id="ingresosTabla">

                    </tbody>

                </table>
            </div>
        </div>

        <div class="d-flex justify-content-center">
            <a href="inicioSesion.html" class="btn btn-primary">Ir a pagina inicio de sesión</a>
        </div>

        <div class="container mt-5" class="text-center" id="calculadora">
            <label for="tipo" class="form-label">Calculadora</label>
            <input type="number" id="dinero" placeholder="Escribe cantidad">

            <select class="form-select" id="calc" name="calc" required>
                <option value="" selected disabled>Selecciona una moneda</option>
                <option value="usd" id="usd">usd</option>
                <option value="gpd" id="gpd">gpd</option>
                <option value="jpy" id="jpy">jpy</option>
            </select>
            </br>
            <button id="botonCalcular">Calcular</button>
        </div>


    </body>

    </html>
</body>

</html>