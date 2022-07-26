<?php
// headers para poder optimizar la api para devolver archivos de tipo json y otras configuraciones del server
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET, POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


//$temp = 0;
//$hum = 0;

//$temp = $_GET['temp'];
//$hum = $_GET['hum'];

// refrescamos la pagina
$page = $_SERVER['PHP_SELF'];
$sec = "10";
header("Refresh: $sec; url=$page");

// obtenemos la fecha actual
//$fecha = date("Y-m-d");

// Conecta ala base de datos
function connectDB()
{
    // conexion al hosting 
    $con = mysqli_connect("localhost", "id19296970_uptap22", "arduinoTap02*", "id19296970_registros"); // server, user, password, database

    if ($con) {
        //echo 'La conexión de la base de datos se ha hecho satisfactoriamente ';
    } else {
        //echo 'Ha sucedido un error inesperado en la conexión de la base de datos ';
    }
    return $con;
}

if ($con) {
    // echo "Conexion con base de datos exitosa! ";

    if (isset($_POST['temp'])) {
        $temp = $_POST['temp'];
        // echo "Estación meteorológica";
        // echo " Temperaura : ".$temperatura;
    }

    if (isset($_POST['hum'])) {
        $hum = $_POST['hum'];
        //  echo " humedad : ".$humedad;
        date_default_timezone_set('america/mexico_city');
        $fecha = date("Y-m-d H:i:s");

        $consulta = "INSERT INTO registro(fecha, temp, hum) VALUES ('$fecha','$temp', '$hum')";
        // $consulta = "UPDATE DHT11 SET Temperatura='$temperatura',Humedad='$humedad' WHERE Id = 1";
        $resultado = mysqli_query($con, $consulta);
        if ($resultado) {
            //  echo " Registo en base de datos OK! ";
        } else {
            //  echo " Falla! Registro BD";
        }
    }
} else {
    // echo "Falla! conexion con Base de datos ";   
}


//sql
$sql = "SELECT * FROM `registro`;";
// Consulta datos y recepciona una clave para consultar dichos datos con dicha clave
function getArraySQL($sql)
{ //Creamos la conexión con la función anterior 
    $conexion = connectDB(); //generamos la consulta 
    mysqli_set_charset($conexion, "utf8"); //formato de datos utf8 
    if (!$result = mysqli_query($conexion, $sql)) die(); //si la conexión cancelar programa 
    $array[] = array(); //creamos un array 
    //guardamos en un array multidimensional todos los datos de la consulta 
    $i = 0;
    while ($row = mysqli_fetch_array($result)) {
        $array[$i] = array(
            'id' => $row['id'],
            'fecha' => $row['fecha'],
            'temp' => $row['temp'],
            'hum' => $row['hum']
        );
        $i++;
    }
    return $array; //devolvemos el array 
}

// mandamos a imprimir el arreglo con los datos y lo transformamos en json
$myArray = getArraySQL($sql);
echo json_encode($myArray);
