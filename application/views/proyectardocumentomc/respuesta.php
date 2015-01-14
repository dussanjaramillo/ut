
<div id="form1">
    <table width="100%" >
        <tr align="center">
            <td>TIENE DINERO EN CUENTAS BANCARIAS</td>
            <td>NO TIENE DINERO EN CUENTAS BANCARIAS</td>
        </tr>
        <tr align="center">
            <td><input type="radio" value="1" name="rta" atr="1" class="infor"></td>
            <td><input type="radio" value="2" name="rta" atr="2" class="infor"></td>
        </tr>
    </table>
</div>
<div id="form2" style="display: none">
    <div id="form3" >
        <table width="100%" >
            <thead style="background-color: #FC7323">
            <th>Nombre de los Archivos Agregados</th>
            <th>Acción</th>
            </thead>
            <tbody id="archivos">
                <?php echo $documentos; ?>
            </tbody>
        </table>
    </div>
    <form action="" method="post" id="uploadFile">
        <table>
            <tr>
                <td>
                    <input type="file" name="userFile" id="userFile" size="20" required="required"/>
                </td>
                <td>
                    <input type="submit" value="Carga" id="cargue" />
                </td>
            </tr>
        </table>
    </form>
    <div id="fin" align="center"><button id="guardar1" class="btn btn-success">Guardar Todo</button></div><br>
</div>
<input type="hidden" id="cantidad" name="cantidad" value="<?php echo $total[0]['TOTAL'] ?>">


<script>

    $('#uploadFile').submit(function(e) {
        e.preventDefault();
        var id = "<?php echo $post['id']; ?>";
        var name = $('#userFile').val();
        if (name == "") {
            return false;
        }
        jQuery(".preload, .load").show();
        var doUploadFileMethodURL = "<?= site_url('mcinvestigacion/doUploadFile'); ?>?id=" + id + "&name=" + name;
        $.ajaxFileUpload({
            url: doUploadFileMethodURL,
            secureuri: false,
            type: 'post',
            fileElementId: 'userFile',
            dataType: 'json',
            data: {id: id},
            success: function(data) {
                console.log(data.tabla);
                var html = base64_decode(data.tabla);
                console.log(html);
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

    function eliminar(dato, id) {
//        alert(dato)
        jQuery(".preload, .load").show();
        var url = "<?php echo base_url('index.php/mcinvestigacion/informacion_banco') ?>";
        $.post(url, {dato: dato, id: id})
                .done(function(msg) {
                    $('#archivos *').remove();
                    $('#archivos').html(msg);

                    jQuery(".preload, .load").hide();
                }).fail(function(msg) {
            alert('Documento no Eliminado');
            jQuery(".preload, .load").hide();
        });
    }


    $('#guardar1').click(function() {
        if ($('#cantidad').val() == "0" || $('#cantidad').val() == "") {
            alert('Datos Incompletos');
            return false;
        }
        var r = confirm("¿Desea Continuar sin Cargar mas Archivos?");
        if (r == true) {
            jQuery(".preload, .load").show();
            var url = "<?php echo base_url('index.php/mcinvestigacion/documentos_bancarios') ?>";
            var id = "<?php echo $post['id']; ?>";
            var cod_siguiente = "<?php echo $post['cod_siguiente']; ?>";
            var titulo = "<?php echo $post['titulo']; ?>";
            var nit = "<?php echo $post['nit']; ?>";
            var cod_fis = "<?php echo $post['cod_fis']; ?>";
            $.post(url, {id: id, cod_fis: cod_fis, nit: nit, titulo: titulo, cod_siguiente: cod_siguiente})
                    .done(function(msg) {
                        window.location.reload();
                    }).fail(function() {
                alert("<?php echo ERROR; ?>");
                jQuery(".preload, .load").hide();
            })
        }
    })


    jQuery(".preload, .load").hide();
    $('#resultado').dialog({
        autoOpen: true,
        width: 700,
        modal: true,
        title: "<?php echo $post['titulo'] ?>",
        close: function() {
            $('#resultado *').remove();
        }
    });
    $('.infor').click(function() {
        var a = $(this).attr('atr');
        if (a == 1) {
            $('#form2').show();
            $('#form1').hide();
            $('#resultado').dialog({
                width: 500,
                height: 350,
                title: "<?php echo $post['titulo'] ?>",
                close: function() {
                    $('#resultado *').remove();
                }
            });
        } else {
            var r = confirm('El cliente No posee dineros \n Desea continuar con bienes');
            if (r == true) {
                sin_bienes();
            }
        }
    });
    $('#form2').show();
    $('#form1').hide();
    function sin_bienes() {
        jQuery(".preload, .load").show();
        var url = "<?php echo base_url('index.php/mcinvestigacion/documentos_bancarios') ?>";
        var id = "<?php echo $post['id']; ?>";
        var cod_siguiente = "<?php echo OFICIO_BIENES; ?>";
        var titulo = "<?php echo $post['titulo']; ?>";
        var nit = "<?php echo $post['nit']; ?>";
        var cod_fis = "<?php echo $post['cod_fis']; ?>";
        $.post(url, {id: id, cod_fis: cod_fis, nit: nit, titulo: titulo, cod_siguiente: cod_siguiente})
                .done(function(msg) {
                    window.location.reload();
                }).fail(function() {
            alert("<?php echo ERROR; ?>");
            jQuery(".preload, .load").hide();
        })
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
</script>
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />