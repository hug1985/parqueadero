<?php

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Obtener los datos del formulario
    $placa = isset($_POST['placa']) ? $_POST['placa'] : null;
    $fecha_salida = isset($_POST['fecha_salida']) ? $_POST['fecha_salida'] : null;
    $tipo_vehiculo = isset($_POST['tipo_vehiculo']) ? $_POST['tipo_vehiculo'] : null;

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

    // Array asociativo para almacenar información de tarifas
    $tarifas_asociativas = [
        '1' => ['id' => 1, 'tipo_pago' => 'automovil_mensualidad' , 'tarifa' => 300000],
        '2' => ['id' => 2, 'tipo_pago' => 'automovil_media_jornada', 'tarifa' =>  5000],
        '3' => ['id' => 3, 'tipo_pago' => 'automovil_por_hora', 'tarifa' => 2500],
        '4' => ['id' => 4, 'tipo_pago' => 'motocicleta_mensualidad', 'tarifa' => 150000],
        '5' => ['id' => 5, 'tipo_pago' => 'motocicleta_media_jornada', 'tarifa' => 2500],
        '6' => ['id' => 6, 'tipo_pago' => 'motocicleta_por_hora', 'tarifa' => 1250],
        '7' => ['id' => 7, 'tipo_pago' => 'bicicleta_mensualidad', 'tarifa' => 60000],
        '8' => ['id' => 8, 'tipo_pago' => 'bicicleta_media_jornada', 'tarifa' => 2000],
        '9' => ['id' => 9, 'tipo_pago' => 'bicicleta_por_hora', 'tarifa' => 1000]
    ];

    // Obtener el id_vehiculo, la fecha_entrada, y el tipo_pago de la tabla registros
    $sql_select = "SELECT id_vehiculo, fecha_entrada, tipo_pago FROM registros WHERE placa = ? AND fecha_salida IS NULL";
    $stmt_select = $conn->prepare($sql_select);
    if ($stmt_select) {
        $stmt_select->bind_param("s", $placa);
        $stmt_select->execute();
        $stmt_select->store_result();
        if ($stmt_select->num_rows > 0) {
            $stmt_select->bind_result($id_vehiculo, $fecha_entrada, $tipo_pago);
            $stmt_select->fetch();

            // Verificar si el tipo de pago es válido y obtener la tarifa asociativa
            if (array_key_exists($tipo_pago, $tarifas_asociativas)) {
                $tarifa_asociativa = $tarifas_asociativas[$tipo_pago];
                $tarifa = $tarifa_asociativa['tarifa'];

                    // Calcular el total a pagar según el tipo de pago
                    switch ($tipo_pago) {
                        case '1':
                            // Calcular la diferencia de tiempo en meses para automóvil
                            $diferencia_tiempo = ($fecha_salida && $fecha_entrada) ? (strtotime($fecha_salida) - strtotime($fecha_entrada)) / (30 * 24 * 60 * 60) : 0;
                            $total_pagar = $tarifa * $diferencia_tiempo;
                            break;
                        case '2':
                            // Calcular la diferencia de tiempo en medias jornadas para automóvil
                            $diferencia_tiempo = ($fecha_salida && $fecha_entrada) ? (strtotime($fecha_salida) - strtotime($fecha_entrada)) / (12 * 60 * 60) : 0;
                            $total_pagar = $tarifa * $diferencia_tiempo;
                            break;
                        case '3':
                            // Calcular la diferencia de tiempo en horas para automóvil
                            $diferencia_tiempo = ($fecha_salida && $fecha_entrada) ? (strtotime($fecha_salida) - strtotime($fecha_entrada)) / (60 * 60) : 0;
                            $total_pagar = $tarifa * $diferencia_tiempo;
                            break;
                        case '4':
                            // Calcular la diferencia de tiempo en meses para motocicleta
                            $diferencia_tiempo = ($fecha_salida && $fecha_entrada) ? (strtotime($fecha_salida) - strtotime($fecha_entrada)) / (30 * 24 * 60 * 60) : 0;
                            $total_pagar = $tarifa * $diferencia_tiempo;
                            break;
                        case '5':
                            // Calcular la diferencia de tiempo en medias jornadas para motocicleta
                            $diferencia_tiempo = ($fecha_salida && $fecha_entrada) ? (strtotime($fecha_salida) - strtotime($fecha_entrada)) / (12 * 60 * 60) : 0;
                            $total_pagar = $tarifa * $diferencia_tiempo;
                            break;
                        case '6':
                            // Calcular la diferencia de tiempo en horas para motocicleta
                            $diferencia_tiempo = ($fecha_salida && $fecha_entrada) ? (strtotime($fecha_salida) - strtotime($fecha_entrada)) / (60 * 60) : 0;
                            $total_pagar = $tarifa * $diferencia_tiempo;
                            break;
                        case '7':
                            // Calcular la diferencia de tiempo en meses para bicicleta
                            $diferencia_tiempo = ($fecha_salida && $fecha_entrada) ? (strtotime($fecha_salida) - strtotime($fecha_entrada)) / (30 * 24 * 60 * 60) : 0;
                            $total_pagar = $tarifa * $diferencia_tiempo;
                            break;
                        case '8':
                            // Calcular la diferencia de tiempo en medias jornadas para bicicleta
                            $diferencia_tiempo = ($fecha_salida && $fecha_entrada) ? (strtotime($fecha_salida) - strtotime($fecha_entrada)) / (12 * 60 * 60) : 0;
                            $total_pagar = $tarifa * $diferencia_tiempo;
                            break;
                        case '9':
                            // Calcular la diferencia de tiempo en horas para bicicleta
                            $diferencia_tiempo = ($fecha_salida && $fecha_entrada) ? (strtotime($fecha_salida) - strtotime($fecha_entrada)) / (60 * 60) : 0;
                            $total_pagar = $tarifa * $diferencia_tiempo;
                            break;
                            default:
                            // Tipo de pago no válido
                                echo "<script>alert('No se encontró la tarifa correspondiente al tipo de pago $tipo_pago');</script>";
                            break;
                    }

                // Calcular el total a pagar
                $total_pagar = $tarifa * $diferencia_tiempo;

                // Actualizar la fecha de salida y el total a pagar en la tabla registros
                $sql_update = "UPDATE registros SET fecha_salida = ?, total_pagar = ? WHERE id_vehiculo = ? AND fecha_salida IS NULL";
                $stmt_update = $conn->prepare($sql_update);
                if ($stmt_update) {
                    $stmt_update->bind_param("sdi", $fecha_salida, $total_pagar, $id_vehiculo);
                    if ($stmt_update->execute()) {
                        // Mostrar la alerta con el total a pagar y la placa
                        echo "<script>alert('Registro insertado correctamente. Placa: $placa, Total a pagar: $total_pagar');</script>";
                    } else {
                        echo "<script>alert('Error al actualizar la fecha de salida y el total a pagar en la tabla registros: " . $stmt_update->error . "');</script>";
                    }
                } else {
                    echo "<script>alert('Error en la preparación de la consulta para actualizar la fecha de salida y el total a pagar en la tabla registros: " . $conn->error . "');</script>";
                }
            } else {
                echo "<script>alert('No se encontró la tarifa correspondiente al tipo de pago $tipo_pago');</script>";
            }
        } else {
            echo "<script>alert('No se encontró un vehículo con la placa $placa en el parqueadero');</script>";
        }
        $stmt_select->close();
    } else {
        echo "<script>alert('Error en la preparación de la consulta para obtener el id del vehículo, la fecha de entrada y el tipo de pago: " . $conn->error . "');</script>";
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
    <title>FACTURACIÓN PARQUEADERO</title>
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


    <div class="container mt-5">
        <h1 class="text-center">FACTURACIÓN PARQUEADERO</h1>
        <form action="salida.php" method="post" class="mt-4">

            <div class="mb-3">
                <label for="placa" class="form-label">Placa del Vehículo</label>
                <input type="text" class="form-control" id="placa" name="placa" required>
            </div>

            <div class="mb-3">
                <label for="tipo_vehiculo" class="form-label">Tipo vehículo</label>
                <select class="form-select" name="tipo_vehiculo" id="tipo_vehiculo" aria-label="Default select example" required>
                    <option selected>Seleccione una opción</option>
                    <option value="automovil">Automovil</option>
                    <option value="motocicleta">Motocicleta</option>
                    <option value="bicicleta">Bicicleta</option>
                </select>
            </div>

            
            <div class="mb-3">
                <label for="fecha_salida" class="form-label">Fecha Salida</label>
                <input type="datetime-local" name="fecha_salida" class="form-control" id="fecha_salida" required>
            </div>

            
            <button type="submit" class="btn btn-primary">Calcular Total a Pagar</button>
        </form>
    </div>

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
