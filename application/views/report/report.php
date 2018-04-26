<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>


</br>
<div class="container">
	<div class="col-md-20">
<?= form_open()?>

		<div class="row">
			<div class="col-md-20">
				<div class="page-header">
					<h3>Reporte</h3>
				</div>
			</div>
		</div>


		<div class="row">
		<div class="col-md-20">
		<table id="dataTable">
			<thead>
				<tr>
					<td>Modelo</td>
					<td>Seleccionado</td>
					<td>%</td>
					<td>Instalada</td>
					<td>%</td>
					<td>Aperturada</td>
					<td>%</td>
					<td>Votaci&oacute;n</td>
					<td>%</td>
					<td>Cerrada</td>
					<td>%</td>
					<td>Transmitida</td>
					<td>%</td>	
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
					<?php 
					if(isset($records["SELECCIONADA"])  && isset($countModelo)){
					    foreach ($countModelo->result() as $countModelos){
					        if($countModelos->modelo_maquina == $records["modelo_maquina"]){
					            $porcentaje = number_format($records["SELECCIONADA"] * 100 /$countModelos->cantidad, 2, '.', ' ');
					            $arratporcentaje = explode(".",$porcentaje);
					            if ($arratporcentaje[1]> 0)
					               echo $porcentaje." %";
					            else
					                echo $arratporcentaje[0]." %";
					        }
					    }
					    
					}else {
					    echo "0 %";
					}
					
					?>
					</td>
					
					<td>
						<? 
    					if(isset($records["INSTALADA"])){
    					    echo $records["INSTALADA"];
    					}else{
    					    echo "0";
    					}
    					?>
					</td>
					<td>
					<?php 
					if(isset($records["INSTALADA"])  && isset($countModelo)){
					    foreach ($countModelo->result() as $countModelos){
					        if($countModelos->modelo_maquina == $records["modelo_maquina"]){
					            $porcentaje = number_format($records["INSTALADA"] * 100 /$countModelos->cantidad, 2, '.', ' ');
					            $arratporcentaje = explode(".",$porcentaje);
					            if ($arratporcentaje[1]> 0)
					               echo $porcentaje." %";
					            else
					                echo $arratporcentaje[0]." %";
					        }
					    }
					    
					}else {
					    echo "0 %";
					}
					?>
					</td>
					<td>
						<? 
    					if(isset($records["APERTURADA"])){
    					    echo $records["APERTURADA"];
    					}else{
    					    echo "0";
    					}
    					?>
					</td>
					<td>
					<?php 
					if(isset($records["APERTURADA"])  && isset($countModelo)){
					    foreach ($countModelo->result() as $countModelos){
					        if($countModelos->modelo_maquina == $records["modelo_maquina"]){
					            $porcentaje = number_format($records["APERTURADA"] * 100 /$countModelos->cantidad, 2, '.', ' ');
					            $arratporcentaje = explode(".",$porcentaje);
					            if ($arratporcentaje[1]> 0)
					               echo $porcentaje." %";
					            else
					                echo $arratporcentaje[0]." %";
					        }
					    }
					    
					}else {
					    echo "0 %";
					}
					?>
					</td>
					<td>
						<? 
    					if(isset($records["VOTACION"])){
    					    echo $records["VOTACION"];
    					}else{
    					    echo "0";
    					}
    					?>
					</td>
					<td>
					<?php 
					if(isset($records["VOTACION"])  && isset($countModelo)){
					    foreach ($countModelo->result() as $countModelos){
					        if($countModelos->modelo_maquina == $records["modelo_maquina"]){
					            $porcentaje = number_format($records["VOTACION"] * 100 /$countModelos->cantidad, 2, '.', ' ');
					            $arratporcentaje = explode(".",$porcentaje);
					            if ($arratporcentaje[1]> 0)
					               echo $porcentaje." %";
					            else
					                echo $arratporcentaje[0]." %";
					        }
					    }
					    
					}else {
					    echo "0 %";
					}
					?>
					</td>
					<td>
						<? 
    					if(isset($records["CERRADA"])){
    					    echo $records["CERRADA"];
    					}else{
    					    echo "0";
    					}
    					?>
					</td>
					<td>
					<?php 
					if(isset($records["CERRADA"])  && isset($countModelo)){
					    foreach ($countModelo->result() as $countModelos){
					        if($countModelos->modelo_maquina == $records["modelo_maquina"]){
					            $porcentaje = number_format($records["CERRADA"] * 100 /$countModelos->cantidad, 2, '.', ' ');
					            $arratporcentaje = explode(".",$porcentaje);
					            if ($arratporcentaje[1]> 0)
					               echo $porcentaje." %";
					            else
					                echo $arratporcentaje[0]." %";
					        }
					    }
					    
					}else {
					    echo "0 %";
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
					<?php 
					if(isset($records["TRANSMITIDA"])  && isset($countModelo)){
					    foreach ($countModelo->result() as $countModelos){
					        if($countModelos->modelo_maquina == $records["modelo_maquina"]){
					            $porcentaje = number_format($records["TRANSMITIDA"] * 100 /$countModelos->cantidad, 2, '.', ' ');
					            $arratporcentaje = explode(".",$porcentaje);
					            if ($arratporcentaje[1]> 0)
					               echo $porcentaje." %";
					            else
					                echo $arratporcentaje[0]." %";
					        }
					    }
					    
					}else {
					    echo "0 %";
					}
					?>
					</td>
				</tr>
				<?php }
				}?>


			</tbody>
		</table>
		
		
		<table id="dataTable">
			<thead>
				<tr>
					<td>Modelo</td>
					<td>Tx CDMA</td>
					<td>%</td>
					<td>Tx Dial UP</td>
					<td>%</td>
					<td>Tx VSAT</td>
					<td>%</td>
					<td>Tx Manual</td>
					<td>%</td>
				</tr>
			</thead>
			<tbody>
				<?php if (isset($reports)){
				    foreach ($reports as $records){?>
				<tr>
					<td><?= $records["modelo_maquina"]?></td>
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
					<?php 
					if(isset($records["CDMA1x"])  && isset($mediotrans)){
					    foreach ($mediotrans->result() as $mediotran){
					        if($mediotran->modelo_maquina == $records["modelo_maquina"]){
					            $porcentaje = number_format($records["CDMA1x"] * 100 /$mediotran->cantidad, 2, '.', ' ');
					            $arratporcentaje = explode(".",$porcentaje);
					            if ($arratporcentaje[1]> 0)
					               echo $porcentaje." %";
					            else
					                echo $arratporcentaje[0]." %";
					        }
					    }
					    
					}else {
					    echo "0 %";
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
					<?php 
					if(isset($records["DIAL UP"])  && isset($mediotrans)){
					    foreach ($mediotrans->result() as $mediotran){
					        if($mediotran->modelo_maquina == $records["modelo_maquina"]){
					            $porcentaje = number_format($records["DIAL UP"] * 100 /$mediotran->cantidad, 2, '.', ' ');
					            $arratporcentaje = explode(".",$porcentaje);
					            if ($arratporcentaje[1]> 0)
					               echo $porcentaje." %";
					            else
					                echo $arratporcentaje[0]." %";
					        }
					    }
					    
					}else {
					    echo "0 %";
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
					<?php 
					if(isset($records["VSAT"])  && isset($mediotrans)){
					    foreach ($mediotrans->result() as $mediotran){
					        if($mediotran->modelo_maquina == $records["modelo_maquina"]){
					            $porcentaje = number_format($records["VSAT"] * 100 /$mediotran->cantidad, 2, '.', ' ');
					            $arratporcentaje = explode(".",$porcentaje);
					            if ($arratporcentaje[1]> 0)
					               echo $porcentaje." %";
					            else
					                echo $arratporcentaje[0]." %";
					        }
					    }
					    
					}else {
					    echo "0 %";
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
					<?php 
					if(isset($records["Manual"])  && isset($mediotrans)){
					    foreach ($mediotrans->result() as $mediotran){
					        if($mediotran->modelo_maquina == $records["modelo_maquina"]){
					            $porcentaje = number_format($records["Manual"] * 100 /$mediotran->cantidad, 2, '.', ' ');
					            $arratporcentaje = explode(".",$porcentaje);
					            if ($arratporcentaje[1]> 0)
					               echo $porcentaje." %";
					            else
					                echo $arratporcentaje[0]." %";
					        }
					    }
					    
					}else {
					    echo "0 %";
					}
					?>
					</td>
					

				</tr>
				<?php }
				}?>


			</tbody>
		</table>

		<table id="dataTable">
			<thead>
				<tr>
					<td>Modelo</td>
					<td>Reemplazo M&aacute;quina Votaci&oacute;n</td>
					<td>%</td>
					<td>Reemplazo SAI</td>
					<td>%</td>
					<td>Reemplazo Memoria</td>
					<td>%</td>
					<td>Reemplazo Boleta</td>
					<td>%</td>
				</tr>
			</thead>
			<tbody>
				<?php if (isset($reports)){
				    foreach ($reports as $records){?>
				<tr>
					<td><?= $records["modelo_maquina"]?></td>
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
					<?php 
					if(isset($records["MAQUINA VOTACION"])  && isset($reemplazo)){
					    foreach ($reemplazo->result() as $reemplazos){
					        if($reemplazos->modelo_maquina == $records["modelo_maquina"]){
					            $porcentaje = number_format($records["MAQUINA VOTACION"] * 100 /$reemplazos->cantidad, 2, '.', ' ');
					            $arratporcentaje = explode(".",$porcentaje);
					            if ($arratporcentaje[1]> 0)
					               echo $porcentaje." %";
					            else
					                echo $arratporcentaje[0]." %";
					        }
					    }
					    
					}else {
					    echo "0 %";
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
					<?php 
					if(isset($records["SAI"])  && isset($reemplazo)){
					    foreach ($reemplazo->result() as $reemplazos){
					        if($reemplazos->modelo_maquina == $records["modelo_maquina"]){
					            $porcentaje = number_format($records["SAI"] * 100 /$reemplazos->cantidad, 2, '.', ' ');
					            $arratporcentaje = explode(".",$porcentaje);
					            if ($arratporcentaje[1]> 0)
					               echo $porcentaje." %";
					            else
					                echo $arratporcentaje[0]." %";
					        }
					    }
					    
					}else {
					    echo "0 %";
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
					<?php 
					if(isset($records["MEMORIA"])  && isset($reemplazo)){
					    foreach ($reemplazo->result() as $reemplazos){
					        if($reemplazos->modelo_maquina == $records["modelo_maquina"]){
					            $porcentaje = number_format($records["MEMORIA"] * 100 /$reemplazos->cantidad, 2, '.', ' ');
					            $arratporcentaje = explode(".",$porcentaje);
					            if ($arratporcentaje[1]> 0)
					               echo $porcentaje." %";
					            else
					                echo $arratporcentaje[0]." %";
					        }
					    }
					    
					}else {
					    echo "0 %";
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
					<?php 
					if(isset($records["BOLETA"])  && isset($reemplazo)){
					    foreach ($reemplazo->result() as $reemplazos){
					        if($reemplazos->modelo_maquina == $records["modelo_maquina"]){
					            $porcentaje = number_format($records["BOLETA"] * 100 /$reemplazos->cantidad, 2, '.', ' ');
					            $arratporcentaje = explode(".",$porcentaje);
					            if ($arratporcentaje[1]> 0)
					               echo $porcentaje." %";
					            else
					                echo $arratporcentaje[0]." %";
					        }
					    }
					    
					}else {
					    echo "0 %";
					}
					?>
					</td>

				</tr>
				<?php }
				}?>


			</tbody>
		</table>
		
		
		
		
		<table id="dataTable">
			<thead>
				<tr>
					<td>Error</td>
					<td>Cantidad</td>
					<td>%</td>
				</tr>
			</thead>
			<tbody>
				<?php if(isset($countErrorTipo)){
				    foreach ($countErrorTipo->result() as $countErrors){?>
				    	<tr>
				       	<td><?= $countErrors->descripcion?></td>
				       	<td><?= $countErrors->cantidad?></td>
				       	<?php if (isset($totalErrorTipo)){
				       	    foreach ($totalErrorTipo->result() as $totalErrorTipos){?>
				       	        <td>
				       	        <?php 
				       	        
				       	        $porcentaje = number_format($countErrors->cantidad * 100 /$totalErrorTipos->cantidad, 2, '.', ' ');
    				       	        $arratporcentaje = explode(".",$porcentaje);
    				       	        if ($arratporcentaje[1]> 0)
    				       	            echo $porcentaje." %";
    				       	        else
    				       	           echo $arratporcentaje[0]." %";
				       	        
				       	        ?>
				       	        
				       	        </td>
				       	   <?php  }
				       	}?>
				        </tr>
				  <?php   }
				}?>

			</tbody>
		</table>
</div>
</div>
		
		


		<div class="small-12 column text-right buttonPanel">
			<input type="submit" id="btnEnviar" class="button small right"
				value="Aceptar" formaction="<?= base_url()?>"/>
		</div>
		<?= form_close()?>

	</div>
</div>

