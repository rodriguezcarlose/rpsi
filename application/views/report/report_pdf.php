<style>
table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
}
</style>
        <br>
                <?php
                $fila=$consulta->result();
                $centrovotacion= $fila[0]->codigo_centrovotacion .'-'. $fila[0]->centro_votacion;
                if (!is_null($contingencia)) {
                    $reemplazos = $contingencia->result();
                }
                if (!is_null($errors)) {
                    $errores = $errors->result_array();
                }
                if (!is_null($voters)){
                    $votantes = $voters->result_array();
                }
                $operador = $user->result();
                ?>

<p style="text-align: right;">Fase Completada: <b><span style="color: #007095"><?php echo $fila[0]->estatus; ?></span></b></p>
<table>
                    <thead>
                    <tr>
                        <td>Estado:</td>
                        <td><?php echo $fila[0]->estado; ?></td>
                    </tr>
                    <tr>
                        <td>Municipio:</td>
                        <td><?php echo $fila[0]->municipio; ?></td>
                    </tr>
                    <tr>
                        <td>Parroquia:</td>
                        <td><?php echo $fila[0]->parroquia; ?></td>
                    </tr>
                    <tr>
                        <td>Centro:</td>
                        <td><?php echo $fila[0]->centro_votacion; ?></td>
                    </tr>
                    <tr>
                        <td>Código del Centro:</td>
                        <td><?php echo $fila[0]->codigo_centrovotacion; ?></td>
                    </tr>
                    <tr>
                        <td>Mesa:</td>
                        <td><?php echo $fila[0]->mesa; ?></td>
                    </tr>
                    <tr>
                        <td>Operador:</td>
                        <td><?php echo $operador[0]->nombre .' '.$operador[0]->apellido; ?></td>
                    </tr>
                    </thead>
                </table>
                <br>
<br>
<table>
                    <thead>
                    <tr>
                        <td colspan="3">Detalle de los errores encontrados en la fase de pruebas:</td>
                    </tr>
                    <tr>
                        <td>Descripción:</td>
                        <td>Fase Actual:</td>
                        <td>Tipo de Error:</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if (isset($errores)) {
                        foreach ($errores as $item) {
                            ?>
                            <tr>
                                <td><?php echo $item['error']; ?></td>
                                <td><?php echo $item['fase']; ?></td>
                                <td><?php echo $item['tipo_error']; ?></td>
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
                    ?>
                    </tbody>
                </table>
                <br>
<br>
<table>
                    <thead>
                    <tr>
                        <td>Medio de Transmisión:</td>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            <?php
                            if ($fila[0]->medio_transmision != ""){
                                echo $fila[0]->medio_transmision;
                            } else {
                                echo 'N/A';
                            }
                            ?>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <br>
<br>
<table>
                    <thead>
                    <tr>
                        <td colspan="4">Fase de Votación:</td>
                    </tr>
                    <tr>
                        <td>Nacionalidad:</td>
                        <td>Cédula:</td>
                        <td>Nombre:</td>
                        <td>Apellido:</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if (isset($votantes)) {
                        foreach ($votantes as $item) {
                            ?>
                            <tr>
                                <td><?php echo $item['tipo_documento']; ?></td>
                                <td><?php echo $item['documento_identidad']; ?></td>
                                <td><?php echo $item['nombre']; ?></td>
                                <td><?php echo $item['apellido']; ?></td>
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
                    ?>
                    </tbody>
                </table>
<br>
<br>
<table>
                    <thead>
                    <tr>
                        <td>Auditoría:</td>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            <?php
                            if ($fila[0]->id_estatus_maquina == '7'){
                                echo $fila[0]->estatus;
                            } else {
                                echo 'N/A';
                            }
                            ?>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <br>
<br>
<table>
                    <thead>
                    <tr>
                        <td colspan="3">Reemplazos:</td>
                    </tr>
                    <tr>
                        <td>Descripción:</td>
                        <td>Fase Actual:</td>
                        <td>Entregado:</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if (isset($reemplazos)) {
                        foreach ($reemplazos as $item) {
                            ?>
                            <tr>
                                <td><?php echo $item->reemplazo; ?></td>
                                <td><?php echo $item->fase; ?></td>
                                <td>
                                    <?php
                                    if ($item->entregado) {
                                        echo 'SI';
                                    } else {
                                        echo 'NO';
                                    }
                                    ?>
                                </td>
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
                    ?>
                    </tbody>
                </table>
