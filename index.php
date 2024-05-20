<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parqueadero SENA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
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

        h1 {
            color:#2980B9;
        }


    </style>
</head>

<body>
    <header>
        <nav class="nav justify-content-center">
            <a class="nav-link active" href="index.php">INICIO</a>
            <a class="nav-link active" href="insert.php">REGISTRO</a>
            <a class="nav-link active" href="delete.php">ELIMINAR</a>
            <a class="nav-link active" href="salida.php">FACTURAR</a>
            <a class="nav-link active" href="update.php">ACTUALIZAR</a>
        </nav>
    </header>

    <main>
        <div class="container text-center">
            <h1>PARQUEADERO SENA</h1>
        </div>
        <div class="container mt-5">
            <h1 class="text-center mb-4">Seleccione su Proceso</h1>
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <form action="consultas.php" method="post">
                        <select class="form-select mb-3" name="consulta" id="consulta" required>
                            <option value="vehiculos_hoy">Muestre los vehículos que ingresaron el día de hoy</option>
                            <option value="motocicletas_hoy">Cuenta la cantidad de vehículos tipo motocicleta que ingresaron el día de hoy</option>
                            <option value="total_octubre">Sume el total del valor del parqueadero que se obtuvo durante el mes de octubre</option>
                            <option value="ultima_semana_octubre">Cuenta los vehículos que ingresaron la última semana de octubre que fueron vehículos en el horario de la mañana</option>
                            <option value="mas_ingreso_octubre">Muestre cual fue el vehículo en el mes de octubre que mas ingreso al parqueadero</option>
                            <option value="vista_bicicletas">Realice una vista de los vehículos que son Bicicleta</option>
                            <option value="vista_vehiculos_mensualidad">Realice una vista de los vehículos que pagan mensualidad</option>
                            <option value="vista_pico_placa_2y3">Hoy es pico y placa 2y3 genere una vista con las placas de los vehículos que están en el parqueadero para poder avisarles que no salgan del parqueadero</option>
                        </select>
                        <button type="submit" class="btn btn-primary">Consultar</button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <div class="container-fluid bg-dark text-white text-center">
            <div class="container py-3">
                <p class="m-0">Copyrigth &copy; 2023</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"></script>
</body>

</html>
