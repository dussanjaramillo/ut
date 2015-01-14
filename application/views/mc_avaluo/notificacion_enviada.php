<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
if (isset($message)) {
    echo $message;
}
//echo "<pre>";
//print_r($post);
//echo "</pre>";
if (isset($custom_error))
    echo $custom_error;
?>
<div id="boton_mensaje" style="display:none" ><button id="boton1"type="button" class="close" data-dismiss="alert">&times;</button></div>
<div id="respuesta"></div>
<div id="ajax_load" class="ajax_load" style="display: block">
    <div class="preload" id="preload" ></div><img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
</div>
<?php
$attributes = array("id" => "myform", 'class' => 'myform');
echo form_open_multipart("mc_avaluo/guarda_notificacion_enviada", $attributes);
?>

<input type="hidden" name="id" id="id" value="<?php echo $post['cod_proceso'] ?>">
<input type="hidden" id="cod_siguiente" name="cod_siguiente" value="<?php echo $post['cod_siguiente'] ?>">
<input type="hidden" id="tipo_gestion" name="tipo_gestion" value="<?php echo $post['tipo_gestion'] ?>">

<input type="hidden" id="respuesta" name="respuesta" value="<?php echo $post['respuesta'] ?>">
<input type="hidden" id="titulo" name="titulo" value="<?php echo $post['titulo'] ?>">
<input type="hidden" id="tipo_doc" name="tipo_doc" value="<?php echo $post['tipo_doc'] ?>">
<input type="hidden" id="nombre_archivo" name="nombre_archivo" value="">
<div id="datos">
</div>

<div id="datos" style="margin-top:20px;">
    <table id="tabla" style="width:auto;">
        <tr>
            <td class="sub" style="text-align:left;">Fecha:</td>
            <td class="sub" style="text-align:left;">
                <?php
                $data = array('name' => 'fecha', 'id' => 'fecha',
                    'required' => 'required',);
                echo form_input($data);
                ?>   
            </td>
        </tr>    
        <tr>   
            <td class="sub" style="text-align:left;">Número de radicado Onbase</td>
            <td style="text-align: left;" >
                <?php
                $data = array('name' => 'numero_radicado', 'id' => 'numero_radicado',
                    'required' => 'required', 'onkeypress' => "return soloNumeros(event)");
                echo form_input($data);
                ?> </td>
        </tr>
        <tr>   
            <td class="sub" style="text-align:left;">Fecha Documento Notificación</td>
            <td style="text-align: left;" >
                <?php
                $data = array('name' => 'fecha_radicado', 'id' => 'fecha_radicado',
                    'required' => 'required',);
                echo form_input($data);
                ?> </td>
        </tr>
        <tr>   
            <td class="sub" style="text-align:left;">No de Colilla</td>
            <td style="text-align: left;" >
                <?php
                $data = array('name' => 'num_colilla', 'onkeypress' => "return soloNumeros(event)", 'id' => 'num_colilla',
                    'required' => 'required',);
                echo form_input($data);
                ?> </td>
        </tr>
        <tr>   
            <td class="sub" style="text-align:left;">Subir Soporte</td>
        <table style=" padding:70px 50px 0px 15px; width: 100%">
            <tr><td><span>Número de Radicado</span></td><td><input type="text" name="numero_radicado" id="numero_radicado" class="requerid" onkeypress="return soloNumeros(event)" ></input></td></tr>
            <tr><td><span>Fecha de Radicado</span></td><td><input type="text"  name="fecha_radicado" id="fecha_radicado" class="requerid" ></input></td></tr>
            <tr id="adjunta_doc"><td><span>Adjuntar Documento</span></td>
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
                    <div style=" float:left; width: 100%;">                       
                        <input type="button" class="btn btn-success" id="sube_documento" name="sube_documento" value="Subir Documento" onclick="return comprobarextension(), fsubir_documento()">
                    </div>
                </td>
            </tr>
            <tr id="doc_adjunto" style="display:none"><td><span>Documento Adjunto</span></td>
                <td><div id="documento_subido" style=" float:left; "></div></td>
            </tr>
            <tr><td  colspan="2" style="text-align: center">  </td></tr>
        </table>
        <?php echo form_hidden('detalle', serialize($post)); ?>
        </tr>
        <tr>
            <td class="sub" style="text-align:left;">Observaciones</td>
            <td>
                <?php
                $datadesc = array(
                    'name' => 'descripcion_datos',
                    'id' => 'descripcion_datos',
                    'required' => 'required',
                    'rows' => '4',
                    'cols' => '100',
                    'style' => 'margin: 0px 0px 10px; width: 644px; height: 100px;'
                );
                echo form_textarea($datadesc);
                ?>
            </td>
        </tr>
        <tr><td></td>
            <td >
             <?php 
                $data = array(
                    'name' => 'button',
                    'id' => 'cancelar',
                    'value' => 'Cancelar',
                    'type' => 'button',
                    'style' => 'margin-left:60px;',
                    'content' => 'Cancelar',
                    'class' => 'btn btn-success',
                );
                echo form_button($data);
                $data = array(
                    'name' => 'button',
                    'id' => 'Aceptar',
                    'value' => 'Aceptar',
                    'type' => 'button',
                    'style' => 'margin-left:60px;',
                    'content' => 'Aceptar',
                    'class' => 'btn btn-success',
                    'onclick' => 'enviar()',
                );
                echo form_button($data);
                ?>
            </td>
        </tr>

    </table>
</div>
<br>
<div id="revision"></div>
<?php echo form_close(); ?>
<script>
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
                return true;
            }
        } else {
            mierror = '<div  class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Debe seleccionar un documento</div>';
            $("#mensaje_alerta").css('display', 'block');
            document.getElementById("mensaje_alerta").innerHTML = mierror;
            $("#mensaje_alerta").fadeOut(15000);
            return false;
        }

    }
function fsubir_documento()
    {
          $("#ajax_load").show();
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

            },
            //si ha ocurrido un error
            error: function() {
                $("#ajax_load").hide();
                alert("Ha Ocurrido un Error");
            }
        });
    }
    function enviar() {
        var formData = new FormData($("#myform")[0]);
        var cod_fiscalizacion = $("#cod_fisc").val();
        var fecha = $("#fecha").val();
        var num_radicado = $("#numero_radicado").val();
        var fecha_documento_not = $("#fecha_radicado").val();
        var imagen = $("#archivo0").val();
        if (!fecha)
        {
            mierror = '<div  class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' + 'Debe ingresar el campo fecha' + '</div>';
            document.getElementById("revision").innerHTML = mierror;
            return false;
        }
        else if (!num_radicado)
        {
            mierror = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' + 'Debe ingresar el número de radicado' + '</div>';
            document.getElementById("revision").innerHTML = mierror;
            return false;
        }
        else if (!fecha_documento_not)
        {
            mierror = '<div  class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' + 'Debe ingresar la fecha del radicado' + '</div>';
            document.getElementById("revision").innerHTML = mierror;
            return false;
        }
//        var gestion=194;
//        var mostrar='SI';
//        var parametros='';
//        var si='';
//        var no='';

     
        else {

            $("#ajax_load").show();
            $.ajax({
                url: '<?php echo base_url('index.php/mc_avaluo/guarda_notificacion_enviada') ?>',
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                //una vez finalizado correctamente
                success: function(data) {
                    //   alert("Se Subio el Documento Con Exito ");
                    $("#revision").html(data);
                    $("#ajax_load").hide();     
                       |

//                var url5 = "<?php //echo base_url("index.php/tiempos/bloqueo");          ?>";bodog
                    //     $("#resultado").load(url5, {codfiscalizacion: 0, gestion: 113, fecha: fecha_titulo, mostrar: 'SI', si: 'Agregar_Resultado_Demanda', no: 'Lista_Resultado_Demanda', parametros: 'cod_titulo:' + cod_titulo + '', BD: ''}, function() {
                    // });
                }

                ,
                //si ha ocurrido un error
                error: function(data) {
                    $('#resultado').dialog('close');
                    $('#resultado *').remove();
                    $("#ajax_load").hide();
                    alert("Ha Ocurrido un Error");
                    $("#revision").html(data);
                }
            });
        }
    }

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
    tinymce.init({
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


    $(document).ready(function() {
        $("#fecha").datepicker({
            dateFormat: "yy/mm/dd",
            changeMonth: true,
            maxDate: "0",
            changeYear: true,
        });
        $("#fecha_documento_not").datepicker({
            dateFormat: "yy/mm/dd",
            changeMonth: true,
            maxDate: "0",
            changeYear: true,
        });

    });

    function soloNumeros(e)
    {
        var keynum = window.event ? window.event.keyCode : e.which;
        if ((keynum == 8) || (keynum == 46))
            return true;

        return /\d/.test(String.fromCharCode(keynum));
    }
    function validar()
    {
        var fecha = $('#fecha').val();
        var num_radicado = $('#num_radicado').val();
        var motivo = $('#motivo').val();
        var num_colilla = $('#num_colilla').val();
        var imagen = $('#archivo0').val();
        var descripcion_datos = $('#descripcion_datos').val();
        if (fecha == '')
        {
            mierror = "Debe seleccionar una fecha";
            $("#mensaje").css('display', 'block');
            $("#boton_mensaje").css('display', 'none');
            document.getElementById("mensaje").innerHTML = document.getElementById("boton_mensaje").innerHTML + mierror;
            return false
        }
        else if (num_radicado == '')
        {
            mierror = "Debe ingresar el número de radicado";
            $("#mensaje").css('display', 'block');
            $("#boton_mensaje").css('display', 'none');
            document.getElementById("mensaje").innerHTML = document.getElementById("boton_mensaje").innerHTML + mierror;
            return false

        }
        else if (motivo == '')
        {

            mierror = "Debe ingresar motivo";
            $("#mensaje").css('display', 'block');
            $("#boton_mensaje").css('display', 'none');
            document.getElementById("mensaje").innerHTML = document.getElementById("boton_mensaje").innerHTML + mierror;
            return false

        }
        else if (num_colilla == '')
        {
            mierror = "Debe ingresar el numero de colilla";
            $("#mensaje").css('display', 'block');
            $("#boton_mensaje").css('display', 'none');
            document.getElementById("mensaje").innerHTML = document.getElementById("boton_mensaje").innerHTML + mierror;
            return false

        }
        else if (imagen == '')
        {
            mierror = "Debe seleccionar un archivo";
            $("#mensaje").css('display', 'block');
            $("#boton_mensaje").css('display', 'none');
            document.getElementById("mensaje").innerHTML = document.getElementById("boton_mensaje").innerHTML + mierror;
            return false

        } else
            return true;
    }
    $(document).ready(function() {
        $('#cancelar').click(function() {
            $('#resultado').dialog('close');
            $('#resultado *').remove();
        });
    });



</script>

<style>
    #titulo{
        font-family: Geneva, Arial, Helvetica, sans-serif; 
        font-size: 18px;
        background-color: #5BB75B;
        width:auto;
        text-align:center;
        color:#FFFFFF;
        height:30px;

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
    .tabla1
    {
        background-color: white;  
        text-align: left;
                                                                                                                                                                                                                                                                                                                                
    }
    .sub{
        font-weight: bold;
        margin-left:0px;
        font-size: 14px;
    }

</style>





