<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<?php
if ($post['cod_siguiente_prelacion'] == 960 || $post['cod_siguiente_prelacion'] == 979 || $post['cod_siguiente_prelacion'] == 1044) {
    $div_a = "<div style='display:none'>";
    $div_c = "</div>";
} else {
    $div_a = "";
    $div_c = "";
}
?>
<form action="" method="post" id="uploadFile">
    <table style="width: 100%">
        <tr id="campos_radicados" style="display: none;" >
            <td><?php //echo $div_a;        ?>* Número Radicado<?php //echo $div_c;        ?></td>
            <td>Fecha Radicado</td>
        </tr>
        <tr id="datos_radicados"  style="display: none;">
            <td><?php //echo $div_a;        ?><input type="text" maxlength="12" onchange="numero(this)" id="radicado" name="radicado"><?php //echo $div_c;        ?></td>
            <td><input type="text" id='fecha_radicado' class="fecha" readonly="readonly" name="fecha_radicado"></td>
        </tr>
        <tr id="campos_resolucion"  style="display: none;" >
            <td><?php //echo $div_a;        ?>* Número Resolución<?php //echo $div_c;        ?></td>
            <td>Fecha Resolución</td>
        </tr>
        <tr id="datos_resolucion"  style="display: none;">
            <td><?php //echo $div_a;        ?><input type="text" maxlength="12" onchange="numero(this)" id="numero_resolucion" name="numero_resolucion"><?php //echo $div_c;        ?></td>
            <td><input type="text" id='fecha_resolucion' class="fecha" readonly="readonly" name="fecha_resolucion"></td>
        </tr>
        <tr>
            <td>Documento</td>
        </tr>
        <tr>
            <td><input type="file" name="userFile" id="userFile" size="20" required="required"/></td>
        </tr>
        <tr>
            <td colspan="2" align='center'>
                <input type="submit" value="Subir Archivos" id="cargue" class="btn btn-success" />
            </td>
        </tr>
    </table>
</form>

<table width="100%"  >
    <thead style="background-color: #FC7323;color: #FFF">
    <th>Nombre de los Archivos Agregados</th>
    <th>Nro. Radicado</th>
    <th>Fecha Radicado</th>
    <th>Acción</th>
</thead>
<tbody id="archivos">
    <?php echo $documentos; ?>
</tbody>
</table>

<?php
$attributes = array('onsubmit' => 'return comprobarextension2()', "id" => "myform");
echo form_open_multipart($post['ruta'], $attributes);
?>
<input type="hidden" name="id" id="id" value="<?php echo $post['id']; ?>">
<input type="hidden" name="cod_fis" id="cod_fis" value="<?php echo $post['cod_fis']; ?>">
<input type="hidden" name="nit" id="nit" value="<?php echo $post['nit']; ?>">
<input type="hidden" name="id_mc" id="id_mc" value="<?php echo $post['id_mc']; ?>">
<input type="hidden" name="cod_siguiente" id="cod_siguiente" value="<?php echo $post['cod_siguiente']; ?>">
<input type="hidden" name="tipo" id="tipo" value="<?php echo $post['tipo']; ?>">
<input type="hidden" value="<?php echo $post['cod_siguiente_prelacion']; ?>" id="cod_siguiente_prelacion" name="cod_siguiente_prelacion">
<input type="hidden" value="<?php echo $post['id_prelacion']; ?>" id="id_prelacion" name="id_prelacion">

<center><?php
    if ($post['cod_siguiente_prelacion'] == VIKY_AVALUO) {
        echo '<b>Esta Informacion Sera Enviada a Avaluo y Remate de Bienes</b>';
    }
    ?></center>
<table><tr>
        <!--<td>* Documento:</td>-->
        <td colspan="2">
        </td>
    </tr>

</table>

<input type="hidden" id="id_resolucion" name="id_resolucion" value="<?php echo $post['id'] ?>">
<center><button class="btn btn-success">Guardar Todo</button></center>
    <?php echo form_close(); ?>

<script>
$(document).ready(function() {
   jQuery(".preload, .load").hide();

    var cod_respuesta = '<?php echo $post['cod_siguiente'] ?>';
    switch (cod_respuesta) {
        case '279':
            $("#campos_resolucion").show();
            $("#datos_resolucion").show();
            $("#campos_radicados").hide();
            $("#datos_radicados").hide();
            break;
        default:
            $("#campos_resolucion").hide();
            $("#datos_resolucion").hide();
            $("#campos_radicados").show();
            $("#datos_radicados").show();
            break;

    }
});

    $('#radicado').validCampoFranz('0123456789');
    $('#uploadFile').submit(function(e) { 
        var cod_respuesta = '<?php echo $post['cod_siguiente'] ?>';
        
        switch (cod_respuesta) {
            case '279':
                if ($('#fecha_resolucion').val() == "") {
                    alert('Campo Fecha es Obligatorio');
                    return false;
                }
                if ($('#numero_resolucion').val() == "") {
                        $('#numero_resolucion').focus();
                        alert('Campo Obligatorio');
                        return false;
                    }
                break;
            default:
                if ($('#fecha_radicado').val() == "") {
                    alert('Campo Fecha es Obligatorio');
                    return false;
                }
                if ($('#cod_siguiente_prelacion').val() == 960 || $('#cod_siguiente_prelacion').val() == 979 || $('#cod_siguiente_prelacion').val() == 1044) {

                } else {
                    if ($('#radicado').val() == "") {
                        $('#radicado').focus();
                        alert('Campo Obligatorio');
                        return false;
                    }
                }
                break;

        }


        e.preventDefault();
        var id = "<?php echo $post['id']; ?>";
        var name = $('#userFile').val();
        if (name == "") {
            return false;
        }
        jQuery(".preload, .load").show();
        var radicado = $('#radicado').val();
        var fecha_radicado = $('#fecha_radicado').val();
        var doUploadFileMethodURL = "<?= site_url('mcinvestigacion/doUploadFile2'); ?>?id=" + <?php echo $post['id_mc']; ?> + "&id_mc=" + <?php echo $post['id_mc']; ?> + "&radicado=" + radicado + "&tipo=" +<?php echo $post['tipo']; ?> + '&titulo=' + "<?php echo $post['titulo']; ?>" + '&fecha_radicado=' + fecha_radicado;
        $.ajaxFileUpload({
            url: doUploadFileMethodURL,
            secureuri: false,
            type: 'post',
            fileElementId: 'userFile',
            dataType: 'json',
            data: {id: id},
            success: function(data) {
                var html = base64_decode(data.tabla);
                $('#form3').show();
                var cantidad = $('#cantidad').val();
                var resul = parseInt(cantidad) + parseInt(1);
                $('#cantidad').val(resul);
                $('#fin').show();
                $('#archivos *').remove();
                $('#archivos').append(html);
                $('#userFile').val('');
                jQuery(".preload, .load").hide();
            },
            error: function(data) {
                alert("El archivo no se ha podido cargar, el formato puede no ser valido");
                jQuery(".preload, .load").hide();
            }
        });

        return false;
        //                    return false;
    });
    function numero(dato) {
        if (isNaN(dato.value)) {
            $('#radicado').val('');
            alert('Campo Numerico');
            $('#radicado').focus();
        }
    }
    $('#resultado').dialog({
        autoOpen: true,
        width: 800,
        title: "<?php echo $post['titulo']; ?>",
        height: 400,
        modal: true,
        close: function() {
            $('#resultado *').remove();
        }
    });
    $(".preload, .load").hide();
    function eliminar(dato) {
        var id = '<?php echo $post['id_mc']; ?>';
        var tipo = '<?php echo $post['tipo']; ?>';
//        alert(dato)
        jQuery(".preload, .load").show();
        var url = "<?php echo base_url('index.php/mcinvestigacion/informacion_doc_mc') ?>";
        $.post(url, {dato: dato, id: id, tipo: tipo})
                .done(function(msg) {
                    $('#archivos *').remove();
                    $('#archivos').html(msg);

                    jQuery(".preload, .load").hide();
                }).fail(function(msg) {
            alert('Documento no Eliminado');
            jQuery(".preload, .load").hide();
        });
    }
    function comprobarextension2() {
        var r = confirm("¿Desea Continuar sin Cargar mas Archivos?");
        if (r == true) {
            return true;
        } else {
            return false;
        }
    }
    function comprobarextension() {
        if ($('#cod_siguiente_prelacion').val() == 960 || $('#cod_siguiente_prelacion').val() == 979 || $('#cod_siguiente_prelacion').val() == 1044) {

        } else {
            if ($('#radicado').val() == "" || isNaN($('#radicado').val())) {
                $('#radicado').focus();
                alert('Campo Numerico');
                return false;
            }
        }
        jQuery('#num_resolu').validationEngine('attach');

        if ($("#imagen").val() != "") {
            var archivo = $("#imagen").val();
            var extensiones_permitidas = new Array(".pdf");
            var mierror = "";
            //recupero la extensiÃ³n de este nombre de archivo
            var extension = (archivo.substring(archivo.lastIndexOf("."))).toLowerCase();
            //alert (extension);
            //compruebo si la extensiÃ³n estÃ¡ entre las permitidas
            var permitida = false;
            for (var i = 0; i < extensiones_permitidas.length; i++) {
                if (extensiones_permitidas[i] == extension) {
                    permitida = true;
                    break;
                }
            }
            if (!permitida) {
                jQuery("#imagen").val("");
                mierror = "Comprueba la extensi&oacute;n de los archivos a subir.\n S&oacute;lo se pueden subir archivos con extensiones: " + extensiones_permitidas.join();
            }
            //si estoy aqui es que no se ha podido submitir
            if (mierror != "") {
                alert(mierror);
                return false;
            }
            return true;
        } else {
            return false;
        }
    }
    function base64_decode(data) {
        // Decodes data encoded with MIME base64
        // 
        // +    discuss at: http://kevin.vanzonneveld.net/techblog/article/javascript_equivalent_for_phps_base64_decode/
        // +       version: 805.821
        // +   original by: Tyler Akins (http://rumkin.com)
        // +   improved by: Thunder.m
        // +      input by: Aman Gupta
        // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)    
        // -    depends on: utf8_decode
        // *     example 1: base64_decode('S2V2aW4gdmFuIFpvbm5ldmVsZA==');
        // *     returns 1: 'Kevin van Zonneveld'

        // mozilla has this native 
        // - but breaks in 2.0.0.12!
        //if (typeof window['btoa'] == 'function') {
        //    return btoa(data);
        //}

        var b64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
        var o1, o2, o3, h1, h2, h3, h4, bits, i = ac = 0, dec = "", tmp_arr = [];

        do {  // unpack four hexets into three octets using index points in b64
            h1 = b64.indexOf(data.charAt(i++));
            h2 = b64.indexOf(data.charAt(i++));
            h3 = b64.indexOf(data.charAt(i++));
            h4 = b64.indexOf(data.charAt(i++));

            bits = h1 << 18 | h2 << 12 | h3 << 6 | h4;

            o1 = bits >> 16 & 0xff;
            o2 = bits >> 8 & 0xff;
            o3 = bits & 0xff;

            if (h3 == 64) {
                tmp_arr[ac++] = String.fromCharCode(o1);
            } else if (h4 == 64) {
                tmp_arr[ac++] = String.fromCharCode(o1, o2);
            } else {
                tmp_arr[ac++] = String.fromCharCode(o1, o2, o3);
            }
        } while (i < data.length);

        dec = tmp_arr.join('');
        dec = utf8_decode(dec);

        return dec;
    }
    function utf8_decode(str_data) {
        // Converts a string with ISO-8859-1 characters encoded with UTF-8   to single-byte
        // ISO-8859-1
        // 
        // +    discuss at: http://kevin.vanzonneveld.net/techblog/article/javascript_equivalent_for_phps_utf8_decode/
        // +       version: 805.821
        // +   original by: Webtoolkit.info (http://www.webtoolkit.info/)
        // +      input by: Aman Gupta
        // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
        // *     example 1: utf8_decode('Kevin van Zonneveld');
        // *     returns 1: 'Kevin van Zonneveld'

        var tmp_arr = [], i = ac = c = c1 = c2 = 0;

        while (i < str_data.length) {
            c = str_data.charCodeAt(i);
            if (c < 128) {
                tmp_arr[ac++] = String.fromCharCode(c);
                i++;
            } else if ((c > 191) && (c < 224)) {
                c2 = str_data.charCodeAt(i + 1);
                tmp_arr[ac++] = String.fromCharCode(((c & 31) << 6) | (c2 & 63));
                i += 2;
            } else {
                c2 = str_data.charCodeAt(i + 1);
                c3 = str_data.charCodeAt(i + 2);
                tmp_arr[ac++] = String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
                i += 3;
            }
        }

        return tmp_arr.join('');
    }
    $('.fecha').datepicker({});
</script>