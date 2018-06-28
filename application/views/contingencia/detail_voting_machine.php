<?php
/**
 * Created by PhpStorm.
 * User: Humberto Fernández
 * Date: 4/6/2018
 * Time: 2:33 PM
 */
    defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<h2 class="show-for-small-only"></h2>
<br>
<div class="container">
    <div class="row">
        <h3>M&aacute;quina de Votaci&oacute;n</h3>

        <?php
            $fila=$consulta->result();
            $centrovotacion= $fila[0]->codigo_centrovotacion .'-'. $fila[0]->centro_votacion;
            if (isset($contingencia)) {
                $reemplazos = $contingencia->result();
            }
        ?>

        <form action="http://localhost/rpsi/index.php/contingencia/liberar" method="post" onsubmit="return atleast_onecheckbox(event)" >
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

            <h3>Contingencia - Reemplazos</h3>
            <?php
                if (isset($reemplazos)) {
            ?>
                <table id="dataTable">
                    <thead>
                        <tr>
                            <td>Reemplazo</td>
                            <td>Entregar</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach($reemplazos as $row) {
                        ?>
                            <tr>
                                <td><?php echo $row->reemplazo ?></td>
                                <td><input type='checkbox' name='reemplazo[]' id="<?= $row->id ?>" value='<?= $row->id ?>' /></td>
                            </tr>
                        <?php
                            }
                        ?>
                    </tbody>
                </table>
            <?php
                } else {
            ?>
                   <p style="text-align: center;">No hay reemplazos disponibles para está Máquina de Votación.</p>
            <?php
                }
            ?>

            <div class="small-12 column text-right buttonPanel">
                <?php
                    if (isset($contingencia)) {
                ?>
                        <input id="btnEnviar" class="button small right" value="Aceptar" type="submit">
                <?php
                    }
                ?>
            </div>
        </form>

    </div>
</div>

<script>
    function atleast_onecheckbox(e) {
        if ($("input[type=checkbox]:checked").length === 0) {
            e.preventDefault();
            var title = "¡ERROR!";
            var content = "si quieres continuar debes seleccionar al menos un reemplazo para entregar";
            var type = 2;
            toastMessage(title, content, type, null);
            return false;
        }
    }
</script>
