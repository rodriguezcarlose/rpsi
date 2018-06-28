<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
   <h2 class="show-for-small-only"></h2>    
	<br>
   <div class="container">
    <div class="row">
    	<h3>M&aacute;quina de Votaci&oacute;n</h3>
        <?php $fila=$consulta->result(); $centrovotacion= $fila[0]->codigo_centrovotacion .'-'. $fila[0]->centro_votacion ?>
    	<?= form_open('/voting_machine/seleccionada') ?>

                <div class="large-12 medium-4 columns">
                    <label>Centro de votaci&oacute;n</label>
                    <input type="text" placeholder="" name="centrovotacion" id="centrovotacion" disabled value="<?= $centrovotacion; ?>"/>
                </div>
                <div class="large-4 medium-4 columns">
                    <label>Modelo M&aacute;quina Votaci&oacute;n</label>
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

               <input type="hidden"  name="id" id="id"  value="<?= $fila[0]->id; ?>"/>

            <div class="small-12 column text-right buttonPanel">
            	<?php if ($fila[0]->id_estatus_maquina == "6"){?>
                    <input type="hidden" value="<?= $fila[0]->codigo_centrovotacion; ?>" id="codigo_centrovotacion" name = "codigo_centrovotacion">
                    <input type="hidden" value="<?= $fila[0]->mesa; ?>" id="mesa" name = "mesa">
                    <input id="btnEnviar" class="button small right alert" value="Descargar Reporte" type="submit"onclick="this.form.action = '<?=base_url()?>index.php/report/pdf_gen'; this.form.method='POST'">
                    <input id="btnEnviar" class="button small right" value="Aceptar" type="submit" onclick="this.form.action = '<?=base_url()?>index.php/voting_machine/cancelar'">
            	<?php } else { ?>
                    <input id="btnCloseModalEditor" class="button small right alert" value="Cancelar" type="submit" onclick="this.form.action = '<?=base_url()?>index.php/voting_machine/cancelar'">
                    <input id="btnEnviar" class="button small right" value="Aceptar" type="submit">
                <?php }?>
            </div>
        <?= form_close() ?>

    </div>
</div>
