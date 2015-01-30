<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />

<div class="Gestion" style="background: #f0f0f0;  width: 95%; margin: auto; overflow: hidden">
    <table width="100%" border="0" id="tabla_inicial"  >
        <tr>
        <td class="td1"> <br>   N° Proceso Coactivo</td>
        <td class="color">    <br>                                       
            <?php echo $consulta[0]['COD_PROCESOPJ'] ?>   
        </td>
        </tr>
        <tr>
        <td class="td1"> <br>  N° de Medida Cautelar</td>
        <td>   <br>   
            <input type="text" readonly="readonly" name="cod_medidacautelar" id="cod_medidacautelar" value="<?php echo $consulta[0]['MEDIDA_CAUTELAR'] ?>" >                             
        </td>
        <td class="td1"> <br>   Fecha de Medida Cautelar</td>
        <td>   <br> 
            <input type="text" readonly="readonly" name="fecha_medidacautelar" id="fecha_medidacautelar" value="<?php echo $consulta[0]['FECHA_MEDIDAS'] ?>" >                             
        </td>
        </tr>
        <tr>
        <td class="td1"> <br>   Identificación Ejecutado</td>
        <td>             <br>  
            <input  type="text" id="nitempresa" name="nitempresa" readonly="readonly" value="<?php echo $consulta[0]['IDENTIFICACION'] ?>">

        </td>
        <td class="td1"> <br>Ejecutado</td>
        <td>             <br>  
            <input  type="text" id="nombre_empresa" name="nombre_empresa" readonly="readonly" value=" <?php echo $consulta[0]['EJECUTADO'] ?> ">   
        </td>
        </tr>
        <tr>
        <td class="td1"> <br>Teléfono</td>
        <td>             <br>  
            <input  type="text" id="nitempresa" name="nitempresa" readonly="readonly" value="<?php echo $consulta[0]['TELEFONO'] ?>">

        </td>
        <td class="td1"> <br>Dirección</td>
        <td>             <br>  
            <input  type="text" id="nombre_empresa" name="nombre_empresa" readonly="readonly" value=" <?php echo $consulta[0]['DIRECCION'] ?> ">   
        </td>
        </tr>
    </table>
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
                <font style=" padding: 15px 50px 0px 15px;  color:#238276; width:100%; text-align:center; ">Comentarios</font><br>
                <div style=" padding: 25px 50px 0px 25px;">
                    <textarea id="observaciones" name="obser"style="width: 90%;"></textarea>
                </div>
            </div>
        </td>
        </tr>
        <tr><td><div id="mensaje_alerta"></div></td></tr>
        <tr>
        <td align="center">
        <button id="pdf" onclick="genera_pdf()" class='btn btn-info'>PDF</button>
        <!--                <button id="enviar" class='btn btn-success'>Guardar</button>-->
        <input type="button" name="enviar" id="enviar" value="Guardar" class='btn btn-success'onclick="f_enviar()">
        <button id="ver_observaciones" class='btn btn-info'>Comentarios</button>  
        <button id="cancelar"  class="btn btn-warning"  onclick="f_cancelar()">Cancelar</button>  

        </td>
        </tr>
        <tr>
        <td>
            <div id="Observaciones_anteriores"  style="display: none;" >
                <br><br>
                <table style="width:100%; float:justify; text-align:justify; ">
                    <tr><td  style="background: #f0f0f0; text-align:center; padding: 15px 50px 15px 15px; " >
                        <font style="color:#238276;"> Comentarios</font>
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
            </div>
        </td>
        </tr>
    </table>
    <br>
    <form id="form" name="form" target = "_blank"  method="post" action="<?php echo base_url('index.php/mc_avaluo/pdf') ?>">
        <textarea id="descripcion_pdf" name="descripcion_pdf" style="width: 100%;height: 300px; display:none"></textarea>  
        <input type="hidden" name="nombre_archivo" id="nombre_archivo">
        <input type="hidden" name="tipo_documento" id="tipo_documento" value="3" >
        <input type="hidden" name="titulo_doc" id="titulo_doc" >
    </form>
    <script>
        function f_cancelar()
        {
            $("#ajax_load").css('display', 'block');
            $("#load").css('display', 'block');
            $("#preload").css('display', 'block');
            location.reload();
        }
        tinymce.init({
            language: 'es',
            selector: "textarea#informacion",
            theme: "modern",
            plugins: [
                "advlist autolink lists link  charmap print preview hr anchor pagebreak",
                "searchreplace wordcount visualblocks visualchars code fullscreen",
                //"insertdatetime media nonbreaking save table contextmenu directionality",
                //"emoticons template paste textcolor moxiemanager"
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
        $('#resultado').dialog({
            autoOpen: true,
            width: 900,
            height: 450,
            modal: true,
            title: "<?php echo $post['titulo']; ?>",
            close: function() {
                $('#resultado *').remove();
            }
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
        $(document).ready(function() {
            jQuery(".preload, .load").hide();
            var nit = "<?php echo $consulta[0]['IDENTIFICACION'] ?>";
            var nombre_archivo = "<?php echo date("d-m-Y H:i:s"); ?>";
            var nombre_archivo = nombre_archivo.replace(":", "_");
            var nombre_archivo = nombre_archivo.replace(":", "_");
            var nombre_archivo = nombre_archivo.replace(" ", "_");
            var nombre_archivo = nombre_archivo + "_" + nit;
            document.getElementById("nombre_archivo").value = nombre_archivo;

            //ver observaciones anteriores si existen
            $("#ocultar_observaciones").click(function() {
                $("#Observaciones_anteriores").hide();
            });
            $("#ver_observaciones").click(function() {
                $("#Observaciones_anteriores").show();
            });

            //agregar nuevas observaciones
            $("#agregar_observaciones").click(function() {
                $("#observaciones_nuevas").show();
            });
            $("#ocultar_obser_nueva").click(function() {
                $("#observaciones_nuevas").hide();
            });
        });
        function f_enviar() {
            var url = "<?php echo base_url('index.php/mc_avaluo/guardar_mc_avaluo') ?>";
            var nit = "<?php echo $consulta[0]['IDENTIFICACION'] ?>";
            var id = "<?php echo $consulta[0]['COD_PROCESO'] ?>";
            var tipo_gestion = "<?php echo $post['tipo_gestion'] ?>";
            var cod_siguiente = "<?php echo $post['cod_siguiente'] ?>";
            var titulo = "<?php echo $post['titulo'] ?>";
            var nombre_archivo = "<?php echo date("d-m-Y H:i:s"); ?>";
            var nombre_archivo = nombre_archivo.replace(":", "_");
            var nombre_archivo = nombre_archivo.replace(":", "_");
            var nombre_archivo = nombre_archivo.replace(" ", "_");
            var nombre_archivo = nombre_archivo + "_" + nit;
            var tipo_doc = "<?php echo $post['tipo_doc'] ?>";
            var respuesta = "<?php echo $post['respuesta'] ?>";
            var informacion = tinymce.get('informacion').getContent();
            var observaciones = $("#obser").val();
            var detalle = '<?php echo serialize($post) ?>';
            if (informacion == "") {
                $('#respuesta').css('display', 'block');
                mierror = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' + 'Debe ingresar la información del Documento' + '</div>';
                document.getElementById("mensaje_alerta").innerHTML = mierror;
                $("#respuesta").fadeOut(3000);
                return false;
            } else {
                jQuery(".preload, .load,.ajax_load").show();
                $.post(url, {detalle: detalle, id: id, informacion: informacion, nit: nit, tipo_gestion: tipo_gestion, cod_siguiente: cod_siguiente, nombre: nombre_archivo, tipo_doc: tipo_doc, respuesta: respuesta, titulo: titulo, observaciones: observaciones}, function(data) {
                    $("#mensaje").html(data);
                    jQuery(".preload, .load").hide();
//                    $('#resultado').dialog("close");
                    $('#resultado *').remove();
                    location.reload();
                })
            }
        }

        $("#infor").click(function() {
            $('#tabla_inicial').hide();
            $('#texto').show();
        });

        function genera_pdf() {
            var url = "<?php echo base_url('index.php/mc_avaluo/pdf') ?>";
            var informacion = tinymce.get('informacion').getContent();


            if (informacion == '')
            {
                mierror = 'Debe ingresar el contenido de la notificación';
                document.getElementById("respuesta").innerHTML = mierror;
                return false;
            }
            var nit = "<?php echo $consulta[0]['IDENTIFICACION'] ?>";
            var nombre_archivo = "<?php echo date("d-m-Y H:i:s"); ?>";
            var nombre_archivo = nombre_archivo.replace(":", "_");
            var nombre_archivo = nombre_archivo.replace(":", "_");
            var nombre_archivo = nombre_archivo.replace(" ", "_");
            var nombre_archivo = nombre_archivo + "_" + nit;
            document.getElementById("nombre_archivo").value = nombre_archivo;
            document.getElementById("descripcion_pdf").value = base64_encode(informacion);
            $("#form").submit();






        }
        //    


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
