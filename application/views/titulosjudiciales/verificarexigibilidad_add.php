<style type="text/css">

    .columna_derecha {
        float:right; /* Alineación a la derecha */
        width:610px;
        //border:solid lightblue 1px;

    }

    .columna_izquierda {
        float:left; /* Alineación a la izquierda */
        width:510px;
        //border:solid lightblue 1px;

    }
    .columna_central {
        margin-left:450px; /* Espacio para la columna izquierda */
        margin-right:350px; /* Espacio para la columna derecha */
        //border:solid navy 1px;

    } 


</style>
<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="center-form-large-20">

    <?php
    $atributos = array('class' => 'Form', 'id' => 'Form');
    echo form_open('procesojudicial/Agregar_Verificacion_Exigibilidad', $atributos);
    ?>
    <?php echo $custom_error; ?>
    <?php echo form_hidden('cod_titulo', $id_codigo); ?>
    <br><br>
    <h2>Verificar Exigibilidad</h2>
    <br>
    <div class="controls columna_izquierda">
        <?php
        $i = 1;
        foreach ($tipos_exigibilidad as $data) {
            $opciones[$i] = array(
                'name' => 'id_opcion' . $i,
                'id' => 'id_opcion' . $i,
                'value' => $data->COD_TIPOEXIGILIDAD,
                'checked' => FALSE,
                'style' => 'margin: 10px'
            );

            echo form_checkbox($opciones[$i], 'opcion' . $i);
            ?><?= $data->NOMBRE_TIPOEXIGIBILIDAD; ?><br>

            <?php
            $i++;
        }
        ?>
    </div>

    <div class="controls columna_derecha">
        <div class="span4">              
            <?php
            echo form_label('Observaciones<span class="required"></span>', 'lb_observacion');
            $dataobservaciones = array(
                'name' => 'ta_observacion',
                'id' => 'ta_observacion',
                'value' => set_value('ta_observaciones'),
                'maxlength' => '300',
                'class' => 'span5',
                'required' => 'required'
            );
            echo form_textarea($dataobservaciones);
            ?>
        </div>
    </div>
    <div class="controls columna_central">
        <div class="span5">
            <br>        
            <?php
            $data = array(
                'name' => 'button',
                'id' => 'enviar',
                'value' => 'Guardar',
                'content' => '<i class="fa fa-floppy-o fa-lg"></i>Confirmar',
                'class' => 'btn btn-success enviar'
            );

            echo form_button($data);
            ?>
            <?php echo anchor('procesojudicial/Lista_Verificar_Exigibilidad', '<i class="icon-remove"></i> Salir', 'class="btn"'); ?>
        </div>
    </div> 
</div>
<?php
$i--;
$fecha_hoy = date("Y/m/d H:i:s", strtotime("-6 hour"));
echo form_hidden('tb_fecha', $fecha_hoy);
echo form_hidden('tb_cantidad', $i);
?>
<?php echo form_close(); ?>
<script>
    $('.enviar').click(function() {
        var check = $("input[type='checkbox']:checked").length;
        var cantidad = <?php echo sizeof($tipos_exigibilidad); ?>;
        if (cantidad == check) {
            if (confirm("Se verificara el Titulo como Exigible ¿Desea Continuar?")) {
                $('#Form').submit();
            }
        } else {
            if (confirm("Se verificara el Titulo como no Exigible por no cumplir con todas las condiciones ¿Desea Continuar?")) {
                $('#Form').submit();
            }
        }
    });

</script> 
