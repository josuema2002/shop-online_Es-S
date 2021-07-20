<?php session_start();
require 'admin/config.php';
require 'funtions.php';


$conexion = conexion($bd_config);
comprobarSession();

if (!$conexion) {
    header('Location: error.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'GET'  && !empty($_GET['busqueda'])) {
    $busqueda = limpiarDatos($_GET['busqueda']);

    $statement = $conexion->prepare(
        'SELECT * FROM productos INNER JOIN marca ON productos.marca=marca.idmarca WHERE productos.NombreP LIKE :busqueda or marca.Nombre like :busqueda'
    );
    $statement->execute(array(':busqueda' => "%$busqueda%"));
    $resultados = $statement->fetchAll();

    if (empty($resultados)) {
        $titulo = 'No se encontraron articulos con el reultado: ' . $busqueda;
    }else {
        $titulo = 'Resultados de la busqueda: ' . $busqueda;
    }
}else {
    header('Location: ' . RUTA . './admin/index.php');
}

require './views/buscar.admin.view.php';

?>