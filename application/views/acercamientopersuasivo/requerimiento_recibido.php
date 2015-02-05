<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
if (isset($message)) {
    echo $message;
}
if (isset($custom_error))
    echo $custom_error;
?>

<div>
    <div id="gestion">
        <form name="myform" id="myform" method="post" action="<?php echo base_url($url) ?>" >
            <table>
                <h4  style="color:#aaaaaa;text-align:center;  ">Seleccione un tipo de respuesta</h4> 
                <tr class="sub">
                    <td>
                        <p>
                            <label  class="primario" style="height:30px; width:auto; ">
                                <?php
                                $data = array('name' => 'respuesta', 'id' => 'onbase', 'value' => '1', 'checked' => FALSE, 'class' => 'validate[required]', 'onclick' => 'f_onbase()',);
                                echo form_radio($data);
                                ?>
                                On Base</label>
                        </p>
                        <p>
                            <label  class="primario" style="height:30px;  width:auto;">
                                <?php
                                $data = array('name' => 'respuesta', 'id' => 'fisico', 'value' => '2', 'checked' => FALSE, 'onclick' => 'f_fisico()', 'class' => 'validate[required]');
                                echo form_radio($data);
                                ?>
                                Personal</label>
                        </p>  
                        <p>
                            <label  class="primario" style="height:30px;  width:auto;">
                                <?php
                                $data = array('name' => 'respuesta', 'id' => 'sin_respuesta', 'value' => '3', 'onclick' => 'f_sinrespuesta()', 'checked' => FALSE, 'class' => 'validate[required]');
                                echo form_radio($data);
                                ?>
                                Sin Respuesta</label>
                        </p>  
                    </td>
                </tr>
            </table>
            <div id="respuesta_opc1" name="respuesta_opc1" style="display:none; padding:20px 0 0 0; text-align: center;">
                <h4 class="text-left" style="color:#aaaaaa;">Ingresar Datos de la respuesta</h4>
                <table style="text-align: center;">
                    <tr>
                        <td>
                            <label  class="primario" style="height:30px;  ">Fecha </label>
                        </td>
                        <td>
                            <?php
                            $data = array('name' => 'fecha_recibida', 'readonly' => 'readonly', 'id' => 'fecha_recibida', 'class' => 'validate[required, custom[date]]');
                            echo form_input($data);
                            ?>	
                        </td>
                    </tr>
                     <tr>
                        <td>
                            <label  class="primario" style="height:30px;  ">Radicado Onbase </label>
                        </td>
                        <td>
                            <?php
                            $data = array('name' => 'radicado_onbase', 'id' => 'radicado_onbase', 'class' => 'validate[required, custom[date]]');
                            echo form_input($data);
                            ?>	
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p>
                                <label  class="primario" style="height:30px; width:auto; text-align:left;  ">
                                    <?php
                                    $data = array('name' => 'respuesta2', 'id' => 'sireorganizacion', 'value' => '1', 'checked' => FALSE, 'class' => 'validate[required]');
                                    echo form_radio($data);
                                    ?>
                                    Procesos Concursales y/o Liquidatorios</label>
                            </p>
                            <p>
                                <label  class="primario" style="height:30px;  width:auto;text-align:left;">
                                    <?php
                                    $data = array('name' => 'respuesta2', 'id' => 'noreorganizacion', 'value' => '2', 'checked' => FALSE, 'class' => 'validate[required]');
                                    echo form_radio($data);
                                    ?>
                                    No Procesos Concursales  y/o Liquidatorios</label>
                            </p>  

                        </td>

                    </tr>


                </table>

            </div>

            <div  ><?php
                $data = array(
                    'name' => 'button',
                    'id' => 'Guardar',
                    'value' => 'Guardar',
                    'type' => 'button',
                    'onclick' => 'return save()',
                    'content' => '<i class="fa fa-floppy-o fa-lg"></i> Guardar',
                    'class' => 'btn btn-success'
                );
                echo form_button($data);
                echo form_hidden('detalle', serialize($post));
                ?>
            </div>
    </div>
</form>
</div>
<div id="respuesta" class="respuesta"></div>
</div>
<div id="ajax_load" class="ajax_load" style="display: none">
    <div class="preload" id="preload" ></div><img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
</div>
<script type="text/javascript">
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
    });
    function finalizar() {
        $("#gestion").css('display', 'none');

    }
    function f_onbase() {
        $("#respuesta_opc1").css("display", "block");
        $("#respuesta_opc2").css("display", "none");
    }
    function f_fisico() {
        $("#respuesta_opc1").css("display", "block");
        $("#respuesta_opc2").css("display", "none");
    }
    ;
    function f_sinrespuesta() {
        $("#respuesta_opc1").css("display", "none");
        $("#respuesta_opc2").css("display", "block");
    }

    $(document).ready(function() {
        $("#fecha_recibida").datepicker({
            dateFormat: "yy/mm/dd",
            changeMonth: true,
            maxDate: "0",
            changeYear: true,
        });

        $("#no_reorganizacion_empresarial").click(function() {
            $("#acep_obl").css("display", "block");
        });
        $("#reorganizacion_empresarial").click(function() {
            $("#acep_obl").css("display", "none");
        });

        $("#acepta").click(function() {
            $("#acepta_obligacion").css("display", "block");
            $("#noacepta_obligacion").css("display", "none");
        });
        $("#noacepta").click(function() {
            $("#acepta_obligacion").css("display", "none");
            $("#noacepta_obligacion").css("display", "block");
        });


    });

    function save() {


        var val_onbase = $("input[id='onbase']:checked").val();
        var val_fisico = $("input[id='fisico']:checked").val();
        var reorganiza = $("input[id='sireorganizacion']:checked").val();
        var noreorganiza = $("input[id='noreorganizacion']:checked").val();
        var val_sinrespuesta = $("input[id='sin_respuesta']:checked").val();
        var val_fecha_recibida = $("#fecha_recibida").val();

//
        if ((val_onbase == undefined) && (val_fisico == undefined) && (val_sinrespuesta == undefined))
        {

            mierror = '<div  class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' + 'Debe seleccionar un tipo de respuesta' + '</div>';
            document.getElementById("respuesta").innerHTML = mierror;
            $("#respuesta").css('display', 'block');
            $("#respuesta").fadeOut(3000);
            return false;

        }

//        else if (val_onbase || val_fisico)
//        {
//////
//            if (val_fecha_recibida == '' || val_fecha_recibida == null)
//            { alert('hola 1');
//                mierror = '<div  class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' + 'Debe ingresar la fecha en la que se recibio la notificación' + '</div>';
//                document.getElementById("respuesta").innerHTML = mierror;
//                 $("#respuesta").css('display', 'block');
//                $("#respuesta").fadeOut(3000);
//                return false;
//            }
//
//            else if ((reorganiza == null || reorganiza == '') && (noreorganiza == null || noreorganiza == ''))
//            {  alert('hola 2');
//                mierror = '<div  class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>' + 'Debe seleccionar si la empresa esta o no en Procesos Concursales' + '</div>';
//                document.getElementById("respuesta").innerHTML = mierror;
//                 $("#respuesta").css('display', 'block');
//                 $("#respuesta").fadeOut(3000);
//                return false;
//            }
//            else 
//            {
//                return true;
//            }
//            
        // }
        else {

            jQuery(".preload, .load").show();
            var url = "<?php echo $url ?>"; // El script a dónde se realizará la petición.
            $.ajax({
                type: "POST",
                url: url,
                data: $("#myform").serialize(), // Adjuntar los campos del formulario enviado.
                success: function(data)
                {
                    $("#respuesta").css('display', 'block');
                    jQuery(".preload, .load").hide();
                    $("#respuesta").html(data);
                    $('#resultado').dialog('close');
                    $('#resultado *').remove();
                   window.location.href = "<?php echo base_url('index.php/acercamientopersuasivo/abogado') ?>";

                }
                ,
                //si ha ocurrido un error
                error: function() {
                    $("#ajax_load").hide();
                    alert("Ha Ocurrido un Error");
                }

            });

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
