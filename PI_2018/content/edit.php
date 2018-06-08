<?php
session_start();
include '../php/connection.php';

$id = $_GET['id'];
$sql_oferta = "SELECT * FROM oferta WHERE id=".$id;

?>
<!DOCTYPE html>
<html>
<head>
	<title>Editar una oferta</title>
	<meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="../js/conectores_content.js"></script>
</head>
<body>
<header class="menuLogin">

</header>
<nav class="menuPrincipalUser">

</nav>
<aside class="publicidad">

</aside>
<section id="sectionforms"> 
    <article id="userforms">
    <div id="lista">
        <?php
        $result = $conexion->query($sql_oferta);
        $row = $result->fetch_assoc();
        echo '<div id="detalle-oferta">';
        // echo "<p>AVISO: ESTO ESTÁ HECHO EN PLAN CUTRÓN, LA IDEA ES QUE AQUÍ SALGAN LOS DATOS, PERO SOLO ALGUNOS DE ELLOS SE PUEDAN MODIFICAR, A CONTINUACIÓN VEREIS CUALES. SE HARÁ PRIMERO LA PARTE BACK Y LUEGO SE LE IMPLEMENTARÁ LA VISTA.</p>";
        echo '<h2>Información de la actividad a editar</h2>';      
echo "<div class='filainfo'>";
        echo "<div class='infoperfiliz'><label>Nombre de la oferta: </label></div>";
        echo "<div class='infoperfilde'><input type=\"text\" disabled value={$row['nombre']} </div> ";
echo "</div>";
        echo "<div class='filainfo'>";
        echo "<div class='infoperfiliz'><label>Provincia: </label></div>";
        echo "<div class='infoperfilde'><input type=\"text\" disabled value={$row['provincia']} </div> ";
        echo "</div>";
        echo "<div class='filainfo'>";
        echo "<div class='infoperfiliz'><label>Municipio: </label></div>";
        echo "<div class='infoperfilde'><input type=\"text\" disabled value={$row['municipio']} </div> ";
        echo "</div>";
        echo "<div class='filainfo'>";
        echo "<div class='infoperfiliz'><label>Duración: </label></div>";
        echo "<div class='infoperfilde'><input type=\"text\" disabled value={$row['duracion']} </div> ";
        echo "</div>";
        echo "<div class='filainfo'>";
        echo "<div class='infoperfiliz'><label>Número de plazas disponibles: </label></div>";
        echo "<div class='infoperfilde'><input type=\"text\" disabled value={$row['num_plazas']} </div> ";
        echo "</div>";
        echo "<div class='filainfo'>";
        echo "<div class='infoperfiliz'><label>Tipo de actividad: </label></div>";
        echo "<div class='infoperfilde'><input type=\"text\" disabled value={$row['tipo_actividad']} </div> ";
        echo "</div>";
        echo "<div class='filainfo'>";
        echo "<div class='infoperfiliz'><label>Descripción: </label></div>";
        echo "<div class='infoperfilde'><input type=\"text\" disabled value={$row['descripcion']} </div> ";
        echo "</div>";
        echo "<div class='filainfo'>";
        echo "<div class='infoperfiliz'><label>Precio: </label></div>";
        echo "<div class='infoperfilde'><input type=\"text\" disabled value={$row['precio']} </div> ";
        echo "</div>";
        echo "<div class='filainfo'>";
        echo "<div class='infoperfiliz'><label>Dificultad: </label></div>";
        echo "<div class='infoperfilde'><input type=\"text\" disabled value={$row['dificultad']} </div> ";
        echo "</div>";
        echo "<div class='filainfo'>";
        echo "<div class='infoperfiliz'><label>Categoría: </label></div>";
        echo "<div class='infoperfilde'><input type=\"text\" disabled value={$row['categoria']} </div> ";
        echo "</div>";
        echo "<div class='filainfo'>";
        echo "<div class='infoperfiliz'><label>Fecha de inicio: </label></div>";
        echo "<div class='infoperfilde'><input type=\"text\" disabled value={$row['fecha_inicio']} </div> ";
        echo "</div>";
        echo "<div class='filainfo'> ";
        echo "<div class='infoperfiliz'><label>Fecha de fin: </label></div>";
        echo "<div class='infoperfilde'><input type=\"text\" disabled value={$row['fecha_fin']} </div> ";
        echo "</div>";
        echo '</div>';
        ?>
        <br><br>.
</div>
        <a href="../index.php">Volver al index</a>
    </article>
    <article id="userforms">
<div class="camposmodificar">
<?php
    if (!isset($_POST['nombre'])) {
        echo '    

        <h2>Campos a modificar de la actividad</h2>
        
        <form action="" method="post" accept-charset="utf-8">
        <label>Nombre</label><br>
        <input type="text" name="nombre" value="'.$row['nombre'].'" placeholder="Nombre"><br>

        <label>Duración</label><br>
        <input type="text" name="duracion" value="'.$row['duracion'].'" placeholder="min"><br>

        <label>Número de plazas </label><br>
        <input type="text" name="plazas" value="'.$row['num_plazas'].'" placeholder="Nº de plazas individuales"><br>

        <label>Descripción</label><br/>
        <textarea name="desc"></textarea><br/>

        <label>Dificultad</label><br>
        <select name="dificultad" id="categ-usu">
            <option value="facil">Fácil</option>
            <option value="media">Media</option>
            <option value="alta">Alta</option>
            <option value="experto">Experto</option>
        </select><br>

        <label>Imagen</label>
        <input name="file" type="file" size="2mb"><br/>
        
        <input id="enviar" type="submit" value="Enviar" class="botones">

    </form>';
    }else{
      $nombre = $_POST['nombre'];
      $duracion = $_POST['duracion'];
      $plazas = $_POST['plazas'];
      $descripcion = $_POST['desc'];
      $dificultad = $_POST['dificultad'];
      //ni idea de como pillar lo de la imagen, cuando se sepa se implementa.
      $sql_edit = "UPDATE oferta SET nombre='".$nombre."', duracion='".$duracion."',num_plazas=".$plazas.",descripcion='".$descripcion."', dificultad='".$dificultad."' WHERE id=".$id;
        if ($conexion->query($sql_edit) === TRUE) {
            echo "Registro añadido correctamente.";
        } else {
            echo "Error: " . $sql_edit . "<br>" . $conexion->error;
        }
        echo "<br>";
        echo "<br>";        
        echo '<a href="activity_manager.php"> Volver </a>';
    }

?>
<div>
    </article>
</section>

<footer class="pie">

</footer>
</body>
</html>