<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
if (isset($message)) {
    echo $message;
}
if (isset($custom_error))
    echo $custom_error;
?>
<div  style="display:block; text-align: center;"  >

    <fieldset style="width:auto; margin-top:20px;" >
        <div id="gestion" style="text-align:center;" >
        
            <table id="tabla1" >
                <tr class="sub">
                    <td>
                        <label  class="primario" style="height:30px; width:200px;"> <input type="radio" name="notificacion"  id="not_recibida"  onclick="validar_radio1()" value="1"      />Requerimiento Recibido</label>
                        <label  class="primario" style="height:30px;  width:200px;"><input type="radio" name="notificacion"  id="not_devuelta"  onclick="validar_radio2()"  value="2"   />Requerimiento Devuelto</label>
                    </td>
                </tr>
            </table><br><br>
            <div id="datos_recepcion" style="display:none; text-align:center;" >
                <form name="myform1" id="myform1" method="post" action="" >
                    <input type="hidden" name="acciones" id="acciones" value="Notificacion Recibida" />
                    <input type="hidden" name="causal_devolucion" id="causal_devolucion" value="1"/>
                    <table style="width:auto">
                        <tr><th style="text-align:center;  " colspan="2" >Datos Requerimiento Recibido</th></tr>
                        <tr>	 <td>
                                <label  class="primario" style="height:30px; width:auto; "> <br>Fecha Recibido</label>
                            </td>
                            <td>
                                <div style="margin-left:0px;"> <br>
                                    <?php
                                    $data = array('name' => 'fecha_recibida', 'id' => 'fecha_recibida', 'class' => 'validate[required]', 'readonly' => 'readonly');
                                    echo form_input($data);
                                    ?>
                                    <label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label  class="primario" style="height:30px; width:auto;">Nombre de quien recibe</label>
                            </td>
                            <td>
                                <div style="margin-left:0px;">
                                    <?php
                                    $data = array('name' => 'nombre_receptor', 'id' => 'nombre_receptor', 'class' => 'validate[required]');
                                    echo form_input($data);
                                    ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label  class="primario" style="height:30px; width:auto;">NIS</label>
                            </td>
                            <td>
                                <div style="margin-left:0px;">
                                    <?php
                                    $data = array('name' => 'nis', 'id' => 'nis', 'class' => 'validate[required]', 'onkeypress' => 'return soloNumeros(event)', 'maxlength' => '15');
                                    echo form_input($data);
                                    ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label  class="primario" style="height:30px; width:auto;" >Radicado N°.</label>
                            </td>
                            <td>
                                <div style="margin-left:0px;">
                                    <?php
                                    $data = array('name' => 'num_radicado', 'id' => 'num_radicado', 'class' => 'validate[required]', 'onkeypress' => 'return soloNumeros(event)', 'maxlength' => '10');
                                    echo form_input($data);
                                    ?>
                                </div>
                            </td>
                        </tr>
                        <tr style="display:none">
                            <td>
                                <label  class="primario" style="height:30px; width:auto;">Hora:</label>
                            </td>
                            <td>
                                <div style="margin-left:0px;">Hora:
                                    <select name="hora" id="hora" style="width:80px; " class="validate[required]">
                                        <option value=""></option>
                                        <?php for ($i = 0; $i <= 23; $i++) { ?>
                                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                        <?php } ?>
                                    </select>
                                    Minutos:
                                    <select name="minutos" id="minutos" style="width:80px;" class="validate[required]">
                                        <option value=""></option>
                                        <?php for ($i = 0; $i <= 59; $i++) { ?>
                                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td> 
                                <?php
                                $data = array('name' => 'button', 'id' => 'Guardar1', 'value' => 'Guardar', 'type' => 'button',
                                    'content' => '<i class="fa fa-floppy-o fa-lg"></i> Guardar', 'class' => 'btn btn-success', 'onclick' => 'validar1()');
                                echo form_button($data);
                                echo form_hidden('detalle', serialize($post));
                                ?>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
            <!--------------      NOTIFICACION DEVUELTA ---------->  
            <div id="datos_devolucion" style="display:none;   text-align:center;" >
                <form name="myform1" id="myform2" method="post" action="" >
                    <input type="hidden" name="acciones" id="acciones" value="Notificacion Devuelta" /> 
                    <table>
                        <tr><th style="aling:center; margin-top:20px;   " colspan="2" >
                        <div class='titulo'>Datos Requerimiento Devuelto</div></th></tr>
                        <tr>
                            <td>
                                <label  class="primario" style="height:30px; width:auto; "> <br>Fecha Devolución</label>
                            </td>
                            <td>
                                <div style="margin-left:0px;"> <br>
                                    <?php
                                    $data = array(
                                        'name' => 'fecha_devolucion',
                                        'id' => 'fecha_devolucion',
                                        'class' => 'validate[required]',
                                        'readonly' => 'readonly'
                                    );
                                    echo form_input($data);
                                    ?>
                                    <label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label  class="primario" style="height:30px; width:auto;">Causal de devolución</label>
                            </td>
                            <td>
                                <div style="margin-left:0px;">
                                    <select name="causal_devolucion" id="causal_devolucion" class="validate[required]"><?php
                                        echo '<option value="">Seleccione la causal...</option>';
                                        foreach ($this->data['causales'] as $causal) :
                                            echo '<option value="' . $causal['COD_CAUSALDEVOLUCION'] . '">' . $causal['NOMBRE_CAUSAL'] . '</option>';
                                        endforeach;
                                        ?></select>
                                </div>
                            </td>

                        </tr>
                        <tr>
                            <td>
                                <label  class="primario" style="height:30px; width:auto;">NIS</label>
                            </td>
                            <td>
                                <div style="margin-left:0px;">
                                    <?php
                                    $data = array(
                                        'name' => 'nis_devolucion',
                                        'id' => 'nis_devolucion',
                                        'class' => 'validate[required]',
                                        'onkeypress' => 'return soloNumeros(event)',
                                    );
                                    echo form_input($data);
                                    ?>

                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label  class="primario" style="height:30px; width:auto;">Radicado N°.</label>
                            </td>
                            <td>
                                <div style="margin-left:0px;">
                                    <?php
                                    $data = array(
                                        'name' => 'num_radicado_dev',
                                        'id' => 'num_radicado_dev',
                                        'class' => 'validate[required]',
                                        'onkeypress' => 'return soloNumeros(event)',
                                    );
                                    echo form_input($data);
                                    ?>
                                </div>
                            </td>
                        </tr>
                        <tr style="display:none">
                            <td>
                                <label  class="primario" style="height:30px; width:auto;">Hora:</label>
                            </td>
                            <td>
                                <div style="margin-left:0px;">Hora:
                                    <select name="hora_dev" id="hora_dev" style="width:80px; " class="validate[required]">
                                        <option value=""></option>
                                        <?php for ($i = 0; $i <= 23; $i++) { ?>
                                            <option value="<?php echo $i; ?>"><?php
                                                if ($i < 10) {
                                                    echo "0" . $i;
                                                } else {
                                                    echo $i;
                                                }
                                                ?></option>
                                        <?php } ?>
                                    </select>
                                    Minutos:
                                    <select name="minutos_dev" id="minutos_dev" style="width:80px;" class="validate[required]">
                                        <option value=""></option>
                                        <?php for ($i = 0; $i <= 59; $i++) { ?>
                                            <option value="<?php echo $i; ?>"><?php
                                                if ($i < 10) {
                                                    echo "0" . $i;
                                                } else {
                                                    echo $i;
                                                }
                                                ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </td>
                        </tr>
                        <tr><td colspan="2" ><br><br>
                                <?php
                                $data = array(
                                    'name' => 'button',
                                    'id' => 'Guardar2',
                                    'value' => 'Guardar',
                                    'type' => 'button',
                                    'content' => '<i class="fa fa-floppy-o fa-lg"></i> Guardar',
                                    'class' => 'btn btn-success',
                                    'onclick' => 'validar2()',
                                );
                                echo form_button($data);
                                ?>
                                <?php
                                $data = array(
                                    'name' => 'button',
                                    'id' => 'corregir',
                                    'value' => 'Corregir Requerimiento',
                                    'type' => 'button',
                                    'content' => '<i class="fa fa-book fa-lg"></i> Corregir Requerimiento',
                                    'class' => 'btn btn-success',
                                    'onclick' => 'f_corregir()',
                                );
                                echo form_button($data);
                                ?>
                            </td></tr>
                        <tr><td colspan="2" style="text-align:center;">
                                <div id="contenido_req"style="display:none"><br>
                                    <textarea name="descripcion" id="descripcion" style="width:auto; height: 400px; text-align:center;">
                                        <?php echo $documento ?>
                                    </textarea>
                                </div>
                            </td>
                        <tr>
                            <td colspan="2">

                                <br>
                                <?php
                                $data = array('name' => 'pdf', 'onclick' => 'genera_pdf()', 'id' => 'pdf', 'style' => 'display:none;align="center";', 'value' => 'Generar PDF', 'content' => '<i class="fa fa-file"></i> Generar PDF', 'type' => 'button', 'class' => 'btn btn-success');
                                echo form_button($data);
                                ?>
                                <?php
                                $data = array(
                                    'name' => 'button',
                                    'id' => 'enviar',
                                    'value' => 'enviar',
                                    'type' => 'button',
                                    'content' => '<i class="fa fa-floppy-o fa-lg"></i>Guardar Temporal',
                                    'class' => 'btn btn-success',
                                    'onclick' => 'f_enviar()',
                                    'style' => 'display:none;align="center";'
                                );
                                echo form_button($data);
                                ?> 

                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div id="subir_archivo" name="subir_archivo" style=" display:none; width: 250px; overflow: hidden;   clear: both; margin: 20px 0;  width: 90%; margin-top:30px; text-align: left">
                                    <h4 class="text-left" style="color:#aaaaaa;">Subir Archivo</h4>
                                    <div id="file_source" style="width:500px;"></div>
                                    <div class="input-append" id="arch0" ></div><br>
                                    <div style="width:500px;" >
                                        <input type="file" name="archivo0" id="archivo0"  class='btn btn-success file_uploader' />
                                    </div><br>
                                    <?php
                                    echo form_label('Número Documento <span class="required">*</span>', 'Número');
                                    ?>
                                    <input type="text" name="numero_documento" id="numero_documento"  onkeypress="return soloNumeros(event)" class="requerid" />    
                                    <?php
                                    echo form_label('Fecha Radicado <span class="required">*</span>', 'Fecha Radicado');
                                    ?>
                                    <input type="text" name="fecha_radicado" id="fecha_radicado" onkeypress="return prueba(event)" class="requerid"  />  
                                    <input type="button" id="Aceptar" name="Aceptar" style="margin-left:60px;" onclick=" return comprobarextension()" content="Aceptar" value="Aceptar" class="btn btn-success"/>
                                </div>
                                <div id="revision"></div>
                            </td>    
                        </tr>
                    </table>
                    <?php echo form_hidden('detalle2', serialize($post)); ?>
                </form>
            </div>
    </fieldset>
</div>
</div> 
<div id="ajax_load" class="ajax_load" style="display: none">
    <div class="preload" id="preload" ></div><img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
</div>

<form id="form_pdf" name="form_pdf" target = "_blank"  method="post" action="<?php echo $url_generar_pdf ?>">
    <textarea id="descripcion_pdf" name="descripcion_pdf" style="width: 100%;height: 300px; display:none"></textarea>  
    <input type="hidden" name="nombre" id="nombre" value="">
</form>
<div id="respuesta"></div>
<script type="text/javascript">
    $(document).ready(function() {
        $("#ajax_load").hide();
        $('#resultado').dialog({
            autoOpen: true,
            width: 900,
            height: 450,
            modal: true,
            title: "<?php echo "Requerimiento Acercamiento"; ?>",
            close: function() {
                $('#resultado *').remove();
                $("#ajax_load").hide();
            }

        });
    });


    tinymce.init({
        selector: "textarea#descripcion",
        language: 'es',
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
    function f_corregir()//permite visualizar la información para corregir el requerimiento
    {

        var fecha_devolucion = $("#fecha_devolucion").val();
        var causal_devolucion = $("#causal_devolucion").val();
        if (!fecha_devolucion)
        {
            var mierror = '<div style="display:block" id="error" class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Debe seleccionar una fecha de devolución</div>';
            $("#respuesta").css('display', 'block');
            document.getElementById("respuesta").innerHTML = mierror;
            $("#respuesta");
            return false;
        }
        else if (!causal_devolucion)
        {
            var mierror = '<div style="display:block" id="error" class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Debe seleccionar una causal de devolución</div>';
            $("#respuesta").css('display', 'block');
            document.getElementById("respuesta").innerHTML = mierror;
            $("#respuesta");
            return false;
        }
        else
        {
            $("#contenido_req").show();//div que contiene el textarea con la información del requerimiento
            $("#pdf").show();//boton para generar el pdf
            $("#enviar").show();//boton que guarda la información de la devolución y el contendio del texto
            $("#subir_archivo").show();//Div para subir el archivo con el requerimiento firmado 
        }


    }
    function comprobarextension() {//verifica la extensión del archivo del requerimiento firmado
        if ($("#archivo0").val() != "") {
            var archivo = $("#archivo0").val();
            var extensiones_permitidas = new Array(".pdf");
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
                jQuery("#archivo0").val("");
                mierror = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' + 'Comprueba la extensión de los archivos a subir.\nSólo se pueden subir archivos con extensiones: ' + extensiones_permitidas.join() + '</div>';
                $("#respuesta").css('display', 'block');
                document.getElementById("respuesta").innerHTML = mierror;
                $("#respuesta");
                return false;
            }
            //si estoy aqui es que no se ha podido submitir
            else {
                enviar();
            }
        } else {
            alert('Debe seleccionar un documento');
            return false;
        }
    }

    function f_enviar()//guarda la informació
    //n de la devolución y el contendio del texto
    {
        $("#ajax_load").show();
        var descripcion = tinymce.get('descripcion').getContent();
        var nit = <?php echo $cabecera['IDENTIFICACION'] ?>;
        var nombre_archivo = "<?php echo date("d-m-Y H:i:s"); ?>";
        var nombre_archivo = nombre_archivo.replace(":", "_");
        var nombre_archivo = nombre_archivo.replace(":", "_");
        var nombre_archivo = nombre_archivo.replace(" ", "_");
        var nombre_archivo = "RequerimientoAcercamiento";
        var cod_cobro = <?php echo $post['cod_cobro'] ?>;
        var cod_proceso = <?php echo $post['cod_proceso'] ?>;
        var url2 = "<?php echo base_url('index.php/acercamientopersuasivo/guarda_temporal') ?>";
        if (!descripcion) {
            $("#ajax_load").hide();
            mierror = '<div  class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Debe ingresar la información en el texto enriquecido </div>';
            $("#respuesta").css('display', 'block');
            document.getElementById("respuesta").innerHTML = mierror;
            $("#respuesta").fadeOut(3000);
            return false;
        }
        else {
            $("#ajax_load").show();
            $.post(url2, {descripcion: descripcion, nit_empresa: nit, cod_proceso: cod_proceso, cod_cobro: cod_cobro, nombre_archivo: nombre_archivo}, function(data) {
                $("#respuesta").css('display', 'block');
                $("#respuesta").html(data);
                alert('Se ha guardado la información');
                $("#ajax_load").hide();

            })
        }
    }

    function genera_pdf()//Genera el pdf

    {
        var descripcion = tinymce.get('descripcion').getContent();
        if (!descripcion) {
            mierror = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Debe ingresar la información en el texto enriquecido </div>';
            $("#respuesta").css('display', 'block');
            document.getElementById("respuesta").innerHTML = mierror;
            $("#respuesta").fadeOut(3000);
            return false;
        }
        else {
            document.getElementById("nombre").value = "RequerimientoAcercamiento_Corregido2_" + $("#nit_empresa").val();
            document.getElementById("descripcion_pdf").value = base64_encode(descripcion);
            $("#form_pdf").submit();
        }
    }


    function soloNumeros(e)
    {
        var keynum = window.event ? window.event.keyCode : e.which;
        if ((keynum == 8) || (keynum == 46))
            return true;

        return /\d/.test(String.fromCharCode(keynum));
    }


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



    function enviar() {//sube documento del requerimiento


        var formData = new FormData($("#myform2")[0]);
        var numero_documento = $("#numero_documento").val();
        if (!numero_documento)
        {
            mierror = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Debe ingresar el número del documento</div>';
            $("#respuesta").css('display', 'block');
            document.getElementById("respuesta").innerHTML = mierror;
            $("#respuesta");
            return false;
        }
        else {
            // $("#revision").remove();
            $("#ajax_load").show();
            $.ajax({
                url: '<?php echo $url; ?>',
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                //una vez finalizado correctamente
                success: function(data) {
                    $("#subir_archivo").hide();
                    $("#ajax_load").hide();
                    $("#gestion").hide();
                    $("#respuesta").css("display", "block");
                    $('#resultado *').remove();
                    
                },
                //si ha ocurrido un error
                error: function() {
                    $("#ajax_load").hide();
                    alert("Ha Ocurrido un Error");
                }
            });
        }
    }
    function validar1() {//envia la información cuando el requerimiento es recibido

        var fecha_recibida = $("#fecha_recibida").val();
        var nombre_receptor = $("#nombre_receptor").val();
        var nis = $("#nis").val();
        var num_radicado = $("#num_radicado").val();
        var hora = $("#hora").val();
        var minutos = $("#minutos").val();
        $("#respuesta").css('display', 'block');
        if (fecha_recibida == '') {
            mierror = '<div   class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' + 'Debe seleccionar la fecha en que fue recibido el documento' + '</div>';
            document.getElementById("respuesta").innerHTML = mierror;
            $("#respuesta");
            return false;
        }
        else if (nombre_receptor == '') {
            mierror = '<div   class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' + 'Debe ingresar el nombre de quien recibe el documento' + '</div>';
            document.getElementById("respuesta").innerHTML = mierror;
            $("#respuesta");
            return false;
        }
//        else if (nis == '') {
//            mierror = '<div   class="aalert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' + 'Debe ingresar el número de nis' + '</div>';
//            document.getElementById("respuesta").innerHTML = mierror;
//            $("#respuesta").fadeOut(3000);
//            return false;
//        }
        else if (num_radicado == '') {
            mierror = '<div   class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' + 'Debe ingresar el número de radicado del documento ' + '</div>';
            document.getElementById("respuesta").innerHTML = mierror;
            $("#respuesta");
            return false;
        }

        else {

           $(".ajax_load").show();
            var url = "<?php echo base_url() ?>index.php/acercamientopersuasivo/respuesta_notificacion"; // El script a dónde se realizará la petición.
            $.ajax({
                type: "POST",
                url: url,
                data: $("#myform1").serialize(), // Adjuntar los campos del formulario enviado.
                success: function(data)
                {
                    $(".ajax_load").hide();
                    $("#respuesta").html(data);
                    $('#resultado').dialog('close');
                    $('#resultado *').remove();
                    window.location.href = "<?php echo base_url('index.php/acercamientopersuasivo/abogado') ?>";

                }
                ,
                error: function(result) {
                    $(".ajax_load").hide();
                    alert("Datos incompletos");
                    $("#respuesta").html(result);
                }
            });

        }
    }

    function validar2() {//envia la información cuando el requerimiento es devuelto pero no requiere corrección

        var fecha_devolucion = $("#fecha_devolucion").val();
        var nis_devolucion = $("#nis_devolucion").val();
        var num_radicado_dev = $("#num_radicado_dev").val();
        var hora_dev = $("#hora_dev").val();
        var minutos_dev = $("#minutos_dev").val();
        var causal_devolucion = $("#causal_devolucion").val();
        $("#respuesta").css('display', 'block');
        if (fecha_devolucion == '') {
            mierror = '<div   class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' + 'Debe ingresar la fecha de devolución' + '</div>';
            document.getElementById("respuesta").innerHTML = mierror;
            $("#respuesta");
            return false;
        }
        else if (causal_devolucion == '') {
            mierror = '<div   class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' + 'Debe seleccionar la causal de devolución' + '</div>';
            document.getElementById("respuesta").innerHTML = mierror;
            $("#respuesta");
            return false;
        }
//        else if (nis_devolucion == '') {
//            mierror = '<div   class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' + 'Debe ingresar el nis de devolución' + '</div>';
//            document.getElementById("respuesta").innerHTML = mierror;
//            $("#respuesta").fadeOut(3000);
//            return false;
//        }
        else if (num_radicado_dev == '') {
            mierror = '<div   class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' + 'Debe ingresar el número de radicado' + '</div>';
            document.getElementById("respuesta").innerHTML = mierror;
            $("#respuesta");
            return false;
        }


       

        else {
            $(".ajax_load").show();
            var url = "<?php echo base_url() ?>index.php/acercamientopersuasivo/respuesta_notificacion"; // El script a dónde se realizará la petición.
            $.ajax({
                type: "POST",
                url: url,
                data: $("#myform2").serialize(), // Adjuntar los campos del formulario enviado.
                success: function(data)
                {
                    $(".ajax_load").hide();
                    $("#respuesta").html(data);
                    $('#resultado').dialog('close');
                    $('#resultado *').remove();
                    window.location.href = "<?php echo base_url('index.php/acercamientopersuasivo/abogado') ?>";

                }
                ,
                error: function(result) {
                    $(".ajax_load").hide();
                    alert("Datos incompletos");
                }
            });

        }
    }

    $(document).ready(function() {
        $("#fecha_recibida").datepicker({
            dateFormat: "yy/mm/dd",
            changeMonth: true,
            maxDate: "0",
            minDate: "<?php echo "-".$dias?>",
            changeYear: true,
        });

        $("#fecha_devolucion").datepicker({
            dateFormat: "yy/mm/dd",
            changeMonth: true,
            maxDate: "0",
            minDate: "<?php echo "-".$dias?>",
            changeYear: true,
        });
    });

    function ver()
    {

        val_de = $("input[id='de']:checked").val();//si esta chequeado trae el value	
        val_dr = $("input[id='dr']:checked").val();//si esta chequeado trae el value

        if (val_de == 1)
        {

            $("#dat_envio").css("display", "block");
            $("#dat_recepcion").css("display", "none");
        }
        else if (val_dr == 1)
        {


            $("#dat_recepcion").css("display", "block");
            $("#dat_envio").css("display", "none");
        }



    }
    function validar_radio1()
    {

        val_recibida = $("input[id='not_recibida']:checked").val();//si esta chequeado trae el value	
        if (val_recibida == 1)//si esta chequeado trae el value y si el value es 1 va a mostrar el formulario para ingresar datos
        {
            $("#datos_recepcion").css("display", "block");
            $("#datos_devolucion").css("display", "none");
        }

    }
    function validar_radio2()
    {

        val_devuelta = $("input[id='not_devuelta']:checked").val();//si esta chequeado trae el value	
        if (val_devuelta == 2)//si esta chequeado trae el value y si el value es 1 va a mostrar el formulario para ingresar datos
        {
            $("#datos_recepcion").css("display", "none");
            $("#datos_devolucion").css("display", "block");
        }

    }

    function finalizar() {
        $("#gestion").css('display', 'none');
        recargartabla4();//datatable Requerimientos Devueltos
    }

    $(document).ready(function() {
        $("#fecha_radicado").datepicker({
            dateFormat: "yy/mm/dd",
            changeMonth: true,
            maxDate: "0",
            changeYear: true,
        });
    });
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