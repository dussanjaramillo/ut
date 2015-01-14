<?php
$linea1 = "RESOLUCIÓN No:   ________________        DE   ___________________";
$linea2 = "POR LA CUAL SE FIJA LA CUOTA DE APRENDICES EL DIRECTOR DE LA REGIONAL ANTIOQUIA DEL SERVICIO NACIONAL DE APRENDIZAJE, SENA";
$linea3 = "En uso de sus facultades legales y en especial las otorgadas por el numeral 2° del artículo 4 de la Ley 119 de
        1994, numeral 9° del artículo 24 del Decreto 249 de 2004, y por el artículo 33 de la Ley 789 de 2002, el
        Decreto reglamentario 620 de 2005 y,";
$linea4 = "CONSIDERANDO:";
$linea5 = $informacion['considerando'];
$linea6 = "<b>RESUELVE:</b>";
$linea7 = $informacion['resuelve'];
$linea8 = "NOTIFIQUESE Y CUMPLASE";
$linea9 = "Dada en " . $informacion['ciudad'] . ", a los " . $informacion['fecha_actual'];
$linea10 = $informacion['director_regional'];
$linea11 = "Director/a Regional";
$linea12 = "Proyectó." . NOMBRE_COMPLETO;
$linea13 = "Revisó:" . $informacion['director_regional'];
$linea14 = "";
$linea15 = "";
$html = '
<div class="bs-docs-grid" >
    <div class="row show-grid" style="margin: 10px auto; text-align:center">
        <b>' . $linea1 . '
    </div>
    <div class="row show-grid" style="margin: 10px auto;">
        <div  style="margin: 10px auto;text-align:center">
' . $linea2 . '
            </b>
        </div>
        <div  style="margin: 10px auto;text-align:center">
' . $linea3 . '
        </div>
        <div  style="margin: 10px auto;text-align:center">
            <b>' . $linea4 . '</b>
        </div>
        <div  style="margin: 10px auto;text-align:justify">
            <p>
' . $linea5 . '
            <p>
        </div>
        <div  style="margin: 10px auto;text-align:center">
' . $linea6 . '
        </div>
        <div  style="margin: 10px auto;text-align:justify">
' . $linea7 . '
        </div>
    </div>
    <div  style="margin: 10px auto;text-align:center">
        <b>' . $linea8 . '</b>
    </div>
    <div  style="margin: 10px auto;text-align:justify">
' . $linea9 . '
    </div>
    <div  style="margin: 10px auto;text-align:center">
' . $linea10 . '<br>
        ' . $linea11 . '
    </div>
    <div  style="margin: 10px auto;text-align:left">
' . $linea12 . '<br>
        ' . $linea13 . '

    </div>
    ';
if (!empty($pdf)) {
//    Notificacionacta::pdf($html);
    Notificacionacta::pdf(base64_decode($informacion['proyecto']));
} else {
    ?>
    <div style="display: "><textarea id="informacion2" style="height: 700px" class="testo" name="informacion"><?php echo $html; ?></textarea></div>
    <?php
}
?>
<div class="row show-grid" style="margin: 10px auto;">
    <div class="span2" style="margin: 10px auto;text-align:center">
        <?php 
        $attributes = array('onsubmit' => 'return subir_archivo()', "id" => "myform");
        echo form_open("notificacionacta/resolucion_guardar", $attributes);
        ?>
        <input type="hidden" id="regional" name="regional" value="<?php echo $informacion['regional']; ?>">
        <input type="hidden" id="proyecto" name="proyecto" value=''>
        <input type="hidden" name="email_regional" id="email_regional" value="<?php echo $informacion['email_regional'] ?>">
        <input type="hidden" name="num_liquidacion" id="num_liquidacion" value="<?php echo $informacion['num_liquidacion'] ?>">
        <input type="hidden" id="nit" name="nit" value="<?php echo $informacion['nit']; ?>">
        <input type="hidden" id="rozon_social" name="rozon_social" value="<?php echo $informacion['rozon_social']; ?>">
        <input type="hidden" id="rozon_social" name="cod_fis" value="<?php echo $informacion['cod_fis']; ?>">
        <input type="hidden" id="vletras" name="vletras" value="<?php echo $informacion['vletras']; ?>">
        <input type="hidden" id="vresolucion" name="vresolucion" value="<?php echo $informacion['vresolucion']; ?>">
        <input type="hidden" id="num_proceso" name="num_proceso" value="<?php echo $informacion['num_proceso']; ?>">
        <input type="hidden" id="cuota" name="cuota" value="<?php echo $informacion['cuota']; ?>">
        <input type="hidden" id="ciudad" name="ciudad" value="<?php echo $informacion['ciudad']; ?>">
        <input type="hidden" id="fecha_actual" name="fecha_actual" value="<?php echo $informacion['fecha_actual']; ?>">
        <input type="hidden" id="nResolucionCuota" name="nResolucionCuota" value="<?php echo $informacion['nResolucionCuota']; ?>">
        <input type="hidden" id="fechaExpedicion" name="fechaExpedicion" value="<?php echo $informacion['fechaExpedicion']; ?>">
        <input type="hidden" id="fecha_ejecutoria" name="fecha_ejecutoria" value="<?php echo $informacion['fecha_ejecutoria']; ?>">
        <input type="hidden" id="considerando" name="considerando" value="<?php echo $informacion['considerando']; ?>">
        <input type="hidden" id="resuelve" name="resuelve" value="<?php echo $informacion['resuelve']; ?>">
        <input type="hidden" id="director_regional" name="director_regional" value="<?php echo $informacion['director_regional']; ?>">
        <input type="hidden" id="reviso" name="reviso" value="<?php echo $informacion['reviso']; ?>">
        <input type="hidden" id="elavora" name="elavora" value="<?php echo $informacion['elavora']; ?>">
        <input type="hidden" id="decision" name="decision" value="<?php echo $informacion['decision']; ?>">
        <input type="hidden" id="id_regional" name="id_regional" value="<?php echo $informacion['id_regional']; ?>" >
        <input type="hidden" id="id_director_regional" name="id_director_regional" value="<?php echo $informacion['id_director_regional']; ?>" >
        <?php
        $data = array(
            'name' => 'button',
            'id' => 'submit-button',
            'value' => 'Aprobar',
            'type' => 'submit',
            'onsubmit' => 'return subir_archivo()',
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
        <input type="hidden" id="proyecto2" name="proyecto" value=''>
        <input type="hidden" id="regional" name="regional" value="<?php echo $informacion['regional']; ?>">
        <input type="hidden" name="num_liquidacion" id="num_liquidacion" value="<?php echo $informacion['num_liquidacion'] ?>">
        <input type="hidden" id="nit" name="nit" value="<?php echo $informacion['nit']; ?>">
        <input type="hidden" id="rozon_social" name="rozon_social" value="<?php echo $informacion['rozon_social']; ?>">
        <input type="hidden" id="num_proceso" name="num_proceso" value="<?php echo $informacion['num_proceso']; ?>">
        <input type="hidden" id="rozon_social" name="cod_fis" value="<?php echo $informacion['cod_fis']; ?>">
        <input type="hidden" id="vletras" name="vletras" value="<?php echo $informacion['vletras']; ?>">
        <input type="hidden" id="vresolucion" name="vresolucion" value="<?php echo $informacion['vresolucion']; ?>">
        <input type="hidden" id="cuota" name="cuota" value="<?php echo $informacion['cuota']; ?>">
        <input type="hidden" id="ciudad" name="ciudad" value="<?php echo $informacion['ciudad']; ?>">
        <input type="hidden" id="fecha_actual" name="fecha_actual" value="<?php echo $informacion['fecha_actual']; ?>">
        <input type="hidden" id="nResolucionCuota" name="nResolucionCuota" value="<?php echo $informacion['nResolucionCuota']; ?>">
        <input type="hidden" id="fechaExpedicion" name="fechaExpedicion" value="<?php echo $informacion['fechaExpedicion']; ?>">
        <input type="hidden" id="fecha_ejecutoria" name="fecha_ejecutoria" value="<?php echo $informacion['fecha_ejecutoria']; ?>">
        <input type="hidden" id="considerando" name="considerando" value="<?php echo $informacion['considerando']; ?>">
        <input type="hidden" id="resuelve" name="resuelve" value="<?php echo $informacion['resuelve']; ?>">
        <input type="hidden" id="director_regional" name="director_regional" value="<?php echo $informacion['director_regional']; ?>">
        <input type="hidden" id="reviso" name="reviso" value="<?php echo $informacion['reviso']; ?>">
        <input type="hidden" id="elavora" name="elavora" value="<?php echo $informacion['elavora']; ?>">
        <input type="hidden" id="decision" name="decision" value="<?php echo $informacion['decision']; ?>">
        <input type="hidden" id="id_regional" name="id_regional" value="<?php echo $informacion['id_regional']; ?>" >
        <input type="hidden" id="id_director_regional" name="id_director_regional" value="<?php echo $informacion['id_director_regional']; ?>" >
        <?php
        $data = array(
            'name' => 'button',
            'id' => 'submit-button',
            'value' => 'pdf',
            'type' => 'submit',
            'onsubmit' => 'return subir_archivo()',
            'content' => '<i class="fa fa-file-pdf-o"></i> PDF',
            'class' => 'btn btn-info'
        );
        echo form_button($data);
        ?>
        <?php echo form_close(); ?>
    </div>
    <div class="span2" style="margin: 10px auto;text-align:center">
        <button onclick="cancelar()" class=" btn btn-warning"><li class="fa fa-minus-circle"></li> Cancelar</button>
    </div>
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
        var informacion = tinymce.get('informacion2').getContent();
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
    }
    function cancelar(){
        var url="<?php echo base_url('index.php/resolucion/datos_del_abogado') ?>";
        window.location=url;
    }


</script>
