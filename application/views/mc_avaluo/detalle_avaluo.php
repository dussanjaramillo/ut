<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
if (isset($message)) {
    echo $message;
}
if (isset($custom_error))
    echo $custom_error;
?>
<div id="descripcion_detalle" class="alert-info">
    A continuación podrá ingresar el detalle del avaluó de cada bien, para ello debe seleccionar cada avalúo y diligenciar la información
    correspondiente a cada bien, una vez registrado el detalle, ingrese el número de radicado, la fecha de radicado y el documento soporte del avalúo.

</div>
<div id="mensaje_registro" class="alert-success"></div>
<br>
<h4>Registro Avalúo</h4>
<table id="tabla1">
    <thead>
        <tr>
            <th>N° PROCESO</th>
            <th>REGIONAL</th>
            <th>N° MEDIDA CAUTELAR</th>
            <th>IDENTIFICACIÓN<BR>EJECUTADO</th>
            <th>EJECUTADO</th>
            <td>BIEN</td>
            <td>GESTIÓN</td>
        </tr>
    </thead> 
    <tbody>
        <?php
//        echo "<pre>";
//        print_r($consulta );
//        echo "</pre>";
        if ($consulta):
            foreach ($consulta as $data):
                ?>
                <tr>
                    <td><?=$data['COD_PROCESOPJ'] ?></td>
                    <td><?= $data['NOMBRE_REGIONAL'] ?></td>
                    <td><?= $data['COD_MEDIDACAUTELAR'] ?></td>
                    <td><?= $data['IDENTIFICACION'] ?></td>
                    <td><?= $data['EJECUTADO'] ?></td>
                    <td><?= $data['NOMBRE_TIPO'] ?></td>
                    <td>
                        <a class="btn btn-small btn-info" onclick="f_detalle('<?= $data['COD_PROCESO'] ?>', '<?= $data['COD_AVALUO'] ?>', '<?= $data['COD_TIPO_INMUEBLE'] ?>');" title="ver" target="_blank">
                            <i class="fa fa-eye "></i>
                    </td>
                </tr>
                <?php
            endforeach;
        endif;
        ?>
    </tbody>
</table>
<br>
<?php
$attributes = array("id" => "myform", "onsubmit" => "return f_enviar()");
echo form_open_multipart($ruta_guarda_avaluo, $attributes);
?>
<table style=" padding:70px 50px 0px 15px; width: 100%">
    <tr><td><span>Comentarios</span></td>
        <td><textarea id="observaciones" name="observaciones" style="width: 90%;"></textarea>  </td></tr>
    <tr><td>
            <span>Número de Radicado</span><span class="requerid" style="color:red">*</span></td>
        <td>
            <input type="text" name="numero_radicado" id="numero_radicado" class="requerid" ></td></tr>
    <tr><td>
            <span>Fecha de Radicado</span><span class="requerid" style="color:red">*</span></td>
        <td><input type="text"  name="fecha_radicado" id="fecha_radicado" onkeypress="return prueba(event)" class="requerid"  ></td></tr>
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
        <td><div id="documento_subido" style="float:left; width: 50%;">
                <input id="ruta" type="hidden" value="" name="ruta">
            </div></td>
    </tr>
    <tr><td  colspan="2" style="text-align: center">  <input type="submit" id="guardar" class='btn btn-success' value="Guardar" ></td></tr>
</table>
<?php echo form_hidden('detalle', serialize($post)); ?>
<?php echo form_close(); ?>
<form id="form_bandeja" action="<?php echo base_url('index.php/bandejaunificada/index'); ?>" method="post">
    <input type="hidden" id="cod_coactivo" name="cod_coactivo" value="<?php echo $cod_coactivo; ?>">
    <button class="btn btn"> Regresar </button>
</form>
<div id="resultado"></div>
<div id="ajax_load" class="ajax_load" style="display: none">
    <div class="preload" id="preload" ></div><img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
</div>
<script>
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
                $("#ajax_load").hide();

            },
            //si ha ocurrido un error
            error: function() {
                $(".ajax_load,.preload").hide();
                alert("Ha Ocurrido un Error");
            }
        });
    }
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
                document.getElementById("").innerHTML = mierror;
                $("#mensaje_alerta").fadeOut(15000);
                return false;
            }
            //si estoy aqui es que no se ha podido submitir
            else {
                      // Controla el tamaño del archivo a cargar          
                var file = $("#imagen")[0].files[0];
                var fileSize = file.size;
                var resultado = 0;
                if(fileSize > 5242880) {
                    $('#imagen').val('');
                    resultado = (fileSize/1024)/1024;
                    var resultado1 = Math.round(resultado*100)/100 ; 
                    mierror = "";
                    mierror = '<div  class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' + 'El archivo (' + archivo + ') a superado el limite de tamaño para adjuntos de 5Mb. Tamaño archivo de: ' + resultado1 + ' Mb </div>';

                    $("#respuesta").css('display', 'block');
                    document.getElementById("mensaje_alerta").innerHTML = mierror;
                    $("#mensaje_alerta").fadeOut(15000);
                    return false;
                } else {
                   return true;
                }
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
        changeYear: true
    });
    function f_detalle(id, cod_avaluo, tipo_inmueble) {
         $(".ajax_load,.preload").css('display','block');
        var url = "<?= base_url("index.php/mc_avaluo/detalle_avaluo") ?>";
        $('#resultado').load(url, {id: id, cod_avaluo: cod_avaluo, tipo_inmueble: tipo_inmueble});
    }
    $('#tabla1').dataTable({
        "bJQueryUI": true,
        "sPaginationType": "full_numbers",
        "sServerMethod": "POST",
        "oLanguage": {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix": "",
            "sSearch": "Buscar:",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "fnInfoCallback": null
        }
    });
</script>
<style>
    .ui-widget-overlay{z-index: 10000;}
    .ui-dialog{
        z-index: 15000;
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