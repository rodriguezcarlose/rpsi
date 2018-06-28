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

        if((isset($codigo_centrovotacionmesa)) && ($codigo_centrovotacionmesa!="")){
            $mostrar_codigo_centrovotacion=$codigo_centrovotacionesam;
        }else{
            $mostrar_codigo_centrovotacion="";
        }


        ?>
        <?= form_open('/audit/consultar') ?>
        <!--  div class="large-4 medium-4 columns">
                    <label><span style="color:red;">*</span> N&uacute;mero de meson</label>
                    <input type="text" maxlength="3" placeholder="" name="numero_meson" id="numero_meson" value="<?php echo $mostrar_numero_meson; ?>"  />
                </div-->
        <div class="large-8 medium-4 columns">
            <label><span style="color:red;">*</span> Codigo de centro de votacion mesa</label>
            <input type="text" maxlength="14" size="14" placeholder="010101001.01.1" name="codigo_centrovotacionmesa" id="codigo_centrovotacionmesa" value="<?php echo $mostrar_codigo_centrovotacion; ?>"  />
        </div>

        <div class="small-12 column text-right buttonPanel">
            <!-- input id="btnCloseModalEditor" class="button small right alert" value="Cancelar" type="submit" onclick="this.form.action = '<?=base_url()?>index.php/voting_machine/cancelar'"-->
            <input id="btnEnviar" class="button small right" value="Consultar" type="submit">
        </div>
        <?= form_close() ?>

    </div>
</div>
