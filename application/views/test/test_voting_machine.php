<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
   <h2 class="show-for-small-only"></h2>    
	<br>
   <div class="container">
    <div class="row">
    	<h3>M&aacute;quina de Votaci&oacute;n</h3>
         <?php 
                $fila=$consulta->result(); 
                $centrovotacion= $fila[0]->codigo_centrovotacion .'-'. $fila[0]->centro_votacion 
                
                ?>
    	<?= form_open('/voting_machine/procesar') ?>
				<input type="hidden" value="<?= $fila[0]->id; ?>" id="id" name = "id">
				<input type="hidden" value="<?= $fila[0]->estatus; ?>" id="estatusmv" name = "estatusmv">
				<input type="hidden" value="<?= $fila[0]->id_estatus_maquina; ?>" id="idestatusmaquina" name = "idestatusmaquina">
                <div class="large-12 medium-4 columns">
                    <label>Centro de votaci&oacute;n</label>
                    <input type="text" name="centrovotacion" id="centrovotacion" disabled value="<?= $centrovotacion ?>"/>
                </div>
                <div class="large-4 medium-4 columns">
                    <label>Modelo M&aacute;quina Votaci&oacute;n</label>
                    <input type="text" name="modelomaquina" id="modelomaquina" disabled value="<?= $fila[0]->modelo_maquina; ?>"/>
                </div>
               <div class="large-4 medium-4 columns">
                    <label>N&uacute;mero de mesa</label>
                    <input type="text" name="mesa" id="mesa" disabled value="<?= $fila[0]->mesa; ?>"/>
                </div> 
               <div class="large-4 medium-4 columns">
                    <label>Estatus</label>
                    <input type="text" name="estatus" id="estatus" disabled value="<?= $fila[0]->estatus; ?>"/>
                </div> 
               <div class="large-6 medium-4 columns">
                    <label>C&oacute;digo Validaci&oacute;n</label>
                    <?php if( $fila[0]->id_estatus_maquina == 3){?>
                    <input type="text" placeholder="" name="codigo" id="codigo" disabled value="" />
                    <?php }else{?>
                    <input type="text" placeholder="" name="codigo" id="codigo" value=""/>
                    <?php }?>
                </div> 
               <div class="large-6 medium-4 columns">
                    <label>Medio de Transmisi&oacute;n</label>
                    <?php if( $fila[0]->id_estatus_maquina !== "5"){?>
                   		<select name="medio" id="medio" disabled>
                   	<?php }else{?>
                  	
					<select name="medio" id="medio">
						<option value="">Seleccione</option>
                        <option value="DIAL UP">DIAL UP</option>
                        <option value="CDMA1x">CDMA1x</option>
                        <option value="VSAT">VSAT</option>
                        <option value="Manual">Manual</option>
                   	<?php }?>
                    </select>
                </div> 

        <br>
        <h3>Registrar Errores</h3>
			<div class="large-12 medium-4 columns">
                <label>Buscar Error</label>
               	<select data-autocomplete=""  multiple="" name="error[]" id = "error">
               		<option value="">Buscar errores</option>
               		<?php 
               		   foreach ($errormv->result() as $error){
               		?>
               		<option value="<?= $error->id?>"><?= $error->descripcion?></option>
               		
               		<?php
               		    
               		}
               		
               		?>
               	</select>

            </div>
        	<br>    
        	<h3>Tipo Reemplazo</h3>
			<div class="large-6 medium-4 columns">
                <label>Tipo Reemplazo</label>
               	<select name="tiporeemplazo" id ="tiporeemplazo">
               		<option value="">Seleccione</option>
               		<?php 
               		foreach ($tiporeemplazo->result() as $tipor){
               		?>
               		<option value="<?= $tipor->id?>"><?= $tipor->descripcion?></option>
               		<?php
               		}
               		?>
               	</select>
            </div>
        	<br> 

    		
            <div class="small-12 column text-right buttonPanel">
                <input id="btnCloseModalEditor" class="button small right alert" value="Cancelar" type="submit" onclick="this.form.action = '/voting_machine/cancelar'">
                <input id="btnEnviar" class="button small right" value="Aceptar" type="submit">
            </div>
        <?= form_close() ?>            

    </div>
</div>
