<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<h2 class="show-for-small-only"></h2>
<br>
<div class="container">
    <div class="row">
        <h3>Consulta M&aacute;quina de Votaci&oacute;n</h3>
        <?= form_open('/report/consulta_report_mv') ?>
        <div class="large-8 medium-4 columns">
            <label><span style="color:red;">*</span> C&oacute;digo de centro de votaci&oacute;n</label>
            <input type="text" maxlength="9" placeholder="010101001" name="codigo_centrovotacion" id="codigo_centrovotacion" onkeypress="return validar_numeros(event)" value="<?php echo set_value('codigo_centrovotacion'); ?>"/>
        </div>
        <div class="large-4 medium-4 columns">
            <label><span style="color:red;">*</span> N&uacute;mero de mesa</label>
            <input type="text" maxlength="2" placeholder="1" name="mesa" id="mesa" onkeypress="return validar_numeros(event)" value="<?php echo set_value('mesa'); ?>" />
        </div>
        <div class="small-12 column text-right buttonPanel">
            <input id="btnCloseModalEditor" class="button small right alert" value="Cancelar" type="submit" onclick="this.form.action = '<?=base_url()?>'">
            <input id="btnEnviar" class="button small right" value="Consultar" type="submit">
        </div>
        <?= form_close() ?>
    </div>
</div>