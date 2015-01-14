<h3 align="center">VALIDACIÓN DE GARANTÍAS</h3>
<h5 align="center">No de Autorizaciones Enviadas &nbsp;<?= $contador ?> </h5>
<?php $i = 1; ?>
<?php foreach ($respuesta as $res) { ?>
    <h5 align="center">Solicitud No : <?= $i ?></h5>
    <h5 align="center">Respuesta : &nbsp;<?php
        if ($res['EXCEPCION'] == 3) {
            echo "ACEPTADO";
        } else if ($res['EXCEPCION'] == 4) {
            echo "Rechazado";
        } else {
            echo "En espera";
        }
        ?> </h5>
    <?php $i++; ?> 
<?php } ?>
<br>
<table align="center">
    <tr>
        <td>El valor de su deuda es:</td>
        <td align="right"><b><?php
                if (!empty($totalliquidacion)) {
                    echo "$ " . $totalliquidacion;
                } else {
                    echo "0";
                }
                ?></b></td>
    </tr>
    <tr>
        <td style="width: 250px">El valor de sus garantías es</td>
        <td align="right"><b><?php
                    echo "$ " . $total;                
                ?></b></td>
    </tr>
</table>
<br>
<?php
if (!empty($respuesta[0]['EXCEPCION'])) {
    $excepcion = $respuesta[0]['EXCEPCION'];
} else {
    $excepcion = "";
}
if ($contador <= 2) {
    ?>

    <?php if (($contador <= 2 && $excepcion == "") || ($contador <= 2 && $excepcion == 4)) { ?>
        <?php if ($total < $totalliquidacion) { ?>
            <table align="center" id="deudores">
                <tr>
                    <td colspan="2">Desea enviar autorizar para crear el acuerdo de pago sin garantía</td>
                </tr>
                <tr>
                    <td align="center">
                        <select id="autorizaciongarantia">
                            <option value="">-Seleccionar-</option>
                            <option value="1">Si</option>
                            <option value="2">No</option>
                        </select>
                    </td>
                </tr>
            </table>
            <div align="center"><button type="button" id="validar" class="btn btn-success">Enviar</button></div>
            <?php
        }
    } else if ($excepcion == 3) {
        
    }
} else {
    if ($ultimarespuesta[0]['EXCEPCION'] == 1) {
        echo "<h5>Solicitud Aceptada</h5>";
    } else if ($ultimarespuesta[0]['EXCEPCION'] == 2) {
        echo "<h5 align='center'>Solicitud Rechazada</h5>";
    } else {
        echo "<h5 align='center'>En espera de respuesta</h5>";
    }
}

if ($total > $totalliquidacion || $excepcion == 3) {
    ?>

    <table align="center" id="rectificacion">
        <tr>
            <td>EL OBLIGADO PERTENECE A LA LISTA DE DEUDORES MOROSOS</td>
            <td>
                <select style="width: 130px" id="mora">
                    <option value="">-Seleccionar-</option>
                    <option value="1">Si</option>
                    <option value="2">No</option>
                </select>
            </td>
        </tr>
    </table>

    <?php
}
if ($total > $totalliquidacion || $excepcion == 3) {
    ?>
    <form id="f1" method="post" enctype="multipart/form-data" >
    <table id="subidaarchivo" align="center"></table>    
        <table align="center">
            <tr>
                <td><button type="button" id="atras" class="btn btn-warning">Atras</button></td>
                <td><button type="button" id="siguiente" class="btn btn-success">Siguiente</button></td>
            </tr>
        </table>
        <input type="hidden" name="liquidacion" value="<?= $liquidacion ?>">
        <input type="hidden" name="nitg" value="<?= $nit ?>">
        <input type="hidden" name="fiscalizacion" value="<?= $fiscalizacion ?>">
    </form>
<?php } ?>
<script>
    var autorizaciones = "<?= $contador ?>";

    $('#siguiente').click(function() {

        $('#f1').submit(function() {
            if ($('#archivo').val() != "")
            {
                return true;
            }
            else {
                return false;
            }
        })
    });

//------------------------------------------------------------------------------
//*************** SUBE DOCUMENTO DE MOROSO *************************************    
//------------------------------------------------------------------------------

    $('#mora').change(function() {
        if ($(this).val() == 2) {
            var archivo = "<tr id='generaarchivo'><td>Archivo certificando que el cliente no esta reportado en el BDME</td><td><input type='file' id='archivo' name='archivo'></td></tr>";
            $('#subidaarchivo').append(archivo);
        } else {
            if ($('#generaarchivo').length != 0)
                $('#generaarchivo').remove();
        }
    });


//------------------------------------------------------------------------------
//**************** AUTORIZACION SIN GARANTIAS **********************************    
//------------------------------------------------------------------------------    

    $('#autorizaciongarantia').change(function() {
        if ($('#carguedatos').length != 0)
            $('#carguedatos').remove();
        if ($(this).val() == 1) {
            var tabla = "<tr class='observaciones'><td>Observaciones</td></tr><tr class='observaciones'><td colspan='2'><textarea id='obser' style='width: 538px; height: 143px;'></textarea></td></tr>"
            $('#deudores').append(tabla);
        }
        else if ($(this).val() == 2) {
            if ($('.observaciones').length != 0)
                $('.observaciones').remove();
        }
    });

    $('#validar').click(function() {

        if ($('#carguedatos').length == 0)
            $('#carguedatos').remove();
        if ($('#autorizaciongarantia').val() == 1)
        {
            if ($('#obser').val() != "") {
                $(this).attr('disabled', true);
                var url = "<?= base_url('index.php/acuerdodepago/guardaexcepcion') ?>";
                var observacion = $('#obser').val();
                var fiscalizacion = "<?= $fiscalizacion ?>";
                $.post(url, {observacion: observacion, fiscalizacion: fiscalizacion}, function(data) {
                    window.location = "<?= base_url().'index.php/bandejaunificada/procesos' ?>";
                });
            } else {
                if ($('#carguedatos').length == 0)
                    $('#deudores').append('<tr id="carguedatos"><td style="color : red" align="center"><b>Por favor Ingresar la observación</b></td></tr>');
            }

        } else if ($('#autorizaciongarantia').val() == 1) {

        }
    });

//    alert('hola');
    $('#siguiente').click(function() {
//        alert(respuesta);
        var respuesta = $('#mora').val();
        if (respuesta == 2) {
            var url = "<?= base_url('index.php/acuerdodepago/acuerdo') ?>";
            $('#f1').attr('action', url);
            $('#f1').submit();
        } else if (respuesta == 1) {
            var url = "<?= base_url('index.php/acuerdodepago/mora') ?>";
            $('#f1').attr('action', url);
            $('#f1').submit();
        }
    });

    $('#atras').click(function() {
        window.location = "<?= base_url().'index.php/bandejaunificada/procesos' ?>";
    });
</script>    
