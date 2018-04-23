<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
   <h2 class="show-for-small-only"></h2>    
	<br>
   <div class="container">
    <div class="row">
    	<h3>Consulta M&aacute;quina de Votaci&oacute;n</h3>
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
        ?>
    	<?= form_open('/voting_machine/consultar') ?>
                <div class="large-4 medium-4 columns">
                    <label><span style="color:red;">*</span> Numero de meson</label>
                    <input type="number" maxlength="3" placeholder="" name="numero_meson" id="numero_meson" value="<?php echo $mostrar_numero_meson; ?>"/>
                </div>
                <div class="large-4 medium-4 columns">
                    <label><span style="color:red;">*</span> Codigo de centro de votacion</label>
                    <input type="number" maxlength="9" size="9" placeholder="" name="codigo_centrovotacion" id="codigo_centrovotacion" value="<?php echo $mostrar_codigo_centrovotacion; ?>"/>
                </div>
               <div class="large-4 medium-4 columns">
                    <label><span style="color:red;">*</span> Numero de mesa</label>
                    <input type="number" maxlength="2" placeholder="" name="mesa" id="mesa" value="<?php echo $mostrar_mesa; ?>"/>
                </div> 
            <div class="small-12 column text-right buttonPanel">
                <input id="btnCloseModalEditor" class="button small right alert" value="Cancelar" type="button">
                <input id="btnEnviar" class="button small right" value="Consultar" type="submit">
            </div>
        <?= form_close() ?>

    </div>
</div>
