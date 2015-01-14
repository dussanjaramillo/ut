<h1>Generar Resoluci&oacute;n</h1>
<?php
//echo "<pre>";
//print_r($detalle);
//echo "</pre>";

$table = "";
$cantidad = count($detalle);
for ($i = 0; $i < $cantidad; $i++) {
    $table = "<table border='0' width='100%'>";
    $detalles = $detalle[$i];
    if (!empty($detalles['NUM_LIQUIDACION'])) {
        $table .="<tr><td colspan='2' align='center' style='background:#5bb75b'>PRESUNTIVA</td></tr>";
        $table .="<tr><td >NUM lIQUIDACION</td><td>" . number_format($detalles['NUM_LIQUIDACION']) . "</td></tr>";
        $table .="<tr><td>VLR CONTRATO TODOCOSTO</td><td>" . number_format($detalles['VLR_CONTRATO_TODOCOSTO']) . "</td></tr>";
        $table .="<tr><td>VLR CONTRATO MANO OBRA</td><td>" . number_format($detalles['VLR_CONTRATO_MANO_OBRA']) . "</td></tr>";
        $table .="<tr><td>PAGOS FIC DESCONTAR</td><td>" . number_format($detalles['PAGOS_FIC_DESCONTAR']) . "</td></tr>";
    }
    if (!empty($detalles['ANO'])) {
        $table .="<tr><td colspan='2' align='center' style='background:#5bb75b'>NORMATIVA</td></tr>";
        $table .="<tr><td>ANO</td><td>" . $detalles['ANO'] . "</td></tr>";
        $table .="<tr><td>NRO_TRABAJADORES</td><td>" . $detalles['NRO_TRABAJADORES'] . "</td></tr>";
        $table .="<tr><td>TOTAL_ANO</td><td>" . $detalles['TOTAL_ANO'] . "</td></tr>";
        $table .="<tr><td>MESCOBRO</td><td>" . $detalles['MESCOBRO'] . "</td></tr>";
    }
}
$table .="</table><p>";







$array = array();

$array['regional'] = $informacion['regional'];
$array['num_liquidacion'] = $informacion['liquidacion'];
$array['ciudad'] = $informacion['ciudad'];
$array['nit'] = $informacion['nit'];
$array['nom_empresa'] = $informacion['nombreempleador'];
$array['fecha_liquidacion'] = $informacion['fecha_liquidacion'];
$array['valor_letra'] = $informacion['valor_letras'];
$array['valor'] = $informacion['valor_pesos'];
$array['director'] = $informacion['director_regional'];
$array['proyecto'] = $informacion['elaboro'];
$array['coordinador'] = $informacion['coordinador'];
$array['tabla'] = '<table width="100%" border="1">
            <tr>
                    <td>No Liquidaci&oacute;n</td>
                    <td>Fecha</td>
                    <td>Per&iacute;odo</td>
                    <td>Concepto de Obligaci&oacute;n</td>
                    <td>Valor Liquidaci&oacute;n</td>
            </tr>
            <tr>
                    <td>' . $informacion['liquidacion'] . '</td>
                    <td>' . $informacion['fecha_liquidacion'] . '</td>
                    <td>' . $informacion['periodo_desde'] . '-' . $informacion['periodo_hasta'] . '</td>
                    <td>' . $informacion['concepto'] . '</td>
                    <td>$' . number_format($informacion['valor_pesos2']) . '</td>
            </tr>

            </table>';

switch (date("m")) {
    case 1:
        $mes = 'Enero';
        break;
    case 2:
        $mes = 'Febrero';
        break;
    case 3:
        $mes = 'Marzo';
        break;
    case 4:
        $mes = 'Abril';
        break;
    case 5:
        $mes = 'Mayo';
        break;
    case 6:
        $mes = 'Junio';
        break;
    case 7:
        $mes = 'Julio';
        break;
    case 8:
        $mes = 'Agosto';
        break;
    case 9:
        $mes = 'Septiembre';
        break;
    case 10:
        $mes = 'Octubre';
        break;
    case 11:
        $mes = 'Noviembre';
        break;
    case 12:
        $mes = 'Diciembre';
        break;
}
$array['fecha_creacion'] = date("d") . ' Dias del mes de ' . $mes . ' del ' . date("Y");


$html = Notificacionacta::plantillas(102, $array);


if (!empty($pdf)) {
    Notificacionacta::pdf(base64_decode($informacion['proyecto']));
} else {
//    echo $html
    ?>
    <div style="display: "><textarea id="informacion" style="height: 700px" class="testo" name="informacion"><?php echo $html; ?></textarea></div>
    <?php
}
?>


<div class="row show-grid" style="margin: 10px auto;">
    <div class="span2" style="margin: 10px auto;text-align:center">
        <?php
        $attributes = array('onsubmit' => 'return subir_archivo()', "id" => "myform");
        echo form_open("notificacionacta/resolucion_guardar", $attributes);
        ?>
        <input type="hidden" name="email_regional" id="email_regional" value="<?php echo $informacion['email_regional'] ?>">
        <input type="hidden" name="id_resolucion" id="id_resolucion" value="<?php echo $informacion['id_resolucion'] ?>">
        <input type="hidden" id="proyecto" name="proyecto" value=''>
        <input type="hidden" id="id_director_regional" name="id_director_regional" value="<?php echo $informacion['id_director_regional']; ?>">
        <input type="hidden" id="id_coordinador" name="id_coordinador" value="<?php echo $informacion['id_coordinador']; ?>">
        <input type="hidden" id="fechacreacion" name="fechacreacion" value="<?php echo $informacion['fechacreacion']; ?>">
        <input type="hidden" id="valor_pesos" name="valor_pesos" value="<?php echo $informacion['valor_pesos']; ?>">
        <input type="hidden" id="ciudad" name="ciudad" value="<?php echo $informacion['ciudad']; ?>">
        <input type="hidden" id="id_regional" name="id_regional" value="<?php echo $informacion['id_regional']; ?>">
        <input type="hidden" id="regional" name="regional" value="<?php echo $informacion['regional']; ?>">
        <input type="hidden" id="nombreempleador" name="nombreempleador" value="<?php echo $informacion['nombreempleador']; ?>">
        <input type="hidden" id="nit" name="nit" value="<?php echo $informacion['nit']; ?>">
        <input type="hidden" id="liquidacion" name="liquidacion" value="<?php echo $informacion['liquidacion']; ?>">
        <input type="hidden" id="fecha_liquidacion" name="fecha_liquidacion" value="<?php echo $informacion['fecha_liquidacion']; ?>">
        <input type="hidden" id="cod_fis" name="cod_fis" value="<?php echo $informacion['cod_fiscalizacion']; ?>">
        <input type="hidden" id="periodo_desde" name="periodo_desde" value="<?php echo $informacion['periodo_desde']; ?>">
        <input type="hidden" id="periodo_hasta" name="periodo_hasta" value="<?php echo $informacion['periodo_hasta']; ?>">
        <input type="hidden" id="valor_pesos2" name="valor_pesos2" value="<?php echo $informacion['valor_pesos2']; ?>">
        <input type="hidden" id="valor_letras" name="valor_letras" value="<?php echo $informacion['valor_letras']; ?>">
        <input type="hidden" id="id_concepto" name="id_concepto" value="<?php echo $informacion['id_concepto']; ?>">
        <input type="hidden" id="concepto" name="concepto" value="<?php echo $informacion['concepto']; ?>">
        <input type="hidden" id="elaboro" name="elaboro" value="<?php echo $informacion['elaboro']; ?>">
        <input type="hidden" id="reviso" name="reviso" value="<?php echo $informacion['reviso']; ?>">
        <input type="hidden" id="abogado" name="abogado" value="<?php echo $informacion['abogado']; ?>" >
        <input type="hidden" id="coordinador" name="coordinador" value="<?php echo $informacion['coordinador']; ?>" >
        <input type="hidden" id="director_regional" name="director_regional" value="<?php echo $informacion['director_regional']; ?>" >
        <input type="hidden" id="fecha_actual" name="fecha_actual" value="<?php echo $informacion['fecha_actual']; ?>" >
        <?php
        $data = array(
            'name' => 'button',
            'id' => 'submit-button',
            'value' => 'Aprobar',
            'type' => 'submit',
            'onsubmit' => 'return subir_archivo',
            'content' => '<i class="fa fa-floppy-o fa-lg"></i> Continuar',
            'class' => 'btn btn-success'
        );
        echo form_button($data);
        ?>
        <?php echo form_close(); ?>
    </div>

    <div class="span2" style="margin: 10px auto;text-align:center">
        <?php echo form_open("notificacionacta/resolucion_pdf", array("id" => "myform2", 'onsubmit' => "return subir_archivo()", "target" => "_blank")); ?>
        <input type="hidden" name="email_regional" id="email_regional" value="<?php echo $informacion['email_regional'] ?>">
        <input type="hidden" name="id_resolucion" id="id_resolucion" value="<?php echo $informacion['id_resolucion'] ?>">
        <input type="hidden" id="proyecto2" name="proyecto" value=''>
        <input type="hidden" id="fechacreacion" name="fechacreacion" value="<?php echo $informacion['fechacreacion']; ?>">
        <input type="hidden" id="valor_pesos" name="valor_pesos" value="<?php echo $informacion['valor_pesos']; ?>">
        <input type="hidden" id="ciudad" name="ciudad" value="<?php echo $informacion['ciudad']; ?>">
        <input type="hidden" id="id_regional" name="id_regional" value="<?php echo $informacion['id_regional']; ?>">
        <input type="hidden" id="regional" name="regional" value="<?php echo $informacion['regional']; ?>">
        <input type="hidden" id="nombreempleador" name="nombreempleador" value="<?php echo $informacion['nombreempleador']; ?>">
        <input type="hidden" id="nit" name="nit" value="<?php echo $informacion['nit']; ?>">
        <input type="hidden" id="liquidacion" name="liquidacion" value="<?php echo $informacion['liquidacion']; ?>">
        <input type="hidden" id="fecha_liquidacion" name="fecha_liquidacion" value="<?php echo $informacion['fecha_liquidacion']; ?>">
        <input type="hidden" id="cod_fis" name="cod_fis" value="<?php echo $informacion['cod_fiscalizacion']; ?>">
        <input type="hidden" id="periodo_desde" name="periodo_desde" value="<?php echo $informacion['periodo_desde']; ?>">
        <input type="hidden" id="periodo_hasta" name="periodo_hasta" value="<?php echo $informacion['periodo_hasta']; ?>">
        <input type="hidden" id="valor_pesos2" name="valor_pesos2" value="<?php echo $informacion['valor_pesos2']; ?>">
        <input type="hidden" id="valor_letras" name="valor_letras" value="<?php echo $informacion['valor_letras']; ?>">
        <input type="hidden" id="id_concepto" name="id_concepto" value="<?php echo $informacion['id_concepto']; ?>">
        <input type="hidden" id="concepto" name="concepto" value="<?php echo $informacion['concepto']; ?>">
        <input type="hidden" id="elaboro" name="elaboro" value="<?php echo $informacion['elaboro']; ?>">
        <input type="hidden" id="reviso" name="reviso" value="<?php echo $informacion['reviso']; ?>">
        <input type="hidden" id="abogado" name="abogado" value="<?php echo $informacion['abogado']; ?>" >
        <input type="hidden" id="coordinador" name="coordinador" value="<?php echo $informacion['coordinador']; ?>" >
        <input type="hidden" id="director_regional" name="director_regional" value="<?php echo $informacion['director_regional']; ?>" >
        <input type="hidden" id="fecha_actual" name="fecha_actual" value="<?php echo $informacion['fecha_actual']; ?>" >
        <?php
        $data = array(
            'name' => 'button',
            'id' => 'submit-button',
            'value' => 'pdf',
            'type' => 'submit',
            'onsubmit' => 'return subir_archivo',
            'content' => '<i class="fa fa-file-pdf-o"></i> PDF',
            'class' => 'btn btn-info'
        );
        echo form_button($data);
        ?>
        <?php echo form_close(); ?>
    </div>
    <div class="span2" style="margin: 10px auto;text-align:center">
        <button onclick="detalle()" class=" btn btn-success">Detalle Liquidaci&oacute;n</button>
    </div>
    <div class="span2" style="margin: 10px auto;text-align:center">
        <button onclick="cancelar()" class=" btn btn-warning"><li class="fa fa-minus-circle"></li> Cancelar</button>
    </div>
</div>
<div style="display: none" id="detalle_info">
    <?php echo $table; ?>
</div>
<script>
    function detalle() {
        $('#detalle_info').dialog({
            autoOpen: true,
            modal: true,
            width: 600,
        });
        return false;
    }


    tinymce.init({
        language: 'es',
        selector: "textarea",
        theme: "modern",
        plugins: [
            "advlist autolink lists link  charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen",
                    //    "insertdatetime media nonbreaking save table contextmenu directionality",
                    //  "emoticons template paste textcolor moxiemanager"
        ],
        toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
        toolbar2: "print preview media | forecolor backcolor emoticons",
        image_advtab: true,
        templates: [
            {title: 'Test template 1', content: '<b>Test 1</b>'},
            {title: 'Test template 2', content: '<em>Test 2</em>'}
        ],
        autosave_ask_before_unload: false
    });

    function subir_archivo() {
        var informacion = tinymce.get('informacion').getContent();
        informacion = base64_encode(informacion);
        $('#proyecto2').val(informacion);
        $('#proyecto').val(informacion);
        return true;
    }



    function base64_encode(data) {
        // Encodes data with MIME base64
        // 
        // +    discuss at: http://kevin.vanzonneveld.net/techblog/article/javascript_equivalent_for_phps_base64_encode/
        // +       version: 805.821
        // +   original by: Tyler Akins (http://rumkin.com)
        // +   improved by: Bayron Guevara
        // +   improved by: Thunder.m
        // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)        
        // -    depends on: utf8_encode
        // *     example 1: base64_encode('Kevin van Zonneveld');
        // *     returns 1: 'S2V2aW4gdmFuIFpvbm5ldmVsZA=='

        // mozilla has this native
        // - but breaks in 2.0.0.12!
        //if (typeof window['atob'] == 'function') {
        //    return atob(data);
        //}

        var b64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
        var o1, o2, o3, h1, h2, h3, h4, bits, i = ac = 0, enc = "", tmp_arr = [];
        data = utf8_encode(data);

        do { // pack three octets into four hexets
            o1 = data.charCodeAt(i++);
            o2 = data.charCodeAt(i++);
            o3 = data.charCodeAt(i++);

            bits = o1 << 16 | o2 << 8 | o3;

            h1 = bits >> 18 & 0x3f;
            h2 = bits >> 12 & 0x3f;
            h3 = bits >> 6 & 0x3f;
            h4 = bits & 0x3f;

            // use hexets to index into b64, and append result to encoded string
            tmp_arr[ac++] = b64.charAt(h1) + b64.charAt(h2) + b64.charAt(h3) + b64.charAt(h4);
        } while (i < data.length);

        enc = tmp_arr.join('');

        switch (data.length % 3) {
            case 1:
                enc = enc.slice(0, -2) + '==';
                break;
            case 2:
                enc = enc.slice(0, -1) + '=';
                break;
        }

        return enc;
    }
    function utf8_encode(str_data) {
        // Encodes an ISO-8859-1 string to UTF-8
        // 
        // +    discuss at: http://kevin.vanzonneveld.net/techblog/article/javascript_equivalent_for_phps_utf8_encode/
        // +       version: 805.821
        // +   original by: Webtoolkit.info (http://www.webtoolkit.info/)
        // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)        
        // *     example 1: utf8_encode('Kevin van Zonneveld');
        // *     returns 1: 'Kevin van Zonneveld'

        str_data = str_data.replace(/\r\n/g, "\n");
        var tmp_arr = [], ac = 0;

        for (var n = 0; n < str_data.length; n++) {
            var c = str_data.charCodeAt(n);
            if (c < 128) {
                tmp_arr[ac++] = String.fromCharCode(c);
            } else if ((c > 127) && (c < 2048)) {
                tmp_arr[ac++] = String.fromCharCode((c >> 6) | 192);
                tmp_arr[ac++] = String.fromCharCode((c & 63) | 128);
            } else {
                tmp_arr[ac++] = String.fromCharCode((c >> 12) | 224);
                tmp_arr[ac++] = String.fromCharCode(((c >> 6) & 63) | 128);
                tmp_arr[ac++] = String.fromCharCode((c & 63) | 128);
            }
        }

        return tmp_arr.join('');
    }// }}}

    function cancelar() {
        var url = "<?php echo base_url('index.php/resolucion/datos_del_abogado') ?>";
        window.location = url;
    }

</script>



