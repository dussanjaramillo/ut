<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div style="background: #f0f0f0; width: 700px; margin: auto; overflow: hidden">
    <?php echo form_open("procesojudicial/Agregar_Asignacion_Abogado"); ?>
    <?php echo $custom_error; ?>
    <?php
    echo form_hidden('cod_titulo', $cod_titulo);
    $fecha_hoy = date("Y/m/d H:i:s", strtotime("-6 hour"));
    echo form_hidden('tb_fecha', $fecha_hoy);
    ?>
    <br>
    <div class="text-center" style="overflow: hidden; width: 60%; margin: 0 auto"> 
        <h2>Asignar Abogado</h2>
        <br>

        <div style="overflow: hidden; width: 80%; margin: 0 auto"> 
            <?php
            echo form_label('<b>Abogados Procesos Judiciales<span class="required"></span></b>', 'id_abogado');
            if (!empty($abogados)) {
                foreach ($abogados as $row) {
                    $selectabo[$row->IDUSUARIO] = $row->NOMBRES . ' ' . $row->APELLIDOS;
                }
                echo form_dropdown('id_abogado', $selectabo, '', 'id="id_abogado" class="span3" placeholder="seleccione..." ');
            } else {
                echo form_label('No existen abogados judiciales');
            }
            ?>
        </div>

    </div>
    <p>     
    <div class="text-center">        
        <?php
        $data = array(
            'name' => 'button',
            'id' => 'submit-button',
            'value' => 'Guardar',
            'type' => 'submit',
            'content' => '<i class="fa fa-floppy-o fa-lg"></i> Confirmar',
            'class' => 'btn btn-success'
        );
        echo form_button($data);
        ?> <?php echo anchor('procesojudicial/Lista_Asignacion_Abogado', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?> 
    </div>

</div>
<?php echo form_close(); ?>
 