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
<h4 style="text-align:center;">Revisar Pruebas Registradas</h4><br>
<table id="tabla1" style="width:auto; ">
    <tr><td colspan="2" style="text-align:center"><span style="color:red" style="text-align: center;">PROCESO COACTIVO</span>
            <span style="margin-left:10px;">
                <?php
                $data = array('name' => 'id_proceso_coactivo', 'id' => 'id_proceso_coactivo', 'class' => 'validate[required]', 'maxlength' => '15',
                    'required' => 'required', 'readonly' => 'readonly', 'value' => '343223');
                echo form_input($data);
                ?></span>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="text-align: left;" ><br><br> Observaciones<br>
            <?php
            $datadesc = array('name' => 'observaciones', 'id' => 'observaciones', 'required' => 'required', 'rows' => '4',
                'cols' => '80', 'style' => 'margin: 0px 0px 10px; width:600px; height: 70px;');
            echo form_textarea($datadesc);
            ?>
        </td>
    </tr>
    <tr>
        <td>


        </td>    
    </tr>
</table>


<div id="objeto_no" style="display:block">

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

</div>
</table>
<script>
    $(document).ready(function() {
        $('#salir').click(function() {
            $('#div_contenido').dialog('close');
        });
    });
</script>
<style>
    .hide-close .ui-id-2 { display: none }

</style>