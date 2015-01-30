<div id="ajax_load" class="ajax_load" style="display: none">
    <div class="preload" id="preload" ></div><img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
</div>

<?php
$attributes = array("id" => "myform", "onsubmit" => "return f_enviar()");
echo form_open_multipart("mc_avaluo/guardar_documento_adjunto", $attributes);
?>
<div id="texto" >
    <table width="100%">
        <tr>
            <td><!--Este es el contenido del documento-->
                <textarea id="informacion" style="width: 100%;height: 400px"><?php echo $documento; ?></textarea>
            </td>
        </tr>
        <tr><td><br><br>
                <table style=" padding:70px 50px 0px 15px;"><tr><td><span>Comentarios</span></td>
                        <td>
                            <textarea id="observaciones" style="width: 90%;"></textarea>   
                        </td></tr>
                    <tr><td>
                            <?php
                            echo form_label('Número Radicado<span class="required">*</span>', 'Número');
                            ?>
                        </td><td><input type="text" onpaste="return false"  name="numero_radicado" id="numero_radicado" class="requerid" onkeypress="return soloNumeros(event)" ></input></td></tr>
                    <tr><td><?php
                            echo form_label('Fecha Radicado <span class="required">*</span>', 'Fecha Radicado');
                            ?></td><td><input type="text" onpaste="return false" onkeypress="return prueba(event)"   name="fecha_radicado" id="fecha_radicado" class="requerid" ></input></td></tr>
                    <tr id="adjunta_doc"><td>
                            <?php
                            echo form_label('Adjuntar Documento<span class="required">*</span>', 'Adjuntar Documento');
                            ?>

                        </td>
                        <td>
                            <?php
                            $data = array(
                                'name' => 'userfile',
                                'id' => 'imagen',
                                'class' => 'btn btn-success'
                            );
                            echo form_upload($data);
                            ?>
                            <br><br>
                            <div style=" float:left; width: 100%;text-align: center;">                     
                                <input type="button" class="btn btn-success" id="sube_documento" name="sube_documento" value="Subir Documento" onclick="return comprobarextension()">
                            </div>
                        </td>
                    </tr>
                    <tr id="doc_adjunto" style="display:none"><td><span>Documento Adjunto</span></td>
                        <td><div id="documento_subido" style=" float:left; width: 100%;text-align: center;">
                                <input type="hidden" name="ruta" id="ruta" value="">
                            </div></td>
                    </tr>
                </table>
                <br><br>
                <div id="mensaje_alerta"  style="display:block"></div>           
            </td></tr>
        <tr><td>
        <center style=" padding: 15px 50px 0px 15px; ">
            <input type="button" name="pdf" id="pdf" value="Generar PDF" class="btn btn-info"> </input>
            <input type="submit" id="enviar" class='btn btn-success' value="Enviar" ></input>
            <input type="button" id="ver_observaciones" value="Comentarios" class='btn btn-info'>						
               <button id="cancelar"  class="btn btn-warning"  onclick="f_cancelar()">Cancelar</button>  
        </center>
        </td></tr>
        <tr>
            <td>
                <div id="Observaciones_anteriores"  style="display: none;" ><!--Este es el historial de observacioness-->
                    <br><br>
                    <table style="width:100%; float:justify; text-align:justify;">
                        <tr><td  style="background: #f0f0f0; text-align:center; padding: 15px 50px 15px 15px; " >
                                <font style="color:#238276;"> Comentarios</font>
                                <div id="close" style="width:auto;float:right; text-align:right;">
                                    <a href="#" id="ocultar_observaciones"><font  style="color:#238276;">cerrar</font></a> 
                                </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="background: #f0f0f0;"><br>
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
</div>   

<?php echo form_hidden('detalle', serialize($post)); ?>
<?php echo form_close(); ?>

<form id="form" name="form" target = "_blank"  method="post" action="<?php echo base_url('index.php/mc_avaluo/pdf') ?>">
    <textarea id="descripcion_pdf" name="descripcion_pdf" style="width: 100%;height: 300px; display:none"></textarea>  
    <input type="hidden" name="nombre_archivo" id="nombre_archivo">
</form>
<div id="resultado"></div>
<script>
    
    function f_cancelar()
        {
            $("#ajax_load").css('display', 'block');
            $("#load").css('display', 'block');
            $("#preload").css('display', 'block');
            location.reload();
        }
    function soloNumeros(e)
    {
       
        var keynum = window.event ? window.event.keyCode : e.which;
        if ((keynum == 8) || (keynum == 46))
            return true;

        return /\d/.test(String.fromCharCode(keynum));
    }

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
    jQuery(".preload, .load").hide();
    $('#resultado').dialog({
        autoOpen: true,
        width: 800,
        height: 450,
        modal: true,
        title: "<?php echo $post['titulo']; ?>",
        close: function() {
            $('#resultado *').remove();
        }
    });
//    
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
    function fsubir_documento()
    {
        $(".ajax_load,.preload,.load").css('display', 'block');
        var formData = new FormData($("#myform")[0]);
        $.ajax({
            url: '<?php echo $url_adjuntar_documento; ?>',
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            //una vez finalizado correctamente
            success: function(data) {
                alert("Se Subio el Documento Con Exito ");
                $("#doc_adjunto").show();
                $("#documento_subido").html(data);
                $("#adjunta_doc").hide();
                $(".ajax_load,.preload,.load").css('display', 'none');
            },
            //si ha ocurrido un error
            error: function() {
                $("#ajax_load").hide();
                alert("Ha Ocurrido un Error");
            }
        });
    }


    $('#pdf').click(function() {
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

    })
    $("#ocultar_observaciones").click(function() {
        $("#Observaciones_anteriores").hide();
    });
//   $('#adjuntar').click(function(){
//        $("#sube_documento").show();
//        $("#Observaciones_anteriores").hide();
//        
//    })

    $('#ver_observaciones').click(function() {
        $("#sube_documento").hide();
        $("#Observaciones_anteriores").show();
    })
    function comprobarextension() {
        if ($("#imagen").val() != "") {
            var archivo = $("#imagen").val();
            var extensiones_permitidas = new Array(".gif", ".jpg", ".png", ".pdf", ".jpeg");
            var mierror = "";
            //recupero la extensiÃ³n de este nombre de archivo
            var extension = (archivo.substring(archivo.lastIndexOf("."))).toLowerCase();
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
                mierror = '<div  class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' + 'Comprueba la extensión de los archivos a subir.\nSólo se pueden subir archivos con extensiones: ' + extensiones_permitidas.join() + '</div>';
                $("#mensaje_alerta").css('display', 'block');
                document.getElementById("mensaje_alerta").innerHTML = mierror;
                $("#mensaje_alerta").fadeOut(15000);
                return false;
            }
            //si estoy aqui es que no se ha podido submitir
            else {
                fsubir_documento();
            }
        } else {
            mierror = '<div  class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Debe seleccionar un documento</div>';
            $("#mensaje_alerta").css('display', 'block');
            document.getElementById("mensaje_alerta").innerHTML = mierror;
            $("#mensaje_alerta").fadeOut(15000);
            return false;
        }

    }
    function f_enviar() {
        var numero_radicado = $("#numero_radicado").val();
        var fecha_radicado = $("#fecha_radicado").val();
        var ruta_documento = $("#ruta").val();

        if (!numero_radicado)
        {
            mierror = '<div  class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>El campo Número Radicado es obligatorio</div>';
            $("#mensaje_alerta").css('display', 'block');
            document.getElementById("mensaje_alerta").innerHTML = mierror;
            $("#mensaje_alerta").fadeOut(15000);
            return false;
        }
        else if (!fecha_radicado)
        {
            mierror = '<div  class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>El campo Fecha Radicado es obligatorio</div>';
            $("#mensaje_alerta").css('display', 'block');
            document.getElementById("mensaje_alerta").innerHTML = mierror;
            $("#mensaje_alerta").fadeOut(15000);
            return false;
        }
        else if (!ruta_documento)
        {
            mierror = '<div  class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Debe Adjuntar el documento.</div>';
            $("#mensaje_alerta").css('display', 'block');
            document.getElementById("mensaje_alerta").innerHTML = mierror;
            $("#mensaje_alerta").fadeOut(15000);
            return false;
        }
        else {
            $(".ajax_load,.preload").show();
            return true;
        }
    }

    $("#fecha_radicado").datepicker({
        dateFormat: "yy/mm/dd",
        changeMonth: true,
        maxDate: "0",
        changeYear: true,
    });
    //No permite editar el campo fecha_radicado
    function prueba(e) {
        tecla = (document.all) ? e.keyCode : e.which;
        if (tecla == 8)
            return true; // backspace
        if (tecla == 32)
            return true; // espacio
        if (e.ctrlKey && tecla == 86) {
            return true;
        } //Ctrl v
        if (e.ctrlKey && tecla == 67) {
            return true;
        } //Ctrl c
        if (e.ctrlKey && tecla == 88) {
            return true;
        } //Ctrl x

        patron = /[a-zA-Z]/; //patron

        te = String.fromCharCode(tecla);
        return patron.test(te); // prueba de patron
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