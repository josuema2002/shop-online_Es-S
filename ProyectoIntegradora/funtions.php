<?php
//Hace la coneccion a la BD
function conexion($bd_config){
    try {
        $conexion = new PDO('mysql:host=localhost;dbname='.$bd_config["basedatos"], $bd_config['usuario'], $bd_config['pass']);
        return $conexion;
    } catch (PDOException $e) {
        return false;
    }
}
function conexion2(){
    $conexion=mysqli_connect("localhost","root","","elena's_shop");
    return $conexion;
}
//Limpia los datos para que el admin pueda crear articulos de buena forma
function limpiarDatos($datos){
    $datos = trim($datos);
    $datos = stripslashes($datos);
    $datos = htmlspecialchars($datos);
    return $datos;
}
//Cuenta el numero de pagina en el que estas
function pagina_actual(){
    return isset($_GET['p']) ? (int)$_GET['p'] : 1;
}
//selecciona el numero de paginas con post
function numero_paginas($post_por_pagina, $conexion){
    $total_post = $conexion->prepare('SELECT FOUND_ROWS() as total');
    $total_post->execute();
    $total_post = $total_post->fetch()['total'];

    $numero_paginas = ceil($total_post / $post_por_pagina);
    return $numero_paginas;

}
//Obtiene los posts, conectando a la BD y trayendo el num de pagina
function obtener_post($post_por_pagina, $conexion){
    $inicio = (pagina_actual() > 1) ? pagina_actual() * $post_por_pagina - $post_por_pagina : 0;
    $sentencia = $conexion->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM productos LIMIT $inicio, $post_por_pagina");
    $sentencia->execute();
    return $sentencia->fetchAll();
}
//funcion que da el id del articulo, limpia sus datos para traerlo desde la BD
function id_articulo($id){
    return (int)limpiarDatos($id);
}

//Obtiene los post por medio del id
function obtener_post_por_id($conexion, $id){
    $resultado = $conexion->query("SELECT * FROM productos WHERE idproductos= $id LIMIT 1");
    $resultado = $resultado->fetchAll();
    return ($resultado) ? $resultado : false;
}
//Obtiene nombre de categoria
function nombre_categoria($conexion, $id){
    $resultado = $conexion->query("SELECT categoria.NombreC, categoria.idcategoria FROM categoria INNER JOIN productos ON productos.idcategoria = categoria.idcategoria WHERE idproductos= $id LIMIT 1");
    $resultado = $resultado->fetchAll();
    return ($resultado) ? $resultado : false;
}//Obtiene nombre de marca
function nombre_marca($conexion, $id){
    $resultado = $conexion->query("SELECT marca.Nombre, marca.idmarca FROM marca INNER JOIN productos ON productos.marca = marca.idmarca WHERE idproductos= $id LIMIT 1");
    $resultado = $resultado->fetchAll();
    return ($resultado) ? $resultado : false;
}

//Comprobar la sesion de admin
function comprobarSession(){

    if (($_SESSION['rol']==2)) {
        header('Location: ' . RUTA);
    }else {
        if ($_SESSION['rol']==1) {
        }else {
            header('Location: ' . RUTA . '/login.php');
        }
    }
}

?>