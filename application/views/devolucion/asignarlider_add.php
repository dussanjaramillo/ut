<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div style="background: #f0f0f0; width: 700px; margin: auto; overflow: hidden">
    <?php echo form_open("devolucion/Agregar_AsignacionLider"); ?>
    <?php echo $custom_error; 
    echo form_hidden('cod_devolucion', $cod_devolucion);
    ?>
    <br>
    <div class="text-center" style="overflow: hidden; width: 70%; margin: 0 auto"> 
        <h2>Asignar Lider de Devolucion</h2>
        <br>

        <div style="overflow: hidden; width: 90%; margin: 0 auto"> 
            <?php
            echo form_label('<b>Lideres de Devoluciones<span class="required"></span></b>', 'id_lider');
            if (!empty($lideres)) {
                foreach ($lideres as $row) {
                    $select[$row->IDUSUARIO] = $row->NOMBRES . ' ' . $row->APELLIDOS;
                }
                echo form_dropdown('id_lider', $select, '', 'id="id_lider" class="span3" placeholder="seleccione..." ');
            } else {
                echo form_label('No existen lideres de Devouciones');
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
        ?> <?php echo anchor('devolucion/Menu_GestionDevoluciones', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?> 
    </div>

</div>
<?php echo form_close(); ?>
 