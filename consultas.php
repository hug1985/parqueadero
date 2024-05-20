<!doctype html>
<html lang="en">

<head>
    <title>Consultas Parqueadero</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
</head>

<style>
    body {
        background: -webkit-linear-gradient(to right, #FFFFFF, #6DD5FA, #2980B9);
        /* Chrome 10-25, Safari 5.1-6 */
        background: linear-gradient(to right, #FFFFFF, #6DD5FA, #2980B9);
        /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
        background-image: url('./imagen/parqueo_consultas.jpg');
        background-size: cover; /* Esto hará que la imagen cubra todo el fondo */
        background-repeat: no-repeat; /* Esto evitará que la imagen se repita */
        background-position: center; /* Esto centrará la imagen en el fondo */
    }
    
    th, td {
        padding: 8px;
        text-align: left;
        border-bottom: 1px solid #ddd;
        
    }

    h1 {
            color:#2980B9;
        }

    h2 {
            color:#2980B9;
        }
   
</style>

<body>
    <div class="container">
        <h1>Respuestas de Consultas</h1>

        <?php
            // Incluir el archivo de conexión a la base de datos
            include 'conexion.php';




            if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (!isset($_POST['consulta']) || !in_array($_POST['consulta'], ['vehiculos_hoy', 'motocicletas_hoy', 'total_octubre', 'ultima_semana_octubre', 'mas_ingreso_octubre', 'vista_bicicletas', 'vista_vehiculos_mensualidad', 'vista_pico_placa_2y3'])) {
                echo "<h2>Consulta no válida</h2>";
            }

            $consulta = $_POST['consulta'];

            switch ($consulta) {
                case 'vehiculos_hoy':
                    $sql = "SELECT p.placa, v.tipo_vehiculo AS tipo_vehiculo FROM registros p JOIN vehiculos v ON p.id_vehiculo = v.id_vehiculo WHERE DATE(p.fecha_entrada) = CURDATE()";
                    break;

                case 'motocicletas_hoy':
                    $sql = "SELECT COUNT(*) AS total FROM registros p JOIN vehiculos v ON p.id_vehiculo = v.id_vehiculo WHERE DATE(p.fecha_entrada) = CURDATE() AND v.tipo_vehiculo = 'motocicleta'";
                    break;

                case 'total_octubre':
                    $sql = "SELECT SUM(r.total_pagar) AS total_octubre 
                        FROM registros r 
                        WHERE (MONTH(r.fecha_entrada) = 10 AND DAY(r.fecha_entrada) >= 1) 
                        OR (MONTH(r.fecha_salida) = 10 AND DAY(r.fecha_salida) <= 31)";
                    break;

                case 'ultima_semana_octubre':
                    $sql = "SELECT COUNT(*) AS total FROM registros WHERE YEAR(fecha_entrada) = YEAR(CURDATE()) AND MONTH(fecha_entrada) = 10 AND DAY(fecha_entrada) >= 25 AND HOUR(fecha_entrada)  < 12";
                    break;

                case 'mas_ingreso_octubre':
                    $sql = "SELECT v.placa FROM registros r JOIN vehiculos v ON r.id_vehiculo = v.id_vehiculo WHERE MONTH(r.fecha_entrada) = 10 GROUP BY r.id_vehiculo ORDER BY COUNT(*) DESC LIMIT 1";
                    break;

        
                case 'vista_bicicletas':
                    $sql = "SELECT  placa, tipo_vehiculo FROM vehiculos WHERE tipo_vehiculo = 'bicicleta' AND placa IN (SELECT placa FROM registros WHERE total_pagar = 0)";
                    break;
    
                case 'vista_vehiculos_mensualidad':
                    $sql = "SELECT v.placa, v.tipo_vehiculo, r.tipo_pago FROM vehiculos v JOIN registros r ON v.placa = r.placa WHERE r.total_pagar = 0 AND r.tipo_pago IN ('1', '4', '7');";
                    break;
    
                case 'vista_pico_placa_2y3':
                    $sql = "SELECT placa FROM registros WHERE placa LIKE '%2' OR placa LIKE '%3' AND total_pagar = 0;)";
                    break;
            
                default:
                    echo "<h2>Consulta no válida</h2>";
                exit;
            }        
        }
    
        $stmt = $conection->prepare($sql);
            if (!$stmt->execute()) {
                echo "<h2>Error en la consulta: " . $stmt->errorInfo()[2] . "</h2>";
                exit;
            }

        switch ($consulta) {
                case 'vehiculos_hoy':
                    $result_hoy = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    echo "<h2>Vehículos que ingresaron hoy:</h2>";
                    echo "<table border='1'>";
                    echo "<tr><th>Placa</th><th>Tipo Vehículo</th></tr>";
                    foreach ($result_hoy as $vehiculo) {
                        echo "<tr><td>{$vehiculo['placa']}</td><td>{$vehiculo['tipo_vehiculo']}</td></tr>";
                    }
                    echo "</table>";
                    break;
            

                case 'motocicletas_hoy':
                    $row_motocicletas = $stmt->fetch(PDO::FETCH_ASSOC);
                    echo "<h2>Cantidad de motocicletas que ingresaron hoy:</h2>";
                    echo "<table border='1'>";
                    echo "<tr><th>Total</th></tr>";
                    echo "<tr><td>{$row_motocicletas['total']}</td></tr>";
                    echo "</table>";
                    break;
                    

                case 'total_octubre':
                    $row_total_octubre = $stmt->fetch(PDO::FETCH_ASSOC);
                    echo "<h2>Total del valor del parqueadero en octubre:</h2>";
                    echo "<table border='1'>";
                    echo "<tr><th>Total</th></tr>";
                    if ($row_total_octubre['total_octubre']) {
                        echo "<tr><td>{$row_total_octubre['total_octubre']}</td></tr>";
                    } else {
                        echo "<tr><td>No se encontraron registros de pagos en octubre.</td></tr>";
                    }
                    echo "</table>";
                    break;
                    

                case 'ultima_semana_octubre':
                    $row_ultima_semana_octubre = $stmt->fetch(PDO::FETCH_ASSOC);
                    echo "<h2>Cantidad de vehículos que ingresaron la última semana de octubre en horario de la mañana:</h2>";
                    echo "<table border='1'>";
                    echo "<tr><th>Total</th></tr>";
                    echo "<tr><td>{$row_ultima_semana_octubre['total']}</td></tr>";
                    echo "</table>";
                    break;
                    

                case 'mas_ingreso_octubre':
                    $row_mas_ingreso_octubre = $stmt->fetch(PDO::FETCH_ASSOC);
                    echo "<h2>Vehículo que más ingresó al parqueadero en octubre:</h2>";
                    echo "<table border='1'>";
                    if (!empty($row_mas_ingreso_octubre)) {
                        echo "<tr><th>Placa</th></tr>";
                        echo "<tr><td>{$row_mas_ingreso_octubre['placa']}</td></tr>";
                    } else {
                        echo "<tr><th>No se encontraron vehículos que ingresaran al parqueadero en octubre.</th></tr>";
                    }
                    echo "</table>";
                    break;
                    

                case 'vista_bicicletas':
                    $resultados_bicicletas = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        
                    // Mostrar resultados en la interfaz
                    echo "<h2>Resultados de la vista de bicicletas:</h2>";
                    echo "<table border='1'>";
                    echo "<tr><th>Placa</th><th>Tipo_vehiculo</th></tr>";
                    foreach ($resultados_bicicletas as $fila) {
                        echo "<tr><td>{$fila['placa']}</td><td>{$fila['tipo_vehiculo']}</td></tr>";
                    }
                    echo "</table>";
                        
                    break;
                

                case 'vista_vehiculos_mensualidad':
                    $sql = "SELECT v.placa, v.tipo_vehiculo, r.tipo_pago FROM vehiculos v JOIN registros r ON v.placa = r.placa WHERE r.total_pagar = 0 AND r.tipo_pago IN ('1', '4', '7')";
                    $stmt = $conection->prepare($sql);
                    if (!$stmt->execute()) {
                        echo "<h2>Error en la consulta: " . $stmt->errorInfo()[2] . "</h2>";
                        exit;
                    }
                    
                    $resultados_mensualidad = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    // Mostrar resultados en la interfaz
                    echo "<h2>Resultados de la vista de vehículos que pagan mensualidad:</h2>";
                    echo "<table border='1'>";
                    echo "<tr><th>Placa</th><th>Tipo Vehículo</th><th>Tipo Pago</th></tr>";
                    foreach ($resultados_mensualidad as $fila) {
                        echo "<tr><td>{$fila['placa']}</td><td>{$fila['tipo_vehiculo']}</td><td>{$fila['tipo_pago']}</td></tr>";
                    }
                    echo "</table>";
                    break; 
                    
                
                case 'vista_pico_placa_2y3':
                    $sql = "SELECT placa FROM registros WHERE placa LIKE '%2' OR placa LIKE '%3' AND total_pagar = 0";
                    // Ejecutar la consulta y mostrar resultados en la interfaz
                    $stmt = $conection->prepare($sql);
                    if (!$stmt->execute()) {
                        echo "<h2>Error en la consulta: " . $stmt->errorInfo()[2] . "</h2>";
                        exit;
                    }
                        
                    $resultados_pico_placa_2y3 = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        
                    // Mostrar resultados en la interfaz en una tabla
                    echo "<h2>Resultados de la vista de vehículos en pico y placa 2y3:</h2>";
                    echo "<table border='1'>";
                    echo "<tr><th>Placa</th></tr>";
                    foreach ($resultados_pico_placa_2y3 as $fila) {
                        echo "<tr><td>{$fila['placa']}</td></tr>";
                    }
                    echo "</table>";
                        
                    break;
                    
                
    
                default:
                    echo "<h2>Consulta no válida</h2>";
                exit;
    
        }        
    ?>    

    <a href="index.php" class="btn btn-primary">Regresar</a>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"></script>
</body>

</html>
