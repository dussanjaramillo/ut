<div id="mensaje"></div>
<div id="mensaje"></div>
<div class="Gestion" style="background: #f0f0f0;  width: 95%; margin: auto; overflow: hidden">
</div>
<br><br>
<div id="texto" style="display: block">
    <table width="100%">
        <tr>
            <td>
                <textarea id="informacion" style="width: 100%;height: 400px"><?php echo $documento; ?></textarea>
            </td>
        </tr>
        <tr>
            <td>
                <div id="observa" style="border-radius: 15px;display: block;text-align:center; "><br>
                    <font style=" padding: 15px 50px 0px 15px;  color:#238276; width:100%; text-align:center; "> Comentarios</font><br>
                    <div style=" padding: 25px 50px 0px 25px;">
                        <textarea id="obser" style="width: 90%;"></textarea>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td align="center">
                <button id="pdf"  class='btn btn-info' onclick="generar_pdf();" >PDF</button>
                <input type="button" name="enviar" id="enviar" value="Guardar" class='btn btn-success' onclick="f_guardar('<?php echo $rta_siguiente ?>');">
                <button id="ver_observaciones" class='btn btn-info'> Comentarios </button>  
                <input id="cancelar" class="btn btn-warning" type="submit" value=" Cancelar" name="cancelar">
            </td>
        </tr>
        <tr>
            <td>
                <?php if ($post['cod_respuesta'] == REQUERIMIENTO_ACERCAMIENTO_DEVUELTO): ?>
                    <div id="Observaciones_anteriores"  style="display: none;" >
                        <br><br>
                        <table style="width:100%; float:justify; text-align:justify; ">
                            <tr><td  style="background: #f0f0f0; text-align:center; padding: 15px 50px 15px 15px; " >
                                    <font style="color:#238276;"> Observaciones Realizadas</font>
                                    <div id="close" style="width:auto;float:right; text-align:right;">
                                        <a href="#" id="ocultar_observaciones"><font  style="color:#238276;">cerrar</font></a> 
                                    </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><br>
                                    <div class="alert-success" style="border-color: black; border: 1px solid grey;padding: 15px 50px 0">
                                        <?php echo $traza; ?> 
                                    </div>
                                    <br>
                                </td>
                            </tr>
                        </table>
                    <?php endif; ?>
            </td>
        </tr>
    </table>
    <br>
    <?php echo form_hidden('detalle', serialize($post)); ?>
    <form id="form" name="form" target = "_blank"  method="post" action="<?php echo base_url('index.php/acercamientopersuasivo/pdf') ?>">
        <textarea id="descripcion_pdf" name="descripcion_pdf" style="width: 100%;height: 300px; display:none"></textarea>  
        <input type="hidden" name="nombre_archivo" id="nombre_archivo">
    </form>
    <div id="respuesta"></div>
    <script>
        //ver observaciones anteriores si existen
        $("#ocultar_observaciones").click(function() {
            $("#Observaciones_anteriores").hide();
        });
        $("#ver_observaciones").click(function() {
            $("#Observaciones_anteriores").show();
        });

        $(document).ready(function() {
            jQuery(".preload, .load").hide();
            $('#resultado').dialog({
                autoOpen: true,
                width: 800,
                height: 400,
                modal: true,
                title: "<?php echo "Requerimiento Acercamiento"; ?>",
                close: function() {
                    $('#resultado *').remove();
                    jQuery(".preload, .load").hide();
                }

            });
            tinymce.init({
                language: 'es',
                selector: "textarea#informacion",
                theme: "modern",
                plugins: [
                    "advlist autolink lists link  charmap print preview hr anchor pagebreak",
                    "searchreplace wordcount visualblocks visualchars code fullscreen ",
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
//
            var nit =<?php echo $cabecera['IDENTIFICACION'] ?>;
            var nombre_archivo = "<?php echo date("d-m-Y H:i:s"); ?>";
            var nombre_archivo = nombre_archivo.replace(":", "_");
            var nombre_archivo = nombre_archivo.replace(":", "_");
            var nombre_archivo = nombre_archivo.replace(" ", "_");
            var nombre_archivo = "RequerimientoAcercamiento_Creado_" + nombre_archivo + "_" + nit;
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
        function generar_pdf()
        {
            document.getElementById("nombre_archivo").value = nombre_archivo;
            var url = "<?php echo base_url('index.php/requerimientoacercamiento/pdf') ?>";
            var informacion = tinymce.get('informacion').getContent();
        
            if (informacion == '' || informacion == false)
            {
                mierror = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' + 'Debe ingresar la informaciÃ³n del Requerimiento de Acercamiento en el Texto Enriquecido' + '</div>';
                document.getElementById("respuesta").innerHTML = mierror;
                $("#respuesta").fadeOut(3000);
                return false;
            }
            document.getElementById("descripcion_pdf").value = base64_encode(informacion);
            $("#form").submit();
        }
//

        function f_guardar(rta_siguiente) {
           $("#ajax_load").show();
            var url = "<?= base_url() ?>index.php/acercamientopersuasivo/guardar";
            var nit =<?php echo $cabecera['IDENTIFICACION'] ?>;
            var cod_cobro = "<?php echo $post['cod_cobro']; ?>";
            var respuesta = "<?php echo $post['cod_respuesta']; ?>";
            var cod_proceso = "<?php echo $post['cod_proceso']; ?>";
            var descripcion = tinymce.get('informacion').getContent();
            var nombre = 'RequerimientoAcercamiento';
            var observaciones = $('#obser').val();
            $.ajax({
                type: "POST",
                url: url,
                data: {cod_proceso: cod_proceso, observaciones: observaciones, rta_siguiente: rta_siguiente, cod_cobro: cod_cobro, respuesta: respuesta, descripcion: descripcion, nit: nit, nombre_archivo: nombre},
                success: function(data)
                {   $("#ajax_load").hide();
                    $('#resultado').dialog('close');
                    $('#resultado *').remove();
                    $("#respuesta").html(data);

                    window.location.href = "<?php echo base_url('index.php/acercamientopersuasivo/abogado') ?>";
                }
                ,
                error: function(result) {
                     $("#ajax_load").hide();
                    alert("Datos incompletos");
                }
            });
        }
    </script>
    <style>
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
