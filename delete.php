<?php

include 'conexion.php'; 

if (isset($_GET['placa'])) {
    // Validar que la placa tenga el formato correcto (letras, números y guiones)
    if (preg_match('/^[a-zA-Z0-9\-]+$/', $_GET['placa'])) {
        $placa = filter_var($_GET['placa'], FILTER_SANITIZE_STRING); // Placa del vehículo a eliminar

        try {
            $conection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Eliminar el registro del vehículo de la tabla registros
            $sql_registros = "DELETE FROM registros WHERE placa = :placa";
            $stmt_registros = $conection->prepare($sql_registros);
            $stmt_registros->bindParam(':placa', $placa, PDO::PARAM_STR);
            $stmt_registros->execute();

            // Eliminar el vehículo de la tabla vehiculos
            $sql_vehiculos = "DELETE FROM vehiculos WHERE placa = :placa";
            $stmt_vehiculos = $conection->prepare($sql_vehiculos);
            $stmt_vehiculos->bindParam(':placa', $placa, PDO::PARAM_STR);
            $stmt_vehiculos->execute();

            // Verificar si se eliminó alguna fila en ambas tablas
            if ($stmt_registros->rowCount() > 0 && $stmt_vehiculos->rowCount() > 0) {
                echo '<div class="alert alert-success" role="alert">Vehículo eliminado correctamente</div>';
            } else {
                echo '<div class="alert alert-warning" role="alert">No se encontró ningún vehículo con la placa ' . $placa . '</div>';
            }
        } catch (PDOException $e) {
            echo '<div class="alert alert-danger" role="alert">Error al eliminar el vehículo: ' . $e->getMessage() . '</div>';
        }
    } else {
        echo '<div class="alert alert-danger" role="alert">Formato de placa no válido</div>';
    }
} else {
    echo '<div class="alert alert-danger" role="alert">No se ha especificado una placa de vehículo válida.</div>';
}

?>




<!doctype html>
<html lang="en">

<head>
    <title>Eliminar Vehículo</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">

</head>

<body>
    <header>
        <!-- Header con bootstrap -->
        <nav class="nav justify-content-center">
            <a class="nav-link active" href="index.php" aria-current="page">INICIO</a>
            <a class="nav-link active" href="delete.php">ELIMINAR</a>
            <a class="nav-link active" href="insert.php">REGISTRO</a>
            <a class="nav-link active" href="salida.php">FACTURAR</a>
            <a class="nav-link active" href="update.php">ACTUALIZAR</a>
            
        </nav>
    </header>

    <style>
        body{
            background: -webkit-linear-gradient(to right, #FFFFFF, #6DD5FA, #2980B9);  /* Chrome 10-25, Safari 5.1-6 */
            background: linear-gradient(to right, #FFFFFF, #6DD5FA, #2980B9); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
            background-image: url('./imagen/parqueo.jpg');
            background-size: cover; /* Esto hará que la imagen cubra todo el fondo */
            background-repeat: no-repeat; /* Esto evitará que la imagen se repita */
            background-position: center; /* Esto centrará la imagen en el fondo */

        }

        .form-label {
            color: yellow; /* Cambia esto al color que desees */
        }
        
        h2 {
            color:#2980B9;
        }
        
    </style>

    <main>
        <div class="container">
            <h2 class="text-center">ELIMINAR VEHICULO</h2>
            <form action="delete.php" method="get">
                <div class="mb-3">
                    <label for="placa" class="form-label">Ingrese La Placa Del Vehiculo</label>
                    <input type="text" name="placa" class="form-control" placa="placa" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-danger">Eliminar Vehículo</button>
                    <a href="index.php" class="btn btn-primary">Cancelar</a>
                </div>
            </form>
        </div>
    </main>

    <!-- Fin sección principal -->

    <footer>
        <!-- Footer con bootstrap -->
        <div class="container-fluid bg-dark text-white text-center">
            <div class="container py-3">
                <p class="m-0">Copyrigth &copy; 2023</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
        integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
        integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous"></script>
</body>

</html>
