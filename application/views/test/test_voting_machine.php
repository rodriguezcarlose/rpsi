<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>

<h2 class="show-for-small-only"></h2>
<br>
<div class="container">
    <div class="row">
        <?php
        $erroresmv = $errormv->result_array();
// CON ESTE CÓDIGO SE ELIMINAN DEL COMBOBOX LOS ERRORES QUE YA SE TIENEN SELECCIONADOS EN BASE DE DATOS, PERMITIENDO QUE NO SE REPITAN ERRORES POR MV
//        if (isset($erroresseleccionados)) {
//            $total = count($erroresmv);
//            for ($i = 0; $i < $total; $i++) {
//                foreach ($erroresseleccionados as $row) {
//                    if (isset($erroresmv[$i])) {
//                        if ($erroresmv[$i]["id"] == $row["id"]) {
//                            unset($erroresmv[$i]);
//                        }
//                    }
//                }
//            }
//        }

        if (!is_null($consulta)) {
            $fila=$consulta->result();
            $centrovotacion= $fila[0]->codigo_centrovotacion .'-'. $fila[0]->centro_votacion;
            $finalizado = false;

            switch ($fila[0]->estatus) {
                case "SELECCIONADA":
                    $proxEstatus = "Instalaci&oacute;n";
                    break;
                case "INSTALADA":
                    $proxEstatus = "Apertura";
                    break;
                case "APERTURADA":
                    $proxEstatus = "Votaci&oacute;n";
                    break;
                case "VOTACION":
                    $proxEstatus = "Cierre";
                    break;
                case "CERRADA":
                    $proxEstatus = "Transmisi&oacute;n";
                    break;
                case "TRANSMITIDA":
                    $proxEstatus = "Transmisi&oacute;n";
                    $finalizado = true;
                    break;
                case "AUDITADA":
                    $proxEstatus = "Auditoria";
                    $finalizado = true;
                    break;
            }
        }
        ?>

        <h3>M&aacute;quina de Votaci&oacute;n. Fase <?php
            if(isset($proxEstatus)){
                echo "$proxEstatus";
            }
            ?></h3>
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
            <label>Estatus Actual Completado</label>
            <input type="text" name="estatus" id="estatus" disabled value="<?= $fila[0]->estatus; ?>"/>
        </div>
        <div class="large-6 medium-4 columns">
            <label>C&oacute;digo Validaci&oacute;n</label>
            <?php if( $fila[0]->id_estatus_maquina == 3 || $finalizado){?>
                <input type="text" placeholder="" name="codigo" id="codigo" disabled value="" />
            <?php }else{?>
                <input type="text" placeholder="" name="codigo" id="codigo" value=""/>
            <?php }?>
        </div>
        <div class="large-6 medium-4 columns">
            <label>Medio de Transmisi&oacute;n</label>
            <?php if($fila[0]->id_estatus_maquina !== "5" || $finalizado){?>
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
        <?php if ($fila[0]->id_estatus_maquina == "3" && !$stop_process) { ?>
            <h3>Cedulas Asociadas a esta Maquina</h3>
            <br>
            <table id="dataTable">
                <thead>
                <tr>
                    <td>Nacionalidad</td>
                    <td>Cédula</td>
                    <td>Nombre</td>
                    <td>Apellido</td>
                    <td>Estatus Votaci&oacute;n</td>
                </tr>
                </thead>
                <tbody>
                <?php
                if (isset($votantes)) {
                    foreach($votantes as $row) {
                        ?>
                        <tr>
                            <td><?php echo $row->tipo_documento ?></td>
                            <td><?php echo $row->documento_identidad ?></td>
                            <td><?php echo $row->nombre ?></td>
                            <td><?php echo $row->apellido ?></td>
                            <td>
                                <?php if ($row->voto == 1){ ?>
                                    <input type='checkbox' name='voto' id="<?= $row->id ?>" value='<?= $row->id ?>' checked />
                                <?php } else if ($row->voto == 0) { ?>
                                    <input type='checkbox' name='voto' id="<?= $row->id ?>" value='<?= $row->id ?>' />
                                <?php } ?>
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
                </tbody>
            </table>
            <?php if (isset($links)) { ?>
                <?php echo $links ?>
            <?php } ?>
        <?php }?>

        <?php
             // se tienen que mostrar en todas las fases menos en auditoria
            if (!$finalizado && !$stop_process) {
        ?>
                <h3>Errores registrados en fases anteriores</h3>
                <table id="dataTable">
                    <thead>
                    <tr>
                        <td>Error</td>
                        <td>Fase</td>
                        <td>Tipo de error</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                        if (isset($erroresseleccionados)) {
                        if (count($erroresseleccionados) > 0) {
                            foreach($erroresseleccionados as $row) {
                                ?>
                                <tr>
                                    <td><?php echo $row["error"] ?></td>
                                    <td><?php echo $row["fase"] ?></td>
                                    <td><?php echo $row["tipo_error"] ?></td>
                                </tr>
                                <?php
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="3">N/A</td>
                            </tr>
                            <?php
                        }
                        }
                    ?>
                    </tbody>
                </table>

            <h3>Registrar Errores</h3>
            <div class="large-12 medium-4 columns">
                <label>Buscar Error</label>
                <select data-autocomplete=""  multiple="" name="error[]" id = "error">
                    <option value="">Buscar errores</option>
                    <?php
                        foreach ($erroresmv as $error) {
                            ?>
                                <option value="<?= $error["id"] ?>"><?= $error["descripcion"] ?></option>
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
                        foreach ($tiporeemplazo->result() as $tipor) {
                    ?>
                        <option value="<?= $tipor->id?>"><?= $tipor->descripcion?></option>
                    <?php
                        }
                    ?>
                </select>
            </div>
            <br>
        <?php } ?>

        <?php if (!$finalizado && !$stop_process){?>
            <div class="small-12 column text-right buttonPanel">
                <input id="btnCloseModalEditor" class="button small right alert" value="Cancelar" type="submit" onclick="this.form.action = '<?=base_url()?>index.php/voting_machine/cancelar'">
                <input id="btnEnviar" class="button small right" value="Aceptar" type="submit">
            </div>
        <?php } else { ?>
            <div class="small-12 column text-right buttonPanel">
                <input type="hidden" value="<?= $fila[0]->codigo_centrovotacion; ?>" id="codigo_centrovotacion" name = "codigo_centrovotacion">
                <input type="hidden" value="<?= $fila[0]->mesa; ?>" id="mesa" name = "mesa">
                <input id="btnEnviar" class="button small right alert" value="Descargar Reporte" type="submit"onclick="this.form.action = '<?=base_url()?>index.php/report/pdf_gen'; this.form.method='POST'">
                <input id="btnEnviar" class="button small right" value="Finalizar" type="submit"onclick="this.form.action = '<?=base_url()?>index.php/voting_machine/cancelar'">
            </div>
        <?php } ?>
        <?= form_close() ?>
        <script>
            let checkboxes = document.getElementsByName('voto');
            for(let index in checkboxes) {
                checkboxes[index].onchange = updateFunc;
            }
            function updateFunc() {
                PostToServer(this.value, this.checked);
            }
        </script>

    </div>
</div>