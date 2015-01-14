<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<div id="form1">
    <table align="center" width="100%">
        <tr align="center">
            <td>Embargo Previo</td>
            <td>Sin Embargo Previo</td>
        </tr>
        <tr align="center">
            <td><input type="radio" name="acct" class="actuar" value="1"></td>
            <td><input type="radio" name="acct" class="actuar" value="2"></td>
        </tr>
    </table>
</div>
<div id="form12" style="display: none">
    <form action="" method="post" id="uploadFile">
        <table align="center">
            <tr>
                <td rowspan="2">
                    Efectiva ?
                </td>
                <td>Si&nbsp;&nbsp;
                    <input type="radio" name="efec"  class="actuar2" value="2" checked="true">
                    <input type="hidden" name="radios" id="radios"  value="2">
                </td>
            </tr>
            <tr>
                <td>No&nbsp;&nbsp;<input type="radio" name="efec"  class="actuar2" value="1"></td>
            </tr>
            <tr>
                <td>Nro. Radicado</td>
                <td><input type="text" name="radicado" id="radicado" class=""></td>
            </tr>
            <tr>
                <td>Fecha Radicado</td>
                <td><input type="text" id='fecha_radicado' class="fecha" readonly="readonly" name="fecha_radicado"></td>
            </tr>
            <tr>
                <td>Documento</td>
            </tr>
            <tr>
                <td colspan="4"><input type="file" name="userFile" id="userFile" size="20" required="required"/></td>
            </tr>
            <tr>
                <td colspan="4" align='center'>
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
    <center><button class="btn btn-success" id="guardar">Guardar Todo</button></center>
</div>


<script>
    $('.fecha').datepicker({});
    $('#radicado').validCampoFranz('0123456789');
    cod_fis = "<?php echo $post['cod_fis']; ?>";
    id = "<?php echo $post['id']; ?>";
    id_prelacion = "<?php echo $post['id_prelacion']; ?>";
    nit = "<?php echo $post['nit']; ?>";
    titulo = "<?php echo $post['titulo']; ?>";
    cod_siguiente_prelacion = "<?php echo $post['cod_siguiente_prelacion']; ?>";

    $('.actuar2').click(function() {
        $('#radios').val(this.value);
    });

    $('#guardar').click(function() {
        var radio = $('#radios').val();

        var r = confirm("¿Desea Continuar sin Cargar mas Archivos?");
        if (r == true) {
            if (radio == 1) {
                var r = confirm('El proceso de medidas cautelares "vehiculos" se va a reiniciar');
                if (r == true) {
                    var url = "<?php echo base_url('index.php/mcinvestigacion/bloquear_vehiculos') ?>";
                    $.post(url, {id_prelacion: id_prelacion, cod_fis: cod_fis})
                            .done(function() {
                                alert('Los Datos Fueron Guarda');
                                window.location.reload();
                            })
                }
            } else if (radio == 2) {
                enviar('<?php echo VEHICULO_EMBARGO_INICIO; ?>');
            }
        } else {
            return false;
        }
    });

    jQuery(".preload, .load").hide();
    $('#resul').dialog({
        autoOpen: true,
        width: 300,
        modal: true,
        title: "<?php echo $post['titulo']; ?>",
        close: function() {
            $('#resul *').remove();
        }
    });
    $('.actuar').click(function() {
        if (this.value == 1) {
            if (cod_siguiente_prelacion ==<?php echo INMUEBLE_EMBARGO; ?>) {
                enviar('<?php echo INMUEBLES_EMBARGO_INICIO; ?>');
            }
            if (cod_siguiente_prelacion ==<?php echo VEHICULO_EMBARGO2; ?>) {
                enviar('<?php echo VEHICULO_EMBARGO_OFICIO_INICIO; ?>');
            }
        } else if (this.value == 2) {
            if (cod_siguiente_prelacion ==<?php echo INMUEBLE_EMBARGO; ?>) {
                enviar('<?php echo INMUEBLES_SECUESTRO_INICIO; ?>');
            } else {
                $('#form12').show();
                $('#form1').hide();

//            if(cod_siguiente_prelacion==<?php echo VEHICULO_EMBARGO2; ?>){
//            enviar('<?php echo VEHICULO_EMBARGO_INICIO; ?>');
//            }
            }
        }
    })
    function enviar(dato) {
        jQuery(".preload, .load").show();
        var url = "<?php echo base_url('index.php/mcinvestigacion/avance') ?>";
        $.post(url, {cod_fis: cod_fis, id: id, id_prelacion: id_prelacion, nit: nit, titulo: titulo, dato: dato})
                .done(function() {
                    window.location.reload();
                }).fail(function() {
            jQuery(".preload, .load").hide();
            alert("<?php echo ERROR; ?>");
        })
    }
    $('#uploadFile').submit(function(e) {

        if ($('#fecha_radicado').val() == "") {
            alert('Campo Fecha es Obligatorio');
            return false;
        }
        if ($('#radicado').val() == "") {
            $('#radicado').focus();
            alert('Campo Obligatorio');
            return false;
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
        var doUploadFileMethodURL = "<?= site_url('mcinvestigacion/doUploadFile2'); ?>?id=" + <?php echo $post['id_mc']; ?> + "&id_mc=" + <?php echo $post['id_mc']; ?> + "&radicado=" + radicado + "&tipo=" +<?php echo VEHICULO_RECEPCION_DOCUMENTO ?> + '&titulo=' + "<?php echo $post['titulo']; ?>" + '&fecha_radicado=' + fecha_radicado;
        $.ajaxFileUpload({
            url: doUploadFileMethodURL,
            secureuri: false,
            type: 'post',
            fileElementId: 'userFile',
            dataType: 'json',
            data: {id: id},
            success: function(data) {
                var html = base64_decode(data.tabla);
////                $('#form3').show();
////                var cantidad = $('#cantidad').val();
//                var resul = parseInt(cantidad) + parseInt(1);
////                $('#cantidad').val(resul);
////                $('#fin').show();
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
    function eliminar(dato) {
        var id = '<?php echo $post['id_mc']; ?>';
        var tipo = '<?php echo VEHICULO_RECEPCION_DOCUMENTO; ?>';
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
</script>
<style>
    .ui-widget-overlay{z-index: 10000;}
    .ui-dialog{
        z-index: 15000;
    }
    div.preload{
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        background-color: white;
        opacity: 0.8;
        z-index: 10000;
    }

    div img.load{
        position: absolute;
        left: 50%;
        top: 50%;
        margin-left: -64px;
        margin-top: -64px;
        z-index: 15000;
    }
</style>
