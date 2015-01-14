<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
if (isset($message)) {
    echo $message;
}
?>
<br>
<h2 align="center">Adicionar Cuenta Bancaria</h2>
<br>
<div style="background: none repeat scroll 0 0 #f0f0f0; padding: 55px; margin: auto; text-align: center;">
    <?php echo form_open(current_url()); ?>
    <?php echo $custom_error; ?>
    <div style="float: left; padding-right: 20px; padding-bottom: 40px; width: 30%;">
        <?php
        echo form_label('NUMERO DE CUENTA<span class="required">*</span>', 'cuenta');
        $data = array(
            'name' => 'cuenta',
            'id' => 'cuenta',
            'value' => set_value('cuenta'),
            'maxlength' => '30',
            'required' => 'required'
        );
        echo form_input($data);
        echo form_error('cuenta', '<div>', '</div>');
        ?>
    </div>
    <div style="float: left; padding-right: 20px; width: 30%;">
        <?php
        echo form_label('ENTIDAD BANCARIA<span class="required">*</span>', 'banco');
        $select3 = array();
        $select3[''] = "... Seleccione ...";
        foreach ($bancos->result_array as $row) {
            $select3[$row['IDBANCO']] = $row['NOMBREBANCO'];
        }
        echo form_dropdown('banco', $select3, '', 'id="banco" class="chosen" data-placeholder="seleccione..." ');
        echo form_error('banco', '<div>', '</div>');
        ?>
    </div>
    <div style="float: left; width: 30%;">
        <?php
        echo form_label('DESCRIPCION DE LA CUENTA<span class="required">*</span>', 'descripcion');
        $data = array(
            'name' => 'descripcion',
            'id' => 'descripcion',
            'value' => set_value('descripcion'),
            'maxlength' => '50',
            'required' => 'required'
        );

        echo form_input($data);
        echo form_error('cuenta', '<div>', '</div>');
        ?>
    </div>
</div>

<div style="text-align: center; width: 100%; padding-top: 50px;">
    <p>
        <?php echo anchor('siif/cuentasbancarias', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>
        <?php
        $data = array(
            'name' => 'button',
            'id' => 'submit-button',
            'value' => 'Guardar',
            'type' => 'submit',
            'content' => '<i class="fa fa-floppy-o fa-lg"></i> Guardar',
            'class' => 'btn btn-success',
        );

        echo form_button($data);
        ?>
    </p>
</div>
<?php echo form_close(); ?>