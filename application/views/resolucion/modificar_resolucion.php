<?php
$name_file = RUTA_DES . $post['id'] . "/" . $post['id'] . ".txt";
$text_file = fopen($name_file, "r");
$contet_file = fread($text_file, filesize($name_file));
$html = base64_decode($contet_file);
?>

<textarea style="height: 225px" id="resuelve2" class="testo" name="resuelve2"><?php echo $html; ?></textarea><p>
<center>
    <button id="resuelve_enviar" align="center" class="btn btn-success"><i class="fa fa-floppy-o"></i> Enviar para aprobaci&oacute;n</button>
</center>
<p><hr><center>Correcciones</center><p>
<div id="resul" style="border:1px solid #ccc">
    <?php
    echo $comentario;
    ?>
</div>
<script>

    $(".preload, .load").hide();
    $('#resultado').dialog({
        autoOpen: true,
        width: 800,
        height: 460,
        modal: true,
        close: function() {
            $('#resultado *').remove();
        }
    });

    tinymce.init({
        selector: "textarea.testo",
        theme: "modern",
        plugins: [
            "advlist autolink lists link image charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen",
            "insertdatetime media nonbreaking save table contextmenu directionality",
            "emoticons template paste textcolor moxiemanager"
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

    $('#resuelve_enviar').click(function() {
        var archivo = tinymce.get('resuelve2').getContent();
        archivo = base64_encode(archivo);
        $(".preload, .load").show();
        var id = "<?php echo $post['id'] ?>";
        var nit = "<?php echo $post['nit'] ?>";
        var cod_fis = "<?php echo $post['cod_fis'] ?>";
        var url = "<?php echo base_url('index.php/resolucion/guardar_modificacion') ?>";
        $.post(url, {id: id, archivo: archivo,cod_fis:cod_fis,nit:nit})
                .done(function(msg) {
//                    $(".preload, .load").hide();
                    alert("Los datos fueron guardados con exito");
                    window.location.reload();
                })
                .fail(function(xhr) {
                    $(".preload, .load").hide();
                    alert("Los datos no fueron guardados");
                });
    });



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



</script>
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />