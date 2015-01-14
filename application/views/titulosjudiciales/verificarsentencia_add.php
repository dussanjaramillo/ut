<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
if (isset($message)) {
    echo $message;
}
?>
<div style="background: #f0f0f0; width: 700px; margin: auto; overflow: hidden">
    <?php
    $attributes = array("id" => "myform");
    echo form_open_multipart("procesojudicial/Agregar_Verificar_Sentencia", $attributes);
    ?>       
    <?php
    $fecha_hoy = date("Y/m/d H:i:s", strtotime("-6 hour"));
    echo form_hidden('tb_fecha', $fecha_hoy);
    echo form_hidden('cod_titulo', $id_titulo);
    ?>
    <?php echo $custom_error; ?>
    <br>

    <div style="overflow: hidden; width: 90%; margin: 0 auto">
        <?php if ($class != "") { ?>
            <div class="alert alert-<?php echo $class ?>"></div>
        <?php } ?>


    </div>
    <div style="overflow: hidden; width: 80%; margin: 0 auto">
        <h2 class="text-center">Asistir audiencia de instrucción y fallo</h2>
    </div>
    <div style="overflow: hidden; width: 70%; margin: 0 auto">
        <br>     
        <div class="columna_derecha"> 
            <input type="radio" checked="checked"  id="opc_res" name="opc_res" value="no_sentencia" >Sentencia No favorable al SENA
        </div>
        <div class="columna_izquierda">            
            <input type="radio"  id="opc_res1" name="opc_res" value="sentencia">Sentencia favorable al SENA
        </div>
    </div>
    <br>
    <center>
        <?php
        echo '<div class="subir_documento" id="subir_documento">';
        echo form_label('<b>Documento Soporte de la Sentencia<span class="required"></span></b><br>', 'lb_cargar1');
        echo '<div class="alert-success" style="width: 71%; border-color: black; border: 1px solid grey;padding: 15px 50px 0">';
        $data = array(
            'multiple' => '',
            'name' => 'userfile[]',
            'id' => 'userfile',
            'class' => 'validate[required]'
        );
        echo form_upload($data);
        echo '<br><br>';
        echo '</div>';
        echo '<br>';
        echo '</div>';
        ?>
    </center>
    <div style="overflow: hidden; width: 35%; margin: 0 auto">
        <?php echo anchor('procesojudicial/Lista_Verificar_Sentencia', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>
        <?php
        $data = array(
            'name' => 'button',
            'id' => 'submit-button',
            'value' => 'Confirmar',
            'type' => 'submit',
            'content' => '<i class="fa fa-floppy-o fa-lg"></i> Confirmar',
            'class' => 'btn btn-success'
        );

        echo form_button($data);
        ?>
    </div>

    <?php echo form_close(); ?>
    <style type="text/css">
        .columna_derecha {
            float:right; /* Alineación a la derecha */
            width:50%;
            //border:solid lightblue 1px;

        }

        .columna_izquierda {
            float:left; /* Alineación a la izquierda */
            width:50%;
            // border:solid lightblue 1px;

        }       
    </style>