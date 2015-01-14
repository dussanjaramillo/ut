<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
if (isset($message)) {
    echo $message;
}

if (isset($custom_error))
    echo $custom_error;
?>

<div class="center-form-large" style="width: auto; text-align: center;">
    <h4>Medidas Cautelares Avaluo</h4>
    <div id="ajax_load" class="ajax_load" style="display: none">
        <div class="preload" id="preload" ></div><img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
    </div>



    <div id="texto" style="display: block">
        
        <table id="tabla1" style="width:auto; ">
 <tr>
            <td class="td1"> <br>   N° Proceso Coactivo</td>
            <td class="color">    <br>                                       
                <?php echo $consulta[0]['CODIGO_PJ'] ?>   
            </td>

        </tr>
        <tr>
            <td class="td1"> <br>  N° de Medida Cautelar</td>
            <td>   <br>   
                <input type="text" readonly="readonly" name="cod_medidacautelar" id="cod_medidacautelar" value="<?php echo $consulta[0]['COD_MEDIDACAUTELAR'] ?>" >                             
            </td>
            <td class="td1"> <br>   Fecha de Medida Cautelar</td>
            <td>   <br> 
                <input type="text" readonly="readonly" name="fecha_medidacautelar" id="fecha_medidacautelar" value="<?php echo $consulta[0]['FECHA_MEDIDAS'] ?>" >                             
            </td>
        </tr>
        <tr>
            <td class="td1"> <br>   Identificacion Deudor</td>
            <td>             <br>  
                <input  type="text" id="nitempresa" name="nitempresa" readonly="readonly" value="<?php echo $consulta[0]['NIT_EMPRESA'] ?>">

            </td>
            <td class="td1"> <br>Ejecutado</td>
            <td>             <br>  
                <input  type="text" id="nombre_empresa" name="nombre_empresa" readonly="readonly" value=" <?php echo $consulta[0]['NOMBRE_EMPRESA'] ?> ">   
            </td>
        </tr>
        <tr>
            <td class="td1"> <br>Teléfono</td>
            <td>             <br>  
                <input  type="text" id="nitempresa" name="nitempresa" readonly="readonly" value="<?php echo $consulta[0]['TELEFONO_FIJO'] ?>">

            </td>
            <td class="td1"> <br>Dirección</td>
            <td>             <br>  
                <input  type="text" id="nombre_empresa" name="nombre_empresa" readonly="readonly" value=" <?php echo $consulta[0]['DIRECCION'] ?> ">   
            </td>
        </tr>

        </table>
     
        <form name="myform" id="myform" method="post" action="<?php echo base_url('index.php/mc_avaluo/guarda_notificacion_correo'); ?>" >
        <table width="100%">
            <tr><td colspan="2"><h4>Recibir Avaluo y Generar Notificación</h4></td></tr>
             <tr>
                <td colspan="2"><br>
                    <textarea  name="informacion" id="informacion" style="width:70%;height: 400px"></textarea>
                </td>
            </tr>

        </table><br>
            <table style=" padding:70px 50px 0px 15px;"><tr><td><span>Agregar Observación</span></td>
                    <td>
                      <textarea id="observaciones" style="width: 90%;"></textarea>   
                    </td></tr>
    
                <tr><td><span>Número de Radicado</span></td><td><input type="text" name="numero_radicado" id="numero_radicado" class="requerid" onkeypress="return soloNumeros(event)" ></input></td></tr>
                <tr><td><span>Fecha de Radicado</span></td><td><input type="text"  readonly="readony" name="fecha_radicado" id="fecha_radicado" class="requerid" ></input></td></tr>
            <tr><td><span>Adjuntar Documento</span></td>
                <td>
                            <?php
                            $data = array(
                                'name' => 'userfile',
                                'id' => 'imagen',
                                'class' => 'btn btn-success'
                            );
                            echo form_upload($data);
                            ?>
                            </td>
                        </tr>
                  </table>
                <br><br>
                <div id="mensaje_alerta"  style="display:block"></div>           
            </td></tr>
                <tr><td>
                <center style=" padding: 15px 50px 0px 15px; ">
                    <input type="button" name="pdf" id="pdf" value="Generar PDF" class="btn btn-success"> </input>
                    <button id="enviar" class='btn btn-success' >Enviar</button>
                    <input type="button" id="ver_observaciones" value="Observaciones Anteriores" class='btn btn-success'>
                    </center>
            </td></tr>
        <tr>
            <td>
                <div id="Observaciones_anteriores"  style="display: none;" ><!--Este es el historial de observacioness-->
                    <br><br>
                    <table style="width:100%; float:justify; text-align:justify;">
                        <tr><td  style="background: #f0f0f0; text-align:center; padding: 15px 50px 15px 15px; " >
                                <font style="color:#238276;"> Observaciones Realizadas</font>
                                <div id="close" style="width:auto;float:right; text-align:right;">
                                    <a href="#" id="ocultar_observaciones"><font  style="color:#238276;">cerrar</font></a> 
                                </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><div class="alert-success" style="border-color: black; border: 1px solid grey;padding: 15px 50px 0">
                                <?php echo $traza; ?> 
                                </div>
                            </td>
                        </tr>
                    </table>
                </div> 
            </td>
        </tr>
    </table>
        <div id="datos">
            <input type="hidden" name="id" id="id" value="<?php echo $consulta[0]['COD_AVALUO'] ?>">
            <input type="hidden" name="nit" id="nit" value="<?php echo $consulta[0]['NIT_EMPRESA'] ?>">
            <input type="hidden" name="tipo_gestion" id="tipo_gestion" value="<?php echo $post['tipo_gestion'] ?>"> 
            <input type="hidden" name="cod_siguiente" id="cod_siguiente" value="<?php echo $post['cod_siguiente'] ?>">
            <input type="hidden" name="cod_fisc" id="cod_fisc" value="<?php echo $consulta[0]['COD_FISCALIZACION'] ?>">
            <input type="hidden" name="nombre" id="nombre" value="">
            <input type="hidden" name="tipo_doc" id="tipo_doc" value="<?php echo $post['tipo_doc'] ?>">
            <input type="hidden" name="respuesta" id="respuesta" value="<?php echo $consulta[0]['COD_TIPORESPUESTA'] ?>">

        </div>
        <?php // echo form_close(); ?>
        </form>
    </div>
    <form id="form" name="form" target = "_blank"  method="post" action="<?php echo base_url('index.php/mc_avaluo/pdf') ?>">
        <textarea id="descripcion_pdf" name="descripcion_pdf" style="width: 100%;height: 300px; display:none"></textarea>  
        <input type="hidden" name="nombre_archivo" id="nombre_archivo">
    </form>
</div>
<div id="div_mensaje"></div>
</div>
<script>
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
         $("#ocultar_observaciones").click(function() {
            $("#Observaciones_anteriores").hide();
        });


     $('#ver_observaciones').click(function(){
        $("#sube_documento").hide();
        $("#Observaciones_anteriores").show();
       })
    $(document).ready(function() {
        var nit = "<?php echo $consulta[0]['NIT_EMPRESA'] ?>";
        var nombre_archivo = "<?php echo date("d-m-Y H:i:s"); ?>";
        var nombre_archivo = nombre_archivo.replace(":", "_");
        var nombre_archivo = nombre_archivo.replace(":", "_");
        var nombre_archivo = nombre_archivo.replace(" ", "_");
        var nombre_archivo = nombre_archivo + "_" + nit;
        document.getElementById("nombre_archivo").value = nombre_archivo;
        document.getElementById("nombre").value = 'Documento_Notificacion_' + nombre_archivo;
    });

    $('#pdf').click(function() {
        var url = "<?php echo base_url('index.php/mc_avaluo/pdf') ?>";
        var informacion = tinymce.get('informacion').getContent();

        if (informacion == '')
        {
            mierror = 'Debe ingresar el contenido de la notificación';
            document.getElementById("respuesta").innerHTML = mierror;
            return false;
        }
        var nit = "<?php echo $consulta[0]['NIT_EMPRESA'] ?>";
        var nombre_archivo = "<?php echo date("d-m-Y H:i:s"); ?>";
        var nombre_archivo = nombre_archivo.replace(":", "_");
        var nombre_archivo = nombre_archivo.replace(":", "_");
        var nombre_archivo = nombre_archivo.replace(" ", "_");
        var nombre_archivo = nombre_archivo + "_" + nit;
        document.getElementById("nombre_archivo").value = nombre_archivo;
        document.getElementById("descripcion_pdf").value = informacion;
        $("#form").submit();



    })
   function validar() {

        var url = "<?php echo base_url('index.php/mc_avaluo/guarda_notificacion_correo') ?>";
        var informacion = tinymce.get('informacion').getContent();
        if (informacion == "") {
             $('#div_mensaje').css('display', 'block');
                mierror = '<div style="color:#5BB75B;"  class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button>' + 'Debe ingresar la información  en el Texto Enriquecido' + '</div>';
                document.getElementById("div_mensaje").innerHTML = mierror;
                return false;

        }
        else {
            $("#myform").submit();
        }
    }
 function soloNumeros(e)
    {
        var keynum = window.event ? window.event.keyCode : e.which;
        if ((keynum == 8) || (keynum == 46))
            return true;

        return /\d/.test(String.fromCharCode(keynum));
    }

        $("#fecha_radicado").datepicker({
            dateFormat: "yy/mm/dd",
            changeMonth: true,
            maxDate: "0",
            changeYear: true,
        });
    



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
</style>