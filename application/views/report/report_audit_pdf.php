
<style>
    table {
        border: 1px solid black;
    }
</style>
        <?php
        $fila=$consulta->result();
        $centrovotacion= $fila[0]->codigo_centrovotacion .'-'. $fila[0]->centro_votacion;

        if (!is_null($consulta_votos_auditoria)) {
            $votos_auditoria = $consulta_votos_auditoria->result();
        }

        if (!is_null($consulta_votos_totales)) {
            $votos_auditoria_totales = $consulta_votos_totales;
        }

        if (!is_null($consulta_votos_nulos)) {
            $votos_auditoria_nulos = $consulta_votos_nulos;
        }

        if (!is_null($consulta_votos_validos)) {
            $votos_auditoria_validos = $consulta_votos_validos;
        }

        $operador = $user->result();
        ?>

        <br>
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
        <h3>Lista de votos</h3>
        <?php
            if (isset($votos_auditoria)) {
                $cargo = '';
                $candidato = '';

                foreach ($votos_auditoria as $item) {

                    $tmp_canditato = is_null($item->candidato) ? 'VOTO NULO' : $item->candidato;
                    $tmp_cargo = is_null($item->cargo) ? 'VOTO NULO' : $item->cargo;

                    if ($cargo != $tmp_cargo) {
                        if ($candidato != ''){
                            echo "</tbody>";
                            echo "</table>";
                        }
                        $candidato = '';
                        $cargo = $tmp_cargo;
                        echo "<h4> - $tmp_cargo </h4>";
                    }
                    if ($candidato != $tmp_canditato) {
                        if ($candidato != ''){
                            echo "</tbody>";
                            echo "</table>";
                        }
                        $candidato = $tmp_canditato;
                        echo "<table id='dataTable'>";
                        echo "<thead>";
                        echo "<tr>";
                        echo "<td colspan='2' style='color: #007095'>$tmp_canditato</td>";
                        echo "</tr>";
                        echo "<tr>";
                        echo "<td>ORGANIZACIÓN POLÍTICA</td>";
                        echo "<td>NÚMERO DE VOTOS</td>";
                        echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                    }
                    echo "<tr>";
                    echo "<td>$item->organizacion_politica</td>";
                    echo "<td>$item->num_votos</td>";
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "</table>";
            }
        ?>

        <?php
            echo "<table id='dataTable'>";
            echo "<thead>";
            echo "<tr>";
            echo "<td colspan='2' style='color: #007095'>Total de Votos</td>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            echo "<tr>";
            echo "<td>TOTAL VOTOS</td>";
            echo "<td>$votos_auditoria_totales</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<td>VOTOS NULOS</td>";
            echo "<td>$votos_auditoria_nulos</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<td>VOTOS VALIDOS</td>";
            echo "<td>$votos_auditoria_validos</td>";
            echo "</tr>";
            echo "</tbody>";
            echo "</table>";
        ?>
