<!-- Está vista permite visualizar  el requerimiento generado. Adjuntar el documento aprobado y firmado -->
<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
if (isset($message)) {
    echo $message;
}
if (isset($custom_error))
    echo $custom_error;
?> 

<div id="revision"></div>
<div id="gestion">
    <form name="myform" id="myform" method="post" onsubmit="return comprobarextension()" action="<?php echo base_url('requerimientoacercamiento/subir_requerimiento') ?>">
        <input type="hidden" name="cod_cobro" id="cod_cobro" value="<?php echo $post['cod_cobro'] ?>" />
        <input type="hidden" name="cod_proceso" id="cod_proceso" value="<?php echo $post['cod_proceso'] ?>">
        <input type="hidden" name="ruta" id="ruta" value="<?php echo $post['ruta'] ?>">
        <input type="hidden" name="nit_empresa" id="nit_empresa" value="<?php echo $cabecera['IDENTIFICACION'] ?>">
        <input type="hidden" name="nombre_archivo" id="nombre_archivo" value="">
        <div id="subir_archivo" name="subir_archivo" style=" display:none; width: 250px; overflow: hidden;   clear: both; margin: 20px 0;  width: 90%; margin-top:30px; text-align: left">
            <h4 class="text-left" style="color:#aaaaaa;">Subir Archivo</h4>
            <div id="file_source" style="width:500px;"></div>
            <div class="input-append" id="arch0" ></div><br>
            <div style="width:500px;" >
                <input type="file" name="archivo0" id="archivo0"  class='btn btn-success file_uploader' />
            </div><br>
            <input type="button" id="Aceptar" name="Aceptar" style="margin-left:60px;" onclick="return comprobarextension()" content="Aceptar" value="Aceptar" class="btn btn-success"/>
            <?php
            $data = array('name' => 'button', 'id' => 'Regresar', 'value' => 'Regresar', 'type' => 'button', 'content' => 'Regresar', 'class' => 'btn btn-success');
            echo form_button($data);
            ?>    

        </div>
        <?php echo form_close(); ?>
        <div id="contenido_tinymce">
            <form name="myform2" id="myform2" action="<?php echo base_url('requerimientoacercamiento/guardar_requerimiento_aprobado') ?>">
                <input type="hidden" name="cod_cobro"id="cod_cobro" value="<?php echo $post['cod_cobro'] ?>">
                <input type="hidden" name="cod_proceso"id="cod_proceso" value="<?php echo $post['cod_proceso'] ?>">
                <input type="hidden" name="ruta" id="ruta" value="<?php $post['ruta'] ?>">
                <input type="hidden" name="accion" id="accion">
                <input type="hidden" name="nit_empresa" id="nit_empresa" value="<?php echo $cabecera['IDENTIFICACION'] ?>">
                <input type="hidden" name="nombre" id="nombre" value="">
                <input type="hidden" name="button" id="button" value="" />
                <textarea name="informacion" id="informacion" style="width:auto; height: 300px" readonly="readonly" disabled="disabled" >
                    <?php echo $documento ?>
                </textarea>
        </div>
        <div id="div_botones" style="text-align:center; margin-top: 20px;display: block">               

            <button id="pdf"  class='btn btn-info' onclick="generar_pdf();" >PDF</button>
            <input type="button" name="continuar" id="continuar" value="Continuar" class='btn btn-success' onclick="f_continuar();">
            <button id="ver_observaciones" class='btn btn-info'>Comentarios </button>  
            <input id="cancelar" class="btn btn-warning" type="submit" value=" Cancelar" name="cancelar">

            <div id="Observaciones_anteriores"  style="display: none;" >
                <br><br>
                <table style="width:100%; float:justify; text-align:justify; height:300px;">
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
                            <div class="alert-success" style="overflow-y: scroll;border-color: black; border: 1px solid grey;padding: 15px 50px 0">
                                <?php echo $traza; ?> 
                            </div>
                            <br>
                        </td>
                    </tr>
                </table>
            </div> 

        </div>
        </p>
</div>
<form id="form" name="form" target = "_blank"  method="post" action="<?php echo base_url($url_generar_pdf) ?>">
    <textarea id="descripcion_pdf" name="descripcion_pdf" style="width: 100%;height: 300px; display:none"></textarea>  
    <input type="hidden" name="nombre_archivo" id="nombre_archivo">
</form>
<div id="ajax_load" class="ajax_load" style="display: none">
    <div class="preload" id="preload" ></div><img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
</div>
<div id="respuesta"></div>
<script>
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
            width: 900,
            height: 450,
            modal: true,
            title: "<?php echo "Requerimiento Acercamiento"; ?>",
            close: function() {
                $('#resultado *').remove();
            }
        });
        tinymce.init({
            language: 'es',
            selector: "textarea#informacion",
            theme: "modern",
            readonly:1,
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
        var nombre_archivo = "<?php echo date("d-m-Y H:i:s"); ?>";
        var nombre_archivo = nombre_archivo.replace(":", "_");
        var nombre_archivo = nombre_archivo.replace(":", "_");
        var nombre_archivo = nombre_archivo.replace(" ", "_");
        var nombre_archivo = "RequerimientoAcercamiento_Revisado_" + nombre_archivo;
        document.getElementById("nombre_archivo").value = nombre_archivo;
    });

    function generar_pdf()
    {

        var url = "<?php echo base_url('index.php/requerimientoacercamiento/pdf') ?>";
        var informacion = tinymce.get('informacion').getContent();
        if (informacion == '' || informacion == false)
        {
            mierror = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' + 'Debe ingresar la informaciÃ³n del Requerimiento de Acercamiento en el Texto Enriquecido' + '</div>';
            document.getElementById("respuesta").innerHTML = mierror;
            $("#respuesta").fadeOut(3000);
            return false;
        }
        document.getElementById("descripcion_pdf").value = informacion;
        $("#form").submit();
    }

    function f_continuar()
    {
        $("#contenido_tinymce").hide();
        $("#subir_archivo").show();
        $("#div_botones").hide();
    }

    function comprobarextension() {
        if ($("#archivo0").val() != "") {
            var archivo = $("#archivo0").val();
            var extensiones_permitidas = new Array(".gif", ".jpg", ".png", ".pdf", ".jpeg");
            var mierror = "";
            //recupero la extensión de este nombre de archivo
            var extension = (archivo.substring(archivo.lastIndexOf("."))).toLowerCase();
            //compruebo si la extensión está entre las permitidas
            var permitida = false;
            for (var i = 0; i < extensiones_permitidas.length; i++) {
                if (extensiones_permitidas[i] == extension) {
                    permitida = true;
                    break;
                }
            }
            if (!permitida) {
                jQuery("#archivo0").val("");
                mierror = '<div  class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' + 'Comprueba la extensión de los archivos a subir.\nSólo se pueden subir archivos con extensiones: ' + extensiones_permitidas.join() + '</div>';

                $("#respuesta").css('display', 'block');
                document.getElementById("respuesta").innerHTML = mierror;
                $("#respuesta");
                return false;
            }
            //si estoy aqui es que no se ha podido submitir
            else {

                     // Controla el tamaño del archivo a cargar          
                var file = $("#archivo0")[0].files[0];
                var fileSize = file.size;
                var resultado = 0;
                if(fileSize > 5242880) {
                    $('#archivo0').val('');
                    resultado = (fileSize/1024)/1024;
                    var resultado1 = Math.round(resultado*100)/100 ; 
                    mierror = "";
                    mierror = '<div  class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' + 'El archivo (' + archivo + ') a superado el limite de tamaño para adjuntos de 5Mb. Tamaño archivo de: ' + resultado1 + ' Mb </div>';

                    $("#respuesta").css('display', 'block');
                    document.getElementById("respuesta").innerHTML = mierror;
                    $("#respuesta");
                } else {
                    enviar();
                }
            }
        } else {
            alert('Debe seleccionar un documento');
            return false;
        }
    }
    $('#Regresar').click(function() {
        $("#contenido_tinymce").css("display", "block");
        $("#subir_archivo").css("display", "none");
        $("#div_botones").css("display", "block");

    });
    function enviar() {//sube documento
        tinyMCE.triggerSave();//asigna el contenido 
        var des = $("#informacion").val();
        jQuery(".preload, .load").show();
        if (des != '' || des != FALSE || des != NULL) {
            $("#ajax_load").show();
            var formData = new FormData($("#myform")[0]);
            $.ajax({
                url: '<?php echo $url; ?>',
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                //una vez finalizado correctamente
                success: function(data) {
                    alert("Se Subio el Documento Con Exito ");
                    $("#revision").html(data);
                    jQuery(".preload, .load").hide();
                    $("#subir_archivo").hide();
                    $("#div_documento_subido").hide();
                    $("#archivo0").val('');
                },
                //si ha ocurrido un error
                error: function() {
                    $("#ajax_load").hide();
                    alert("Ha Ocurrido un Error");
                }
            });
        }
        else
        {
            alert('Ingrese el contenido del documento');
        }
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

