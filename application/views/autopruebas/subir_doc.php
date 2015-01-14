<?php
$attributes = array('onsubmit' => 'return comprobarextension()', "id" => "myform");
echo form_open_multipart("autopruebas/subir_acta_archivo2", $attributes);
?>
<input type="hidden" name="id" id="id" value="<?php echo $post['id']; ?>">
<input type="hidden" name="cod_fis" id="cod_fis" value="<?php echo $post['cod_fis']; ?>">
<input type="hidden" name="nit" id="nit" value="<?php echo $post['nit']; ?>">
<table style="width: 100%">
    <tr>
        <td>Nro. Radicado</td>
        <td><input type="text" name="nombre_archivo" id="nombre_archivo" style="width: 150px" maxlength="30" value=""></td>
        <td>Fecha Radicado</td>
        <td><input type="text" class="fecha" name="fecha" id="fecha" style="width: 150px" readonly="readonly" value="<?php echo date("d/m/Y"); ?>"></td>
    </tr>
    <tr>
        <td colspan="4">
            <div style="overflow: hidden; clear: both;  width: 90%; text-align: center">
                <?php
                $data = array(
                    'name' => 'userfile',
                    'id' => 'imagen',
                    'class' => 'validate[required]'
                );
                echo form_upload($data);
                ?>
            </div>
        </td>
    </tr>
</table>

<input type="hidden" id="id_resolucion" name="id_resolucion" value="<?php echo $post['id'] ?>">
<?php echo form_close(); ?>

<center><button class="btn btn-success" onclick="enviar()"><i class="fa fa-floppy-o"></i> Guardar</button>&nbsp;&nbsp;<button id="" onclick="enviar_pdf()" class="btn btn-info">PDF</button></center>
<form id="form" name="form" target = "_blank"  method="post" action="<?php echo base_url('index.php/autocargos/pdf') ?>">
    <textarea id="descripcion_pdf" name="descripcion_pdf" style="width: 100%;height: 300px;display: none "><?php echo $texto ?></textarea>  
    <input type="hidden" name="nombre" id="nombre" value="">
</form>
<script>
    $(".fecha").datepicker();
    function enviar_pdf() {
        $("#form").submit();
    }
    function enviar() {
        $("#myform").submit();
    }
    $('#nombre_archivo').validCampoFranz(' abcdefghijklmnñopqrstuvwxyzáéiou0123456789');
    $('#resultado').dialog({
        autoOpen: true,
        width: 600,
        title: "Subir Archivo",
        height: 200,
        modal: true,
        close: function() {
            $('#resultado *').remove();
        }
    });
    $(".preload, .load").hide();
    function comprobarextension() {
        jQuery('#nombre_archivo').validationEngine('attach');
        var nombre_archivo = jQuery('#nombre_archivo').val();
        if (nombre_archivo == "") {
            alert('Campo de Nombre se encuentra vacío');
            return false;
        }

        if ($("#imagen").val() != "") {
            var archivo = $("#imagen").val();
            var extensiones_permitidas = new Array(".pdf");
            var mierror = "";
            //recupero la extensiÃ³n de este nombre de archivo
            var extension = (archivo.substring(archivo.lastIndexOf("."))).toLowerCase();
            //alert (extension);
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
                mierror = "Comprueba la extensiÃ³n de los archivos a subir.\nSÃ³lo se pueden subir archivos con extensiones: " + extensiones_permitidas.join();
            }
            //si estoy aqui es que no se ha podido submitir
            if (mierror != "") {
                alert(mierror);
                return false;
            }
            return true;
        } else {
            return false;
        }
    }
</script>