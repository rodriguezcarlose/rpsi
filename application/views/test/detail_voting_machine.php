<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
   <h2 class="show-for-small-only"></h2>    
	<br>
   <div class="container">
    <div class="row">
    	<h3>M&aacute;quina de Votaci&oacute;n</h3>
        <?php $fila=$consulta->result(); $centrovotacion= $fila[0]->codigo_centrovotacion .'-'. $fila[0]->centro_votacion ?>
    	<?= form_open('/voting_machine/'. strtolower($fila[0]->estatus)) ?>

                <div class="large-12 medium-4 columns">
                    <label>Centro de votaci&oacute;n</label>
                    <input type="text" placeholder="" name="centrovotacion" id="centrovotacion" disabled value="<?= $centrovotacion; ?>"/>
                </div>
                <div class="large-4 medium-4 columns">
                    <label>Mod cdxelo M&aacute;quina Votaci&oacute;n</label>
                    <input type="text" placeholder="" name="modelomaquina" id="modelomaquina" disabled value="<?= $fila[0]->modelo_maquina; ?>"/>
                </div>
               <div class="large-4 medium-4 columns">
                    <label>N&uacute;mero de mesa</label>
                    <input type="text" placeholder="" name="mesa" id="mesa" disabled value="<?= $fila[0]->mesa; ?>"/>
                </div> 
               <div class="large-4 medium-4 columns">
                    <label>Estatus</label>
                    <input type="text" placeholder="" name="estatus" id="estatus" disabled value="<?= $fila[0]->estatus; ?>"/>
                </div> 
              
                    <input type="text" placeholder="" name="id" id="id"  style="display:none" value="<?= $fila[0]->id; ?>"/>


            <div class="small-12 column text-right buttonPanel">
                <input id="btnCloseModalEditor" class="button small right alert" value="Cancelar" type="button">
                <input id="btnEnviar" class="button small right" value="Aceptar" type="submit">
            </div>
        <?= form_close() ?>

    </div>
</div>
