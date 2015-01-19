<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
if (isset($message)) {
    echo $message;
}
if (isset($custom_error))
    echo $custom_error;
?>

<div>
    <div id="div_documento_subido_" style="text-align:center;">
        <div id="ruta_archivo" name="ruta_archivo" style="display: block;width: 250px; overflow: hidden;   clear: both; margin: 20px 0;  width: 90%; margin-top:30px; text-align: left">
            <div id="archivo_subido" style="text-align:center;">
                <h4 class="text-left" style="color:#aaaaaa;">Archivo Subido</h4>
                <li><a style="background-color: transparent;color:#006600;" href="<?php echo base_url() . $post['ruta_doc']; ?>"  target="_blank" class="linkli">
                        <?php echo $post['nombre'] ?></a>
                    <button type="button"  style="background-color: transparent;color:#cc0000;  " onclick="eliminarDD('<?php echo $post['ruta_doc']; ?>', '<?php echo $post['nombre']; ?>')"
                            <li class="icon-trash" title="Eliminar"></li>
                </li>
                <span id="loader" style="display: none; float:center; position: relative">
                    <img src="<?= base_url() ?>/img/27.gif" width="40px" height="40px" /></span>

            </div>
            <form name="myform3" id="myform3" action="" method="post">
                <?php
                echo form_label('Número Radicado<span class="required">*</span>', 'Número');
                ?>
                <input type="text" name="numero_radicado" id="numero_radicado" onpaste="return onkeypress="return soloNumeros(event)" class="requerid" maxlength="15" />  
                <?php
                echo form_label('Fecha Radicado <span class="required">*</span>', 'Fecha Radicado');
                ?>
                <input type="text" name="fecha_radicado" id="fecha_radicado" onpaste="return false" onkeypress="return prueba(event)" class="requerid"  />  
                <input type="hidden" name="descripcion" id="descripcion" />  
                <p></br>
                    <?php
                    $data = array('name' => 'button', 'id' => 'Aprobar', 'value' => 'Guardar', 'type' => 'button', 'onclick' => 'f_guardar()', 'content' => '<i class="fa fa-floppy-o fa-lg"></i> Guardar', 'class' => 'btn btn-success');
                    echo form_button($data);
                    ?>
                </p>
                <?php echo form_hidden('detalle', serialize($post)); ?>
            </form> 
        </div>
    </div>
</div>
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

    //para eliminar el documento
    //para eliminar el documento
    function eliminarDD(ruta, documento) {
        var res = confirm('Seguro desea eliminar el documento?');
        if (res == true) {
            $("#loader").css("display", "block");
            var ruta = ruta;
            var documento = documento;
            var url = "<?php echo $post['ruta_formulario'] ?>";
            $.ajax({
                type: "POST",
                url: url,
                data: {"ruta": ruta, "documento": documento},
                success: function(data)
                {
                    $("#div_documento_subido_").hide();
                    $("#subir_archivo").show();
                    $("#revision").html(data);
                },
                //si ha ocurrido un error
                error: function() {
                    $("#ajax_load").hide();
                    alert("Datos incompletos");
                }
            });
        }
    }


    function f_guardar()
    {

        var num_docum = $('#numero_radicado').val();
        var fecha_documento = $('#fecha_radicado').val();
        if (num_docum == '')
        {
            $("#respuesta").show();
            mierror = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' + 'Debe ingresar el número de documento' + '</div>';
            document.getElementById("respuesta").innerHTML = mierror;

        }
//        if (fecha_documento == '')
//        {
//            $("#respuesta").show();
//            mierror = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' + 'Debe ingresar la fecha del documento' + '</div>';
//            document.getElementById("respuesta").innerHTML = mierror;
//
//        }

        else {

            var respuesta = confirm('Esta seguro de guardar la información ?');
            if (respuesta == true) {
                //jQuery(".preload, .load").show();
                 $(".ajax_load").show();
                var url = '<?php echo base_url($post['ruta_form']) ?>';
                $.ajax({
                    type: "POST",
                    url: url,
                    data: $("#myform3").serialize(), // Adjuntar los campos del formulario enviado.
                    success: function(data)
                    {
                       // jQuery(".preload, .load").show();
                        $(".ajax_load").hide();
                      //  $("#respuesta").show();
                       // $("#respuesta").html(data);
                        $("#subir_archivo").hide();
                        $("#div_documento_subido_").hide();
                        $('#resultado').dialog('close');
                        $('#resultado *').remove();
                        location.reload();

                    },
                    //si ha ocurrido un error
                    error: function() {
                        $(".ajax_load").hide();
                        alert("Datos incompletos");
                    }
                });
            }
        }

    }
    function soloNumeros(e)
    {
        var keynum = window.event ? window.event.keyCode : e.which;
        if ((keynum == 8) || (keynum == 46))
            return true;
        return /\d/.test(String.fromCharCode(keynum));
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