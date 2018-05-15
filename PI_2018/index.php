<?php
/**
 * Created by PhpStorm.
 * User: jdelgado
 * Date: 15/05/18
 * Time: 9:45
 */

session_start();
include 'php/connection.php';

$global_sql = 'SELECT * FROM oferta ORDER BY fecha_inicio DESC'; // Sql global que carga las ofertas; por defecto seleccionará todas las ofertas más recientes
$global_cont_sql = "SELECT COUNT(*) AS `count` FROM `oferta`";   // Contador de ofertas; por defecto contará todas las ofertas de la bd


/**
 *
 *      Comprobación de categoría
 *
 */
if (isset($_GET['category'])) {
    $categoria = $_GET['category'];
    $global_cont_sql = "SELECT COUNT(*) AS `count` FROM `oferta` WHERE categoria = "."'$categoria'";
    $global_sql = "select * from oferta where categoria = "."'$categoria'";
}

/**
 *
 *      Comprobación de filtros
 *
 */

// Se comprueba si se ha recibido un parámetro post mediante algún filtro
if (isset($_POST["tipo_actividad"])) {
    $actividad = $_POST["tipo_actividad"];
    $global_cont_sql = "SELECT COUNT(*) AS `count` FROM `oferta` WHERE tipo_actividad = "."'$actividad'";
    $global_sql = "select * from oferta where tipo_actividad = "."'$actividad'";
}

?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="./css/style.css">
	<meta charset="utf-8">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  	<script type="text/javascript" src="./js/conectores.js"></script>
	<script type="text/javascript" src="./js/main.js"></script>
	<title>Index</title>
</head>
<body>
<!-- ESTO ES LO MISMO QUE EL COMPONENTE, BASTA CON VOLVER A SUSTITUIRLO, CON LA DIFERENCIA DE QUE EL COMPONENTE NO ENLAZA BIEN CON EL LOGIN -->
<header class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">Nombre Web</a>
    </div>
    <ul class="nav navbar-nav navbar-right">
    	<?php
    		if (!isset($_SESSION['nombre'])) {
    			echo '<li><a href="content/registrouser.html"><span class="glyphicon glyphicon-download-alt"></span> Registrarse</a></li>';
      			echo '<li><a href="content/form_login.html"><span class="glyphicon glyphicon-log-in"></span> Entrar</a></li>';

    		}else{
    			echo '<li><a href="php/logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>';
      			echo '<li><a href="#"><span class="glyphicon glyphicon-user"></span>  '.$_SESSION['nombre'].'</a></li>';
    		}
    	?>
    </ul>
  </div>
</header>
<nav class="menuPrincipalUser">

</nav>
<aside class="filtroBusqueda">

</aside>
<section>
	<article>
		<div id="myCarousel" class="carousel slide carousel-generico" data-ride="carousel">
		  <!-- Indicators -->
		  <ol class="carousel-indicators">
		    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
		    <li data-target="#myCarousel" data-slide-to="1"></li>
		    <li data-target="#myCarousel" data-slide-to="2"></li>
		    <li data-target="#myCarousel" data-slide-to="3"></li>
		  </ol>

		  <!-- Wrapper for slides -->
		  <div class="carousel-inner" role="listbox">
		    <div class="item active">
		      <img src="./img/ciclismo-slider.jpg" alt="ciclismo">
		      <div class="carousel-caption">
		        <h3>Ciclismo</h3>
			        <p>Todo sobre ruedas</p>
		      </div>
		    </div>

		    <div class="item">
		      <img src="./img/surf-slider.jpg" alt="Surf">
		      <div class="carousel-caption">
		        <h3>Surf</h3>
		        <p>¿Te apetece coger algunas olas?</p>
		      </div>
		    </div>

		    <div class="item">
		      <img src="./img/alpinismo-slider.jpg" alt="alpinismo">
		      <div class="carousel-caption">
		        <h3>Alpinismo</h3>
		        <p>Aire fresco</p>
		      </div>
		    </div>

		    <div class="item">
		      <img src="./img/submarinismo-slider.jpg" alt="submarinismo">
		      <div class="carousel-caption">
		        <h3>Submarinismo</h3>
		        <p>Conoce el fondo del mar</p>
		      </div>
		    </div>
		  </div>

		  <!-- Left and right controls -->
		  <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
		    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
		    <span class="sr-only">Previous</span>
		  </a>
		  <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
		    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
		    <span class="sr-only">Next</span>
		  </a>
		</div>
	</article>

	<article>
        <div class="row">
        <?php
        	/* Cuenta las reservas que existen y dependiendo de las que salgan irá poniendo los elementos.
			   En el caso de que haya más reservas de 12, pues solo se mostrarán las 12 más recientes.
        	*/
        	$result = $conexion->query($global_cont_sql); // Select que se ejecutará. Si se usan filtros cambiará.
			$fila = $row = $result->fetch_assoc();
			$count = 12; // Ofertas máximas que se mostrarán por página
			$ofertas_encontradas = $fila['count'];

			if ($ofertas_encontradas == 0) {
                echo '<div id="sin_ofertas"><p>NO HAY OFERTAS DISPONIBLES</p></div>';
            }


			if ($ofertas_encontradas<12) {
				$count = $fila['count'];
			}

			/* Realiza la consulta para extraer las ofertas de la base de datos ordenadas por su fecha de inicio de forma descendente. Tras esto, se realiza un corte de la descripción por si esta es demasiado extensa que solo salga una parte, y al lado un botón o enlace de leer más que llevara a la página de esa oferta concreta.

			*/
        	$sql = $global_sql;
			$result = $conexion->query($sql);
            for ($i = 1; $i <= $count; $i++) {
            	$row = $result->fetch_assoc();
            	//$descripcion = substr($row['descripcion'], 0, 110);
                $nombre = $row['nombre'];
                $provincia = $row['provincia'];
                $actividad = $row['tipo_actividad'];
                $precio = $row['precio'];
                $dificultad = $row['dificultad'];
                echo '  <div class="col-lg-4 actividad">
    			<div class="row">
	    			<div class="col-lg-4">
	    				<img src="./img/submarinismo.jpg" alt="submarinismo" class="listImg">
	    			</div>
	    			<div class="col-lg-8">
	    			    <p id="nombre">'.$nombre.'</p>
	    			    <p id="actividad">'.$actividad.'</p>
	    				<p id="provincia">'.$provincia.'</p>
	    				<p id="dificultad">'.$dificultad.'</p>
	    				<p id="precio">'.$precio.'€</p>
	    			</div>
    			</div>
    		</div>';

            }

        ?>

  		</div>

	</article>

	<!--<article>
		<div class="row">
    		<div class="col-lg-4 actividad">
    			<div class="row">
	    			<div class="col-lg-4">
	    				<img src="./img/submarinismo.jpg" alt="submarinismo" class="listImg">
	    			</div>
	    			<div class="col-lg-8">
	    				<p>Adentrate en las profundidades del mar y mira los arrecifes de corales, los peces que viven en ellos y conoce un mundo nuevo.</p>
	    			</div>
    			</div>
    		</div>
			<div class="col-lg-4 actividad">
    			<div class="row">
	    			<div class="col-lg-4">
	    				<img src="./img/submarinismo.jpg" alt="submarinismo" class="listImg">
	    			</div>
	    			<div class="col-lg-8">
	    				<p>Adentrate en las profundidades del mar y mira los arrecifes de corales, los peces que viven en ellos y conoce un mundo nuevo.</p>
	    			</div>
    			</div>
    		</div>
    		<div class="col-lg-4 actividad">
    			<div class="row">
	    			<div class="col-lg-4">
	    				<img src="./img/submarinismo.jpg" alt="submarinismo" class="listImg">
	    			</div>
	    			<div class="col-lg-8">
	    				<p>Adentrate en las profundidades del mar y mira los arrecifes de corales, los peces que viven en ellos y conoce un mundo nuevo.</p>
	    			</div>
    			</div>
    		</div>
  		</div>

	</article>

	<article>
		<div class="row">
    		<div class="col-lg-4 actividad">
    			<div class="row">
	    			<div class="col-lg-4">
	    				<img src="./img/submarinismo.jpg" alt="submarinismo" class="listImg">
	    			</div>
	    			<div class="col-lg-8">
	    				<p>Adentrate en las profundidades del mar y mira los arrecifes de corales, los peces que viven en ellos y conoce un mundo nuevo.</p>
	    			</div>
    			</div>
    		</div>
			<div class="col-lg-4 actividad">
    			<div class="row">
	    			<div class="col-lg-4">
	    				<img src="./img/submarinismo.jpg" alt="submarinismo" class="listImg">
	    			</div>
	    			<div class="col-lg-8">
	    				<p>Adentrate en las profundidades del mar y mira los arrecifes de corales, los peces que viven en ellos y conoce un mundo nuevo.</p>
	    			</div>
    			</div>
    		</div>
    		<div class="col-lg-4 actividad">
    			<div class="row">
	    			<div class="col-lg-4">
	    				<img src="./img/submarinismo.jpg" alt="submarinismo" class="listImg">
	    			</div>
	    			<div class="col-lg-8">
	    				<p>Adentrate en las profundidades del mar y mira los arrecifes de corales, los peces que viven en ellos y conoce un mundo nuevo.</p>
	    			</div>
    			</div>
    		</div>
  		</div>

	</article>

	<article>
		<div class="row">
    		<div class="col-lg-4 actividad">
    			<div class="row">
	    			<div class="col-lg-4">
	    				<img src="./img/submarinismo.jpg" alt="submarinismo" class="listImg">
	    			</div>
	    			<div class="col-lg-8">
	    				<p>Adentrate en las profundidades del mar y mira los arrecifes de corales, los peces que viven en ellos y conoce un mundo nuevo.</p>
	    			</div>
    			</div>
    		</div>
			<div class="col-lg-4 actividad">
    			<div class="row">
	    			<div class="col-lg-4">
	    				<img src="./img/submarinismo.jpg" alt="submarinismo" class="listImg">
	    			</div>
	    			<div class="col-lg-8">
	    				<p>Adentrate en las profundidades del mar y mira los arrecifes de corales, los peces que viven en ellos y conoce un mundo nuevo.</p>
	    			</div>
    			</div>
    		</div>
    		<div class="col-lg-4 actividad">
    			<div class="row">
	    			<div class="col-lg-4">
	    				<img src="./img/submarinismo.jpg" alt="submarinismo" class="listImg">
	    			</div>
	    			<div class="col-lg-8">
	    				<p>Adentrate en las profundidades del mar y mira los arrecifes de corales, los peces que viven en ellos y conoce un mundo nuevo.</p>
	    			</div>
    			</div>
    		</div>
  		</div>

	</article>-->
</section>

<footer>

</footer>

</body>
</html>