<?php
session_start();
include '../php/connection.php';
if (!isset($_SESSION['nombre'])) {
    // Si es usuario anónimo (no logeado) no podrá acceder a esta página, así que será redirigido a un 404.
    header('Location: ./404.html');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="icon" type="image/png" href="../img/favicon.ico" />
	<link rel="stylesheet" type="text/css" href="../css/style.css">
	<meta charset="utf-8">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  	<script type="text/javascript" src="../js/conectores_content.js"></script>
    <script type="text/javascript" src="../js/main.js"></script>

    <title>Mis reservas | WildSports</title>
</head>
<body>
<header class="menuLogin">

</header>
<nav class="menuPrincipalUser">

</nav>
<aside class="publicidad">

</aside>

<section>
<article>
    <div class="status"></div>
        <div class="misreservas">
         <h2 class="text-center">Mis reservas</h2><hr style='width: 90%'/>
				<?php

				$sql_reserva = "SELECT * FROM reserva WHERE nif_usuario = (SELECT nif FROM usuario WHERE alias = '". $_SESSION['nombre']  ."') ";
				$result = $conexion->query($sql_reserva);
          
          if ($result->num_rows === 0) {
              echo '<span id="alerta-sin-reservas"><p class="text-center alert alert-warning"><span class="glyphicon glyphicon-warning-sign"></span> Todavía no has reservado actividades. ¡<a href="../index.php">Échale un vistazo</a> a algunas!</p></span>';
          }else{ 
           while($row = $result->fetch_assoc()) {
                $sql_oferta = "SELECT * from oferta where id = '".$row['id_oferta']."'";
                $result2 = $conexion->query($sql_oferta);
                $row2 = $result2->fetch_assoc();
                echo '  <div class=" col-md-4 col-xs-12 col-lg-3 col-sm-6">
                            <div class="tarjetaConPun">
            					<img src="../img/oferta/'.$row2['imagen_oferta'].'" alt="sample66"/>
                      
                      				
					                <p id="nombre_actividad">Actividad: '.$row2['nombre'].'</p>
					                <p id="tipo_actividad">Tipo: '.$row2['tipo_actividad'].'</p>
             						<p id="coste_reserva">Coste: '.$row['coste_reserva'].'€</p>';
					                if ($row['num_plazas_reserva'] > 1) {
					                    echo '<p id="plazas_reservadas">Plazas: '.$row['num_plazas_reserva'].' plazas reservadas</p>';
					                } else {
					                    echo '<p id="plazas_reservadas">Plazas: '.$row['num_plazas_reserva'].' plaza reservada</p>';
					                }

					               echo ' 
					               <p id="fecha_reserva">Fecha reservada: '.$row['fecha_reserva'].'</p>';
					               $fecha_reserva = $row['fecha_reserva'];
					               /* EN CASO DE QUE LA RESERVA HAYA CONCLUIDO ... */

					               // Cálculo de la fecha de expiración que es igual a fecha_reserva + 1 dia
					               $fecha_expiracion_actividad = date("Y-m-d", strtotime($fecha_reserva . '+1 day'));
					               $array_fecha = getdate();
					               $year = $array_fecha['year'];
					               $month = $array_fecha['mon'];
					               $day = $array_fecha['mday'];
					               $fecha_de_hoy = $year."-".$month."-".$day;
					               $fecha_de_hoy = date("Y-m-d", strtotime($fecha_de_hoy)); // Convertimos al formato deseado.

					               /* #DEBUG Para hacer salir el formulario de reseña, introduce una fecha mayor a la de hoy y mayor que la fecha de reserva a la actividad
					               $fecha_de_hoy = '2018-06-26';
					               //$fecha_expiracion_actividad = '2018-06-25';
					               echo '<br>Fecha exp: ' .$fecha_expiracion_actividad;
					               echo '<br>Fecha hoy: ' . $fecha_de_hoy;
					               */

					               /* Aquí debe ir una comprobación de si la reserva tiene las columnas reseña y valoración en NULL, que SE MUESTRE la encuesta. Si tiene otro valor NO se mostrará */
					               $nif_usuario = $row['nif_usuario'];
					               $id_oferta = $row['id_oferta'];
					               $sql_comprobacion_null = "SELECT * FROM reserva where nif_usuario = '$nif_usuario' and id_oferta = '$id_oferta' and valoracion is NULL and resena is NULL";
					               $resultado=$conexion->query($sql_comprobacion_null);
					                echo '
					                <button>Ver actividad</button>
					                <a href="oferta.php?id='.$row['id_oferta'].'"></a>
					                    ';
					               if ($resultado->num_rows > 0) { // Si DEVUELVE una actividad con valores nulos, es que el user PUEDE rellenar la encuesta, así que se mostrará
					                   if ($fecha_de_hoy > $fecha_reserva && $fecha_reserva < $fecha_expiracion_actividad) { // El form se mostrará 1 día después de la fecha que el usuario ha reservado(fecha_expiracion). Así que aquí se calculafecha_expiracion)
					                                                                      // si el día de hoy es mayor o IGUAL que la fecha de expiración, es entonces cuando se mostrará.  
					                                                                      ?>
					                       <div id="puntuacion">
					                           <form id="form-rating" method="post">
					                               <label>Califica la actividad</label>
					                               <fieldset class="rating">
					                                   <input required name="rating"
					                                          type="radio"
					                                          id="rating5"
					                                          value="5"
					                                          on="change:rating.submit" />
					                                   <label for="rating5"
					                                          title="5 stars">☆</label>

					                                   <input required name="rating"
					                                          type="radio"
					                                          id="rating4"
					                                          value="4"
					                                          on="change:rating.submit" />
					                                   <label for="rating4"
					                                          title="4 stars">☆</label>

					                                   <input required name="rating"
					                                          type="radio"
					                                          id="rating3"
					                                          value="3"
					                                          on="change:rating.submit" />
					                                   <label for="rating3"
					                                          title="3 stars">☆</label>

					                                   <input required name="rating"
					                                          type="radio"
					                                          id="rating2"
					                                          value="2"
					                                          on="change:rating.submit"
					                                          checked="checked" />
					                                   <label for="rating2"
					                                          title="2 stars">☆</label>

					                                   <input required name="rating"
					                                          type="radio"
					                                          id="rating1"
					                                          value="1"
					                                          on="change:rating.submit" />
					                                   <label for="rating1"
					                                          title="1 stars">☆</label>
					                               </fieldset>
					                               <label>¿Qué te ha parecido la actividad?</label>
					                               <textarea class="form-control" id="rating-text" name="resena" maxlength="200"></textarea><br>
					                               <input type="hidden" id="input_nif" name="nif_usuario" value=<?php echo $row['nif_usuario'];?>>
					                               <input type="hidden" id="input_id_oferta" name="id_oferta" value=<?php echo $row['id_oferta'];?>>
					                               <button class="puntuar" id="rating">Calificar</button>
					                           </form>
					                       </div>
					                       
					                       <?php
					                   }
					               } else {
					                    echo "<span id='voted'><p>Ya has evaluado esta actividad</p><img width='30' src=\"../img/tick.png\"></span><br>

					                    ";
					               }
					                echo '
					            
					          </div>
					        </div>';
					            }
					          }
					        ?>
			
			</div>
		</div>
</article>
</section>
<footer class="pie">
</footer>
</body>
</html>
