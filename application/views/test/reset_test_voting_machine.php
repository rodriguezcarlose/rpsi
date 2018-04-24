<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
   <h2 class="show-for-small-only"></h2>    
	<br>
   <div class="container">
    <div class="row">
    	<h3>Reiniciar M&aacute;quina de Votaci&oacute;n</h3>

    	<?= form_open('/Voting_machine/resettest') ?>
                <div class="large-6 medium-4 columns">
                    <label><span style="color:red;">*</span> Codigo de centro de votacion</label>
                    <input type="number" maxlength="9" size="9" placeholder="" name="codigo_centrovotacion" id="codigo_centrovotacion" value=""/>
                </div>
               <div class="large-6 medium-4 columns">
                    <label><span style="color:red;">*</span> Numero de mesa</label>
                    <input type="number" maxlength="2" placeholder="" name="mesa" id="mesa" value=""/>
                </div> 
            <div class="small-12 column text-right buttonPanel">
                <input id="btnEnviar" class="button small right" value="Reiniciar Prueba" type="submit">
            </div>
        <?= form_close() ?>

    </div>
</div>
