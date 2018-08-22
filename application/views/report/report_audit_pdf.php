
<style>
table, th, td {
        border: 1px solid black;
    border-collapse: collapse;
    }
</style>
        <?php
        $fila=$consulta->result();
        $centrovotacion= $fila[0]->codigo_centrovotacion .'-'. $fila[0]->centro_votacion;





        $operador = $user->result();
        ?>

<div class="container">
    <div class="row">
<p style="text-align: right;">Fase Completada: <b><span style="color: #007095"><?php echo $fila[0]->estatus; ?></span></b></p>
        <div class="field small-12 columns">
            <table id="dataTable">
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
        </div>

        <?php foreach ($estadisticas as $estadistica) { ?>
            <div class="field small-12 columns">
                <h3>Cargo: <?= $estadistica[0]; ?></h3>
            </div>
        <?php
            for ($i = 2; $i < count($estadistica); $i++) {
                $candidato = false;
                ?>
                <div class="field small-12 columns">
                    <table id="dataTable">
                        <?php
                        foreach ($estadistica[$i]->result() as $candidatos) {


                            if (!$candidato) {
                                $candidato = true;
                                ?>
                                <tr>
                                    <th colspan="2">
                                        <?= $candidatos->candidato; ?>
                                    </th>
                                </tr>
                                <tr>
                                    <th>ORGANIZACION POLITICA</th>
                                    <th>NUMERO DE VOTOS</th>
                                </tr>
                                <?php
                            }
                            ?>
                            <tr>
                                <td><?= $candidatos->siglas; ?></td>
                                <td><?= $candidatos->cantidad; ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>
                </div>
                <?php
                        }
            $votos = 0;
            $validos = 0;
            $nulos = 0;
            foreach ($totalvotos as $total){
                foreach ($total->result() as $result){
                    if ($result->id_cargo == $estadistica[1]){
                        $votos = $result->total_votos;
                    }
                }
            }
            foreach ($totalvotosvalidos as $total){
                foreach ($total->result() as $result){
                    if ($result->id_cargo == $estadistica[1]){
                        $validos = $result->total_votos;
                    }
                        }
                    }
            
            foreach ($totalvotosnulos as $total){
                foreach ($total->result() as $result){
                    if ($result->id_cargo == $estadistica[1]){
                        $nulos = $result->total_votos;
                    }
                }
            }
        ?>
            <div class="field small-12 columns">
                <table id="dataTable">
                    <tr>
                        <th colspan="2">VOTOS</th>
                    </tr>
                    <tr>
                        <th>TOTAL VOTOS</th>
                        <td><?= $votos;?></td>
                    </tr>
                    <tr>
                        <th>TOTAL VOTOS VALIDOS</th>
                        <td><?= $validos;?></td>
                    </tr>
                    <tr>
                        <th>TOTAL VOTOS NULOS</th>
                        <td><?= $nulos;?></td>
                    </tr>

                </table>
            </div>
            <?php
        }
        ?>
    </div>
</div>
