<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<br>
<div class="container">
	<div class="col-md-20">
        <?= form_open('/report/generar_excel')?>
            <div class="row">
                <div class="col-md-20">
                    <div class="page-header">
                        <h3>Reporte de Errores</h3>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-20">
                    <table id="dataTable">
                        <thead>
                            <tr>
                                <td>Centro de Votación</td>
                                <td>Mesa</td>
                                <td>Descripción del Error</td>
                                <td>Módelo MV</td>
                                <td>Medio de Transmisión</td>
                                <td>Estatus MV</td>
                                <td>Reemplazo</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($consulta)){
                                foreach ($consulta as $records) {?>
                            <tr>
                                <td><?= $records["codigo_centrovotacion"]?></td>
                                <td><?= $records["mesa"]?></td>
                                <td><?= $records["error"]?></td>
                                <td><?= $records["modelo_maquina"]?></td>
                                <td>
                                    <?php
                                        if ($records["medio_transmision"] === "\x0d" || $records["medio_transmision"] == null) {
                                            echo 'N/A';
                                        } else {
                                            echo $records["medio_transmision"];
                                        }
                                    ?>
                                </td>
                                <td><?= $records["estatus_maquina"]?></td>
                                <td>
                                    <?php if ($records["reemplazo"] != null) {
                                        echo $records["reemplazo"];
                                    } else {
                                        echo 'N/A';
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                    <div class="small-12 column text-right buttonPanel">
                        <input id="btnCloseModalEditor" class="button small right alert" value="Cancelar" type="submit" onclick="this.form.action = '<?=base_url()?>index.php'">
                        <input id="btnEnviar" class="button small right" value="Descargar" type="submit">
                    </div>
                </div>
            </div>
		<?= form_close()?>
	</div>
</div>
