<?php
if (isset($this->session->userdata['logged_in'])) {
$username = ($this->session->userdata['logged_in']['username']);
$email = ($this->session->userdata['logged_in']['email']);
} else {
header("location: login");
}
?>

<br>

<h3>Selecciona el error de la lista:</h3>

<br>

<?php
    
	if(!isset($error_message)){
	
	$tabla='
	<div class="large-12 columns">
	<table class="large-12">';
	
		$tabla.='<thead>';

		$tabla.='<tr>';
		
		$tabla.='<th>
		Buscar
		</th>';
		
		$tabla.='<th colspan="4">
		<input name="buscar_error" id="buscar_error" type="text" />
		</th>';
		$tabla.='</tr>';


		$tabla.='<tr>';
		$tabla.='<th>#</th>';
		$tabla.='<th>Descripcion</th>';
		$tabla.='<th>Tipo</th>';
		$tabla.='<th>Ocurrencia</th>';
		$tabla.='<th>
		<div align="center">
		Seleccione
		</div>
		</th>';
		$tabla.='</tr>';
		$tabla.='</thead>';
		$tabla.='<tbody>';
	
	$numero_fila=0;
	foreach($lista_errores as $error)
    {
        
		$numero_fila=($numero_fila+1);
		
		if($error->id_tipo_error=="1"){
		
		$TIPO_ERROR='<a href="#" class="medium secondary button">'.$error->descripcion_tipo_error.'</a>';
		
		}elseif($error->id_tipo_error=="2"){
		
		$TIPO_ERROR='<a href="#" class="medium alert button">'.$error->descripcion_tipo_error.'</a>';
		
		}
		
		$tabla.='<tr>';
		$tabla.='<td>'.$numero_fila.'</td>';
		$tabla.='<td>'.utf8_decode($error->descripcion_error).'</td>';
		$tabla.='<td>'.$TIPO_ERROR.'</td>';
		$tabla.='<td>'.$error->orden.'</td>';
		$tabla.='<td>
		<div align="center">
		<input name="" type="radio" value="">
		</div>
		</td>';
		$tabla.='</tr>';
	
    }
	$tabla.='</tbody>';
	$tabla.='</table></div>';
	
	echo $tabla;
	
	}else{
	
	echo '<p class="help-text" id="passwordHelpText">';
	echo $error_message;
	echo "</p>";
	
	
	}
	

?>
