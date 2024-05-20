<?php
// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conectar a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "parqueadero";
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Obtener los datos del formulario
    $placa = isset($_POST['placa']) ? $_POST['placa'] : null;
    $marca = isset($_POST['marca']) ? $_POST['marca'] : null;
    $fecha_entrada = isset($_POST['fecha_entrada']) ? $_POST['fecha_entrada'] : null;
    $tipo_vehiculo = isset($_POST['tipo_vehiculo']) ? $_POST['tipo_vehiculo'] : null;
    $tipo_pago = isset($_POST['tipo_pago']) ? $_POST['tipo_pago'] : null;

    // Verificar si el tipo de pago es válido
    $tipos_pago_validos = array("1", "2", "3", "4", "5", "6", "7", "8", "9");
    if (in_array($tipo_pago, $tipos_pago_validos)) {
        // Verificar si el vehículo ya está registrado en la tabla vehiculos
        $sql_vehiculo_existente = "SELECT id_vehiculo FROM vehiculos WHERE placa = ?";
        $stmt_vehiculo_existente = $conn->prepare($sql_vehiculo_existente);
        if ($stmt_vehiculo_existente) {
            $stmt_vehiculo_existente->bind_param("s", $placa);
            $stmt_vehiculo_existente->execute();
            $stmt_vehiculo_existente->store_result();

            if ($stmt_vehiculo_existente->num_rows > 0) {
                // Si el vehículo ya está registrado, obtener el id_vehiculo
                $stmt_vehiculo_existente->bind_result($id_vehiculo);
                $stmt_vehiculo_existente->fetch();
            } else {
                // Si el vehículo no está registrado, insertarlo en la tabla vehiculos
                $sql_insertar_vehiculo = "INSERT INTO vehiculos (placa, marca, tipo_vehiculo) VALUES (?, ?, ?)";
                $stmt_insertar_vehiculo = $conn->prepare($sql_insertar_vehiculo);
                if ($stmt_insertar_vehiculo) {
                    $stmt_insertar_vehiculo->bind_param("sss", $placa, $marca, $tipo_vehiculo);
                    if ($stmt_insertar_vehiculo->execute()) {
                        // Obtener el id_vehiculo recién insertado
                        $id_vehiculo = $stmt_insertar_vehiculo->insert_id;
                    } else {
                        echo "<script>alert('Error al insertar el vehículo en la tabla vehiculos: " . $stmt_insertar_vehiculo->error . "');</script>";
                    }
                } else {
                    echo "<script>alert('Error en la preparación de la consulta para insertar en la tabla vehiculos: " . $conn->error . "');</script>";
                }
            }
            $stmt_vehiculo_existente->close();
        } else {
            echo "<script>alert('Error en la preparación de la consulta para verificar si el vehículo ya está registrado: " . $conn->error . "');</script>";
        }

        // Si se obtuvo el id_vehiculo, insertar la información en la tabla registros
        if (isset($id_vehiculo)) {
            $sql_registros = "INSERT INTO registros (id_vehiculo, placa, marca, fecha_entrada, tipo_pago) VALUES (?, ?, ?, ?, ?)";
            $stmt_registros = $conn->prepare($sql_registros);
            if ($stmt_registros) {
                $stmt_registros->bind_param("issss", $id_vehiculo, $placa, $marca, $fecha_entrada, $tipo_pago);
                if ($stmt_registros->execute()) {
                    echo "<script>alert('Registro efectuado correctamente');</script>";
                } else {
                    echo "<script>alert('Error al insertar el registro en la tabla registros: " . $stmt_registros->error . "');</script>";
                }
            } else {
                echo "<script>alert('Error en la preparación de la consulta para insertar en la tabla registros: " . $conn->error . "');</script>";
            }
        }
    } else {
        // Mensaje de error si el tipo de pago no es válido
        echo "<script>alert('Error: Tipo de pago no válido.');</script>";
    }

    // Cerrar la conexión
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Vehículos</title>
    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
</head>

<body>
    <header>
        <!-- Header de Bootstrap -->
        <nav class="nav justify-content-center">
            <a class="nav-link active" href="index.php" aria-current="page">INICIO</a>
            <a class="nav-link active" href="insert.php">REGISTRO</a>
            <a class="nav-link active" href="salida.php">FACTURAR</a>
            <a class="nav-link active" href="delete.php">ELIMINAR</a>
            <a class="nav-link active" href="update.php">ACTUALIZAR</a>
        </nav>
    </header>

    <style>
        body {
            
            background: -webkit-linear-gradient(to right, #FFFFFF, #6DD5FA, #2980B9);
            background: linear-gradient(to right, #FFFFFF, #6DD5FA, #2980B9);
            background-image: url('./imagen/parqueo.jpg');
            background-size: cover; /* Esto hará que la imagen cubra todo el fondo */
            background-repeat: no-repeat; /* Esto evitará que la imagen se repita */
            background-position: center; /* Esto centrará la imagen en el fondo */
        }

        .form-label {
            color: yellow; /* Cambia esto al color que desees */
        }
        
        h1 {
            color:#2980B9;
        }

    </style>

    <main>
        <div class="container text-center">
            <h1>REGISTRO VEHICULAR</h1>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <form action="insert.php" method="post">
                        <div class="mb-3">
                            <label for="placa" class="form-label">Placa</label>
                            <input type="text" name="placa" class="form-control" id="placa" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="marca" class="form-label">Marca</label>
                            <input type="text" name="marca" class="form-control" id="marca" required>
                        </div>
                        <div class="mb-3">
                            <label for="fecha_entrada" class="form-label">Fecha_entrada</label>
                            <input type="datetime-local" name="fecha_entrada" class="form-control" id="fecha_entrada" required>
                        </div>
                        <div class="mb-3">
                            <label for="tipo_vehiculo" class="form-label">Tipo_vehículo</label>
                            <select class="form-select" name="tipo_vehiculo" id="tipo_vehiculo" aria-label="Default select example" required>
                                <option selected>Seleccione una opción</option>
                                <option value="automovil">Automovil</option>
                                <option value="motocicleta">Motocicleta</option>
                                <option value="bicicleta">Bicicleta</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="tipo_pago" class="form-label">Tipo_Pago</label>
                            <select class="form-select" name="tipo_pago" id="tipo_pago" aria-label="Default select example" required>
                                <option selected>Seleccione una opción</option>
                                <option value="1">automovil_mensualidad </option>
                                <option value="2">automovil_media_Jornada </option>
                                <option value="3">automovil_por_hora </option>
                                <option value="4">motocicleta_mensualidad </option>
                                <option value="5">motocicleta_media_jornada </option>
                                <option value="6">motocicleta_por_hora </option>
                                <option value="7">bicicleta_mensualidad </option>
                                <option value="8">bicicleta_media_jornada </option>
                                <option value="9">bicicleta_por_hora </option>
                            </select>
                        </div>

                        
                        
                        <button type="submit" name="registrar" class="btn btn-primary">Registrar</button>
                        <a href="index.php" class="btn btn-primary">Regresar</a>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <!-- Footer de Bootstrap -->
        <div class="container-fluid bg-dark text-white text-center">
            <div class="container py-3">
                <p class="m-0">Copyrigth &copy; 2023</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
        integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3"
        crossorigin="anonymous">
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
        integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz"
        crossorigin="anonymous">
    </script>
</body>

</html>
