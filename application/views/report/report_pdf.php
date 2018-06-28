<!DOCTYPE html>
<html>
    <head>
        <title>Registro Pruebas Sistema Integrado</title>
        <link href="C:/xampp/htdocs/rpsi/Content/foundation/foundation.css" rel="stylesheet"/>
        <link href="C:/xampp/htdocs/rpsi/Content/foundation/foundation.mvc.css" rel="stylesheet"/>
        <link href="C:/xampp/htdocs/rpsi/Content/foundation/foundation-icons.css" rel="stylesheet"/>
        <link href="C:/xampp/htdocs/rpsi/Content/xd.datetimepicker/jquery.datetimepicker.css" rel="stylesheet" />
        <link href="C:/xampp/htdocs/rpsi/Content/excle/excle.toast.css" rel="stylesheet"/>
        <link href="C:/xampp/htdocs/rpsi/Content/excle/excle.autocomplete.css" rel="stylesheet" />
        <link href="C:/xampp/htdocs/rpsi/Content/excle/excle.autocomplete.multiple.css" rel="stylesheet" />
        <link href="C:/xampp/htdocs/rpsi/Content/Site.css" rel="stylesheet"/>
        <!--  <script src='https://www.google.com/recaptcha/api.js'></script> -->
    </head>
    <body>
        <h2 class="show-for-small-only"></h2>
        <br>
        <div class="container">
            <div class="row">
                <h3>Reporte de Pruebas de la M&aacute;quina de Votaci&oacute;n</h3>
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

                <img style="width: 200px;" src="C:/xampp/htdocs/rpsi/Content/Images/cne_logo.png" />
                <img style="float: right; width: 200px;" src="C:/xampp/htdocs/rpsi/Content/Images/header-logo.png" />
                <br>
                <p style="text-align: right; font-size: 1.6875rem;">Fase Completada: <b><span style="color: #007095"><?php echo $fila[0]->estatus; ?></span></b></p>
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
                <br>
                <table id="dataTable">
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
                    } else { ?>
                        <tr>
                            <td colspan="3">N/A</td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
                <br>
                <table id="dataTable">
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
                <table id="dataTable">
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
                <table id="dataTable">
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
                <table id="dataTable">
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
                    } else { ?>
                        <tr>
                            <td colspan="3">N/A</td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>
