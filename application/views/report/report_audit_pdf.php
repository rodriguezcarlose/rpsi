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
        <h3>Reporte de Auditoría de M&aacute;quina de Votaci&oacute;n</h3>
        <?php
        $fila=$consulta->result();
        $centrovotacion= $fila[0]->codigo_centrovotacion .'-'. $fila[0]->centro_votacion;

        if (!is_null($consulta_votos_auditoria)) {
            $votos_auditoria = $consulta_votos_auditoria->result();
        }

        if (!is_null($consulta_votos_totales)) {
            $votos_auditoria_totales = $consulta_votos_totales[0]["COUNT(*)"];
        }

        if (!is_null($consulta_votos_nulos)) {
            $votos_auditoria_nulos = $consulta_votos_nulos[0]["COUNT(*)"];
        }

        if (!is_null($consulta_votos_validos)) {
            $votos_auditoria_validos = $consulta_votos_validos[0]["COUNT(*)"];
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
        <h3>Lista de votos</h3>
        <?php
            if (isset($votos_auditoria)) {
                $cargo = '';
                $candidato = '';
                foreach ($votos_auditoria as $item) {
                    if ($cargo != $item->cargo) {
                        if ($candidato != ''){
                            echo "</tbody>";
                            echo "</table>";
                        }
                        $candidato = '';
                        $cargo = $item->cargo;
                        echo "<h4> - $item->cargo </h4>";
                    }
                    if ($candidato != $item->candidato) {
                        if ($candidato != ''){
                            echo "</tbody>";
                            echo "</table>";
                        }
                        $candidato = $item->candidato;
                        echo "<table id='dataTable'>";
                        echo "<thead>";
                        echo "<tr>";
                        echo "<td colspan='2' style='color: #007095'>$item->candidato</td>";
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
    </div>
</div>
</body>
</html>
