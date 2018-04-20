<?php
if (isset($this->session->userdata['logged_in'])) {
$username = ($this->session->userdata['logged_in']['username']);
$email = ($this->session->userdata['logged_in']['email']);
} else {
header("location: login");
}
?>

<br>

<h3>Seleccion de Mesa de Votacion:</h3>

<br>

<?php echo form_open('registro_prueba/consulta_registro_prueba'); ?>

<?php

?>



<?php

if((isset($numero_meson)) && ($numero_meson!="")){
$mostrar_numero_meson=$numero_meson;
}else{
$mostrar_numero_meson="";
}

////////////////////////////////////////////////////

if((isset($codigo_centrovotacion)) && ($codigo_centrovotacion!="")){
$mostrar_codigo_centrovotacion=$codigo_centrovotacion;
}else{
$mostrar_codigo_centrovotacion="";
}

////////////////////////////////////////////////////

if((isset($mesa)) && ($mesa!="")){
$mostrar_mesa=$mesa;
}else{
$mostrar_mesa="";
}

////////////////////////////////////////////////////

?>            
            <div class="row">
			
			<?php 
echo validation_errors();
?>
			
                <div class="large-4 medium-4 columns">
                    <label><span style="color:red;">*</span> Numero de meson</label>
                    <input type="text" placeholder="" name="numero_meson" id="numero_meson" value="<?php echo $mostrar_numero_meson; ?>"/>
                </div>
                <div class="large-4 medium-4 columns">
                    <label><span style="color:red;">*</span> Codigo de centro de votacion</label>
                    <input type="text" placeholder="" name="codigo_centrovotacion" id="codigo_centrovotacion" value="<?php echo $mostrar_codigo_centrovotacion; ?>"/>
                </div>
               <div class="large-4 medium-4 columns">
                    <label><span style="color:red;">*</span> Numero de mesa</label>
                    <input type="text" placeholder="" name="mesa" id="mesa" value="<?php echo $mostrar_mesa; ?>"/>
                </div> 
            </div>
			
 			<div class="row">
			
                <div class="large-12 medium-12 columns">
				
				
				<?php
				if((isset($id_maquina))&&($id_maquina!="")){
				?>
				
				<!--<input type="submit" value=" Aceptar " name="submit" class="medium success button"/>-->
				<input type="submit" value=" Buscar " name="submit" class="button small right"/>

				
				<?php
				}else{
				?>

				<input type="submit" value=" Buscar " name="submit" class="button small right"/>
				
				<?php
				}
				?>
				
                </div>	
						
			</div>
	<?php
	if((isset($id_maquina))&&($id_maquina!="")){
	
	if($fase_estatus == "existe"){
	
	//echo "<pre>";
	//print_r($lista_fase);
	
	
	$lista_de_fases='';
	foreach($lista_fase as $fase){

/*
	echo $fase->id."<br>";
	echo $fase->id_maquina_votacion."<br>";
	echo $fase->id_usuario."<br>";
	echo $fase->id_fase."<br>";
	echo $fase->fechainicio."<br>";
	echo $fase->fechafin."<br>";
	echo $fase->descripcion_fase."<br>";
		
*/		
	$lista_de_fases.='
	<button class="button small">
	<i class="fi-plus"></i>
	<a style="color:white;" href="'.base_url().'index.php/registro_prueba/consulta_lista_errores/'.$fase->id.'">'.$fase->descripcion_fase.'</a>
	</button>';
	
    }
	$lista_de_fases.='';
	
	}elseif($fase_estatus == "no existe"){
	
	$data_cargar_error = array(
	'id_maquina' => $id_maquina,
	'id_fase' => 0
	);
	
$lista_de_fases='
	<button class="button small">
	<i class="fi-plus"></i>
	<a style="color:white;" href="'.base_url().'index.php/registro_prueba/consulta_lista_errores/0">INSTALACION</a>
	</button>';	
	
	}
	
	?>	
    <div class="large-12 columns">
        <div class="dataTableActions text-right">
            
			
			<?php echo $lista_de_fases;?>
        </div>
        <table class="large-12">
            <thead>
			<tr>
			<!--<td>id</td>-->
			<!--<td>codigo estado</td>-->
			<td>estado</td>
			<!--<td>codigo municipio</td>-->
			<td>municipio</td>
			<!--<td>codigo parroquia</td>-->
			<td>parroquia</td>
			<td>codigo centrovotacion</td>
			<td>centro votacion</td>
			<td>mesa</td>
			<td>codigo instalacion</td>
			<td>codigo apertura</td>
			<td>codigo cierre</td>
			<td>codigo transmision</td>
			<td>modelo maquina</td>
			<td>estatus maquina</td>
			<td>numero meson</td>
			</tr>
            </thead>
			<tbody>
			<tr>
			<!--<td><?php echo $id_maquina; ?></td>-->
			<!--<td><?php echo $codigo_estado; ?></td>-->
			<td><?php echo $estado; ?></td>
			<!--<td><?php echo $codigo_municipio; ?></td>-->
			<td><?php echo $municipio; ?></td>
			<!--<td><?php echo $codigo_parroquia; ?></td>-->
			<td><?php echo $parroquia; ?></td>
			<td><?php echo $codigo_centrovotacion; ?></td>
			<td><?php echo $centro_votacion; ?></td>
			<td><?php echo $mesa; ?></td>
			<td><?php echo $codigo_instalacion; ?></td>
			<td><?php echo $codigo_apertura; ?></td>
			<td><?php echo $codigo_cierre; ?></td>
			<td><?php echo $codigo_transmision; ?></td>
			<td><?php echo $modelo_maquina; ?></td>
			<td><?php echo $desc_estatus_maquina; ?></td>
			<td><?php echo $numero_meson; ?></td>
			</tr>
			</tbody>
        </table>
    </div>

		<?php
		}else{

		if (isset($error_message)) {
		
		echo '<p class="help-text" id="passwordHelpText">';
		echo $error_message;
		echo "</p>";
		
		}

		
		}
		?>
		
	
			
            


<?php echo form_close(); ?>
