<?php
include 'conexion.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $conection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $placa = $_POST["placa"];
        $fecha_entrada_anterior = isset($_POST['fecha_entrada_anterior']) ? $_POST['fecha_entrada_anterior'] : null;
        $fecha_entrada_nueva = isset($_POST['fecha_entrada_nueva']) ? $_POST['fecha_entrada_nueva'] : null;

        // Obtener el ID del vehículo
        $consulta_vehiculo = $conection->prepare("SELECT id_vehiculo FROM vehiculos WHERE placa = :placa");
        $consulta_vehiculo->bindParam(':placa', $placa);
        $consulta_vehiculo->execute();
        $vehiculo = $consulta_vehiculo->fetch(PDO::FETCH_ASSOC);
        $id_vehiculo = $vehiculo ? $vehiculo['id_vehiculo'] : null;

        // Actualizar registro en la tabla registros si se obtuvieron los datos necesarios
        if ($id_vehiculo) {
            $actualizacion_registro = $conection->prepare("UPDATE registros SET fecha_entrada = :fecha_entrada_nueva WHERE id_vehiculo = :id_vehiculo AND fecha_entrada = :fecha_entrada_anterior");
            $actualizacion_registro->bindParam(':fecha_entrada_anterior', $fecha_entrada_anterior);
            $actualizacion_registro->bindParam(':id_vehiculo', $id_vehiculo);
            $actualizacion_registro->bindParam(':fecha_entrada_nueva', $fecha_entrada_nueva);
            $actualizacion_registro->execute();

            echo "<script>alert('Actualización exitosa.');</script>";
        } else {
            echo "<script>alert('No se pudo obtener la información necesaria para la actualización.');</script>";
        }
    } catch (PDOException $e) {
        echo "¡Error!: " . $e->getMessage() . "<br/>";
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <title>ACTUALIZAR REGISTROS</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">

    <style>
        body {
            background: -webkit-linear-gradient(to right, #FFFFFF, #6DD5FA, #2980B9);
            /* Chrome 10-25, Safari 5.1-6 */
            background: linear-gradient(to right, #FFFFFF, #6DD5FA, #2980B9);
            /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
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

</head>

<body>
    <header>
        <!-- añadimos un header de boostrap -->
        <nav class="nav justify-content-center  ">
            <a class="nav-link active" href="index.php" aria-current="page">INICIO</a>
            <a class="nav-link active" href="insert.php">REGISTRO</a>
            <a class="nav-link active" href="salida.php">FACTURAR</a>
            <a class="nav-link active" href="delete.php">ELIMINAR</a>
            <a class="nav-link active" href="update.php">ACTUALIZAR</a>
        </nav>
    </header>

    <!-- Seccion principal -->
    <main>
        <div class="container text-center">
            <h1>ACTUALIZAR REGISTRO</h1>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <form action="update.php" method="post">
                        <div class="mb-3">
                            <label for="placa" class="form-label">Placa</label>
                            <input type="text" name="placa" class="form-control" id="placa" required>
                        </div>
                        <div class="mb-3">
                            <label for="fecha_entrada_anterior" class="form-label">Fecha de Entrada Anterior</label>
                            <input type="datetime-local" name="fecha_entrada_anterior" class="form-control" id="fecha_entrada_anterior" required>
                        </div>
                        <div class="mb-3">
                            <label for="fecha_entrada_nueva" class="form-label">Fecha de Entrada Nueva</label>
                            <input type="datetime-local" name="fecha_entrada_nueva" class="form-control" id="fecha_entrada_nueva" required>
                        </div>
                        <div class="mb-3">
                            <label for="fecha_actualizacion" class="form-label">Fecha Actualizacion</label>
                            <input type="datetime-local" name="fecha_actualizacion" class="form-control" id="fecha_actualizacion" required>
                        </div>
                        
                    
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                        <a href="index.php" class="btn btn-primary">Regresar</a>
                    </form>

                </div>
            </div>
        </div>
    </main>

    <footer>
        <!-- añadimos un footer de boostrap -->
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