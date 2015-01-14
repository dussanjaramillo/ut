<div id="texto" >
    <table width="100%">
        <tr>
            <td>
                <textarea id="informacion" style="width: 100%;height: 400px"><?php echo $documento; ?></textarea>
            </td>
        </tr>
        <tr>
            <td>
                <div id="observa" style="border-radius: 15px;display: block;text-align:center; "><br>
                    <font style=" padding: 15px 50px 0px 15px;  color:#238276; width:100%; text-align:center; ">  Comentarios</font><br>
                    <div style=" padding: 25px 50px 0px 25px;">
                        <textarea id="observaciones" style="width: 90%;"></textarea>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td align="center">
                <table><tr>
                        <td><button id="pdf" onclick="generar_pdf()" class='btn btn-info'> PDF</button></td>
                        <td><button id="enviar" class='btn btn-success' onclick="f_enviar(<?php echo $cod_aprobacion; ?>)">Aprobar</button></td>
                        <td><button id="atras" class='btn btn-success' onclick="f_enviar(<?php echo $cod_devolucion; ?>)">Devolver</button></td>
                        <td><button id="ver_observaciones" class='btn btn-info'>Comentarios</button></td>
                        <td>  
                            <input id="cancelar" class="btn btn-warning" type="submit" value=" Cancelar" name="cancelar">
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <div id="Observaciones_anteriores"  style="display: none;" >
                    <br><br>
                    <table style="width:100%; float:justify; text-align:justify; height:300px;">
                        <tr><td  style="background: #f0f0f0; text-align:center; padding: 15px 50px 15px 15px; " >
                                <font style="color:#238276;">  Comentarios</font>
                                <div id="close" style="width:auto;float:right; text-align:right;">
                                    <a href="#" id="ocultar_observaciones"><font  style="color:#238276;">cerrar</font></a> 
                                </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="background: #f0f0f0;"><br>
                                <div class="alert-success" style="overflow-y: scroll;border-color: black; border: 1px solid grey;padding: 15px 50px 0">
                                    <?php echo $traza; ?> 
                                </div>
                                <br>
                            </td>
                        </tr>
                    </table>
                </div> 
            </td>
        </tr>
    </table>
</div> 
<div id="respuesta"></div>
<?php echo form_hidden('detalle', serialize($post)); ?>
<form id="form" name="form" target = "_blank"  method="post" action="<?php echo base_url('index.php/acercamientopersuasivo/pdf') ?>">
    <textarea id="descripcion_pdf" name="descripcion_pdf" style="width: 100%;height: 300px; display:none"></textarea>  
    <input type="hidden" name="nombre_archivo" id="nombre_archivo">
</form>
<script>
    tinymce.init({
        language: 'es',
        selector: "textarea#informacion",
        theme: "modern",
        plugins: [
            "advlist autolink lists link image charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen",
                    //   "insertdatetime media nonbreaking save table contextmenu directionality",
                    // "emoticons template paste textcolor moxiemanager"
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
//   
    jQuery(".preload, .load").hide();
    $('#resultado').dialog({
        autoOpen: true,
        width: '800',
        height: '400',
        modal: true,
        title: "<?php echo "Requerimiento Acercamiento" ?>",
        close: function() {
            $('#resultado *').remove();
            jQuery(".preload, .load").hide();
        }
    });
    $("#ocultar_observaciones").click(function() {
        $("#Observaciones_anteriores").hide();
    });

    $("#ver_observaciones").click(function() {
        $("#Observaciones_anteriores").show();
    });
    function base64_encode(data)
        {
            var b64 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';
            var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
                    ac = 0,
                    enc = '',
                    tmp_arr = [];
            if (!data)
            {
                return data;
            }
            do
            {
                o1 = data.charCodeAt(i++);
                o2 = data.charCodeAt(i++);
                o3 = data.charCodeAt(i++);
                bits = o1 << 16 | o2 << 8 | o3;
                h1 = bits >> 18 & 0x3f;
                h2 = bits >> 12 & 0x3f;
                h3 = bits >> 6 & 0x3f;
                h4 = bits & 0x3f;
                tmp_arr[ac++] = b64.charAt(h1) + b64.charAt(h2) + b64.charAt(h3) + b64.charAt(h4);
            }
            while (i < data.length);
            enc = tmp_arr.join('');
            var r = data.length % 3;
            return (r ? enc.slice(0, r - 3) : enc) + '==='.slice(r || 3);
        }
    /**Función que genera el pdf del documento **/
    function generar_pdf() {

        var informacion = tinymce.get('informacion').getContent();
        if (informacion == '')
        {
            mierror = 'Debe ingresar el contenido del documento';
            document.getElementById("respuesta").innerHTML = mierror;
            return false;
        }
        var nit = "";
        var nombre_archivo = "<?php echo date("d-m-Y H:i:s"); ?>";
        var nombre_archivo = nombre_archivo.replace(":", "_");
        var nombre_archivo = nombre_archivo.replace(":", "_");
        var nombre_archivo = nombre_archivo.replace(" ", "_");
        var nombre_archivo = nombre_archivo + "_" + nit;
        document.getElementById("nombre_archivo").value = nombre_archivo;
        document.getElementById("descripcion_pdf").value = base64_encode(informacion);
        $("#form").submit();

    }

    function f_enviar(rta_siguiente)//cod_siguiente es el tipo de respuesta al que va a cambiar el registro que puede ser aprobado o devuelto
    {
//alert('Hola');
        var nit =<?php echo $cabecera['IDENTIFICACION'] ?>;
        var nombre_archivo = "<?php echo date("d-m-Y H:i:s"); ?>";
        var nombre_archivo = nombre_archivo.replace(":", "_");
        var nombre_archivo = nombre_archivo.replace(":", "_");
        var nombre_archivo = nombre_archivo.replace(" ", "_");
        var nombre_archivo = "RequerimientoAcercamiento_Creado_" + nombre_archivo + "_" + nit;
        var observaciones = $('#observaciones').val();
        var informacion = tinymce.get('informacion').getContent();
        if (informacion == "") {
            $('#respuesta').css('display', 'block');
            mierror = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' + 'Debe ingresar la información del Documento' + '</div>';
            document.getElementById("respuesta").innerHTML = mierror;
            $("#respuesta").fadeOut(3000);
        }
        else {
            var url = "<?= base_url() ?>index.php/acercamientopersuasivo/guardar";
            var nit = "<?php echo $cabecera['IDENTIFICACION'] ?>";
            var cod_cobro = "<?php echo $post['cod_cobro']; ?>";
            var respuesta = "<?php echo $post['cod_respuesta']; ?>";
            var descripcion = tinymce.get('informacion').getContent();
            var nombre = 'RequerimientoAcercamiento';
            var observaciones = $('#observaciones').val();
            var cod_proceso = "<?php echo $post['cod_proceso']; ?>";
            $.ajax({
                type: "POST",
                url: url,
                data: {cod_proceso: cod_proceso, observaciones: observaciones, rta_siguiente: rta_siguiente, cod_cobro: cod_cobro, respuesta: respuesta, descripcion: descripcion, nit: nit, nombre_archivo: nombre},
                success: function(data)
                {

                    //$("#respuesta").html(data);
                    $('#resultado').dialog('close');
                    $('#resultado *').remove();
                    location.reload();
                    


                }
                ,
                error: function(result) {
                    alert("Datos incompletos");

                }
            });
        }

    }
</script>
<style>
    .color{
        color: #FC7323;
        font:bold 12px;
    }
    #tabla_inicial{
        border-radius: 50px;
        border: 1px solid #CCC;
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
