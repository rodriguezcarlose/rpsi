<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>


</br>
<div class="container">
	<div class="col-md-12">


		<div class="row">
			<div class="col-md-20">
				<div class="page-header">
					<h3>Reporte</h3>
				</div>
			</div>
		</div>

</br>

		<table id="dataTable">
			<thead>
				<tr>
					<td>Modelo</td>
					<td>Seleccionado</td>
					<td>Iniciado</td>
					<td>Culminado</td>
					<td>Tx CDMA</td>
					<td>Tx Dial UP</td>
					<td>Tx VSAT</td>
					<td>Tx Manual</td>
					<td>Reemplazo M&aacute;quina Votaci&oacute;n</td>
					<td>Reemplazo SAI</td>
					<td>Reemplazo Memoria</td>
					<td>Reemplazo Boleta</td>
					<td>Reemplazo Inversor</td>
					<td>Reemplazo Papel</td>
				</tr>
			</thead>
			<tbody>
				<?php if (isset($reports)){
				    foreach ($reports as $records){?>
				<tr>
					<td><?= $records["modelo_maquina"]?></td>
					<td>
    					<? 
    					if(isset($records["SELECCIONADA"])){
    					    echo $records["SELECCIONADA"];
    					}else{
    					    echo "0";
    					}
    					?>
					</td>
					<td>
						<? 
    					if(isset($records["INICIADA"])){
    					    echo $records["INICIADA"];
    					}else{
    					    echo "0";
    					}
    					?>
					</td>
					<td>
						<? 
    					if(isset($records["TRANSMITIDA"])){
    					    echo $records["TRANSMITIDA"];
    					}else{
    					    echo "0";
    					}
    					?>
					</td>
					<td>
						<? 
    					if(isset($records["CDMA1x"])){
    					    echo $records["CDMA1x"];
    					}else{
    					    echo "0";
    					}
    					?>
					</td>
					<td>
						<? 
    					if(isset($records["DIAL UP"])){
    					    echo $records["DIAL UP"];
    					}else{
    					    echo "0";
    					}
    					?>
					</td>
					<td>
						<? 
    					if(isset($records["VSAT"])){
    					    echo $records["VSAT"];
    					}else{
    					    echo "0";
    					}
    					?>
					</td>
					<td>
						<? 
    					if(isset($records["Manual"])){
    					    echo $records["Manual"];
    					}else{
    					    echo "0";
    					}
    					?>
					</td>
					<td>
						<? 
    					if(isset($records["MAQUINA VOTACION"])){
    					    echo $records["MAQUINA VOTACION"];
    					}else{
    					    echo "0";
    					}
    					?>
					</td>
					<td>
						<? 
    					if(isset($records["SAI"])){
    					    echo $records["SAI"];
    					}else{
    					    echo "0";
    					}
    					?>
					</td>
					<td>
						<? 
    					if(isset($records["MEMORIA"])){
    					    echo $records["MEMORIA"];
    					}else{
    					    echo "0";
    					}
    					?>
					</td>
					<td>
						<? 
    					if(isset($records["BOLETA"])){
    					    echo $records["BOLETA"];
    					}else{
    					    echo "0";
    					}
    					?>
					</td>
					<td>
						<? 
    					if(isset($records["INVERSOR"])){
    					    echo $records["INVERSOR"];
    					}else{
    					    echo "0";
    					}
    					?>
					</td>
					<td>
						<? 
    					if(isset($records["PAPEL"])){
    					    echo $records["PAPEL"];
    					}else{
    					    echo "0";
    					}
    					?>
					</td>
				</tr>
				<?php }
				}?>


			</tbody>
		</table>


		<div class="small-12 column text-right buttonPanel">
			<input type="submit" id="btnEnviar" class="button small right"
				value="Guardar y Liberar" />
		</div>

	</div>
</div>

