<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
if (isset($message)) {
    echo $message;
}
if (isset($custom_error))
    echo $custom_error;
?>

<div id="ajax_load" class="ajax_load" style="display: none">
    <div class="preload" id="preload" ></div><img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
</div>
<?php
$attributes = array("id" => "myform");
echo form_open_multipart("mc_avaluo/recibo_honorarios", $attributes);
?>

<h4 style="text-align:center;">Recepción de Recibo de Pago de Honorarios y Registro Avalúo</h4><br>
<table id="tabla1" style="width:auto; ">

    <tr>
        <td><br><br><span >Avaluo y Recibo Pago de Honorarios Recibido?</span></td>
        <td><select name="honorarios" id="honorarios" class="requerid">
                <option value="">Elija una opción</option>
                <option value="S">SI</option>
                <option value="N">NO</option>
            </select>
        </td>    
    </tr>
</table>
<input type="hidden" name="detalle" id="detalle" value="<?php echo serialize($post) ?>">
<div id="objeto_no" style="display:block">
    <br>
    <?php
    $data = array(
        'name' => 'button',
        'id' => 'salir',
        'value' => 'Salir',
        'type' => 'button',
        'style' => 'margin-left:60px;',
        'content' => 'Salir',
        'class' => 'btn btn-success',
    );
    echo form_button($data);
    ?>

    <?php
    $data = array(
        'name' => 'button',
        'id' => 'Continuar',
        'value' => 'Continuar',
        'type' => 'submit',
        'style' => 'margin-left:120px;',
        'content' => 'Continuar',
        'class' => 'btn btn-success',
        'onclick' => 'return continuar()',
    );

    echo form_button($data);
    
    ?>
<?php echo form_hidden('detalle', serialize($post)); ?>
    <?php echo form_close(); ?>
</div>
</table>
<script>
    $("#ajax_load").css("display", "none");
    jQuery(".preload, .load").hide();
    $('#resultado').dialog({
        autoOpen: true,
        width: 800,
        height: 450,
        modal: true,
        title: "Registrar Avalúo.",
        close: function() {
            $('#resultado *').remove();
            $("#ajax_load").css("display", "none");
        }
    });

   

    function continuar()
    {
        var recibo = document.getElementById("honorarios").value;
        if (recibo == '' || recibo == false)
        {
            alert('Indique si el Avaluo y Recibo Pago de Honorarios fueron recibidos');
            return false;
        }
        else
        {
            $("#myform").submit();
        }
    }
    $(document).ready(function() {
        $('#salir').click(function() {

            $('#resultado *').remove();
            $('#resultado').dialog('close');
        });
    });



</script>
<style>

    .hide-close .ui-id-2 { display: none }

</style>