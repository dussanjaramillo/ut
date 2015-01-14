<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php if (isset($message)) { echo $message;
}
if (isset($custom_error)) echo $custom_error;
?>
<div id="ajax_load" class="ajax_load" style="display: none">
    <div class="preload" id="preload" ></div><img  id="load" class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
</div>
<?php
$attributes = array("id" => "myform");
echo form_open_multipart("mc_avaluo/determina_correcion", $attributes);
?>

<div id="titulo">Determinar si hay corrección</div><br>
<table id="tabla1" style="width:auto; ">
    <tr>
        <td><span >Proceso Coactivo </span></td>
        <td><?php $data = array('name' => 'id_proceso_coactivo', 'id' => 'id_proceso_coactivo',  'value'=>'3423', 'class' => 'validate[required]',    'required' => 'required', 'readonly' => 'readonly',);echo form_input($data);?>
        </td>    
    </tr>
    <tr>
        <td><span >Deudor Objeto?</span></td>
        <td><select name="honorarios" id="honorarios" onchange="verificar()">
                <option value="1">SI</option>
                <option value="2">NO</option>
            </select>
        </td>    
    </tr>
    <tr>
        <td colspan="2" style="text-align:justify" ><span style=" text-align: justify ">

            </span>
        </td>
    </tr>
    <tr>
        <td>
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
                'type' => 'button',
                'style' => 'margin-left:120px;',
                'content' => 'Continuar',
                'class' => 'btn btn-success',
            );
            echo form_button($data);
            ?>
        </td>
    </tr>
</table>
<script>
    $(document).ready(function() {
        $('#salir').click(function() {
            $('#div_contenido').dialog('close');

        });
    });

$(document).ready(function() {
        $('#honorarios').onchange(function() {
          var op_honorarios = $("#honorarios").val();
          

        });
    });



</script>
<style>  
    #tabla1{
        text-align:center;

    }

</style>