<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<h2 class="show-for-small-only"></h2>
<br>
<div class="container">
    <div class="row">
        <h3> M&aacute;quina de Votaci&oacute;n Elegida para Auditor&iacute;a</h3>

        <?php
        $fila = $consulta->result();
        $centrovotacion = $fila[0]->codigo_centrovotacion . '-' . $fila[0]->centro_votacion;

        if ($consulta_cargo_candidato_partido != null) {
            $cargo_candidato_partido = $consulta_cargo_candidato_partido->result();
        }

        if (isset($consulta_votos_auditoria)) {
            if ($consulta_votos_auditoria != null) {
                $votos_auditoria = $consulta_votos_auditoria->result();
            }
        }

        if ($fila[0]->estatus == "AUDITADA") {
            $auditoria_status = true;
        } else {
            $auditoria_status = false;
        }
        ?>

        <div class="large-12 medium-4 columns">
            <label>Centro de votaci&oacute;n</label>
            <input type="text" placeholder="" name="centrovotacion" id="centrovotacion" disabled
                   value="<?= $centrovotacion; ?>"/>
        </div>
        <div class="large-4 medium-4 columns">
            <label>Modelo M&aacute;quina Votaci&oacute;n</label>
            <input type="text" placeholder="" name="modelomaquina" id="modelomaquina" disabled
                   value="<?= $fila[0]->modelo_maquina; ?>"/>
        </div>
        <div class="large-4 medium-4 columns">
            <label>N&uacute;mero de mesa</label>
            <input type="text" placeholder="" name="mesa" id="mesa" disabled value="<?= $fila[0]->mesa; ?>"/>
        </div>
        <div class="large-4 medium-4 columns">
            <label>Estatus</label>
            <input type="text" placeholder="" name="estatus" id="estatus" disabled value="<?= $fila[0]->estatus; ?>"/>
        </div>

        <?= form_open('/audit/procesar') ?>
        <input type="hidden" value="<?= $fila[0]->id; ?>" id="id" name="id">
        <input type="hidden" value="<?= $fila[0]->codigo_centrovotacion; ?>" id="codigo_centrovotacion" name="codigo_centrovotacion">
        <input type="hidden" value="<?= $fila[0]->mesa; ?>" id="mesa" name="mesa">
        <input type="hidden" value="<?= $fila[0]->estatus; ?>" id="estatus"  name="estatus">
        <h3> Auditor&iacute;a</h3>
        <div class="field small-12 columns">
            <?php
            if ($cargo_candidato_partido) {
                $flag = 0;
                foreach ($cargo_candidato_partido as $item) {
                    if ($flag != $item->id_cargo) {
                        if ($flag != 0){
                            echo "</select>";
                        }
                        $flag = $item->id_cargo;
                        echo "<h4> - Cargo: $item->cargo </h4>";
                        if ($auditoria_status) {
                            echo "<select id='$item->id_opcion_boleta' name='$item->id_opcion_boleta' disabled>";
                            echo "<option selected='selected' value=''>Seleccione</option>";
                            echo "<option value='0'>VOTO NULL</option>";
                            //echo "<option value='$item->id_opcion_boleta'>$item->candidato - $item->organizacion_politica</option>";
                            //echo "</select>";
                        } else {
                            echo "<select id='$item->id_opcion_boleta' name='$item->id_opcion_boleta'>";
                            echo "<option selected='selected' value=''>Seleccione</option>";
                            echo "<option value='0'>VOTO NULL</option>";
                            //echo "<option value='$item->id_opcion_boleta'>$item->candidato - $item->organizacion_politica</option>";
                            //echo "</select>";
                        }
                        echo "<label for='$item->id_opcion_boleta'>Candidato - Partido Político:</label>";
                    }
                    echo "<option value='$item->id_opcion_boleta'>$item->candidato - $item->organizacion_politica</option>";
                }
                echo "</select>";
            }
            ?>
        </div>

        <?php if (!$auditoria_status) { ?>
            <div class="small-1 column right buttonPanel<br>">
                <input id="btnEnviar" class="button small right" value="Registrar" type="submit">
            </div>
        <?php } ?>
        <div class="field small-12 columns">
        <h3>Lista de votos</h3>
        <?php
        if (isset($votos_auditoria)) {
            $flag=0; $i=0;
            foreach ($votos_auditoria as $item) {
                if ($flag != $item->cod_voto) {
                    if ($flag != 0){
                        echo "</tbody>";
                        echo "</table>";
                    }
                    $flag = $item->cod_voto;
                    ++$i;
                    echo "<table id='dataTable'>";
                    echo "<thead>";
                    echo "<tr>";
                    echo "<td colspan='3'>VOTO $i</td>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                }

                if ($item->cargo == null && $item->candidato == null && $item->organizacion_politica == null) {
                    echo "<tr>";
                    echo "<td colspan='3'>NULL</td>";
                    echo "</tr>";
                } else {
                    echo "<tr>";
                    echo "<td>$item->cargo</td>";
                    echo "<td>$item->candidato</td>";
                    echo "<td>$item->organizacion_politica</td>";
                    echo "</tr>";
                }
            }
            echo "</tbody>";
            echo "</table>";
        }
        ?>
        </div>

        <div class="small-12 column text-right buttonPanel">
            <?php if (!$auditoria_status) { ?>
                <input id="btnEnviar" class="button small" value="Finalizar Auditor&iacute;a" type="submit"
                       onclick="this.form.action = '<?= base_url() ?>index.php/audit/finishAudit'">
            <?php } else { ?>
            <input id="btnEnviar" class="button small right warning" value="Descargar Auditoria" type="submit"
                   onclick="this.form.action = '<?= base_url() ?>index.php/report/pdf_gen_auditoria'">
            <?php } ?>
            <input id="btnEnviar" class="button small right alert" value="Volver" type="submit"
                   onclick="this.form.action = '<?= base_url() ?>index.php/audit/index'">
        </div>
    </div>
    <?= form_close() ?>
</div>