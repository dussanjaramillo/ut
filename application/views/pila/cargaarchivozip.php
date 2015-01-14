<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<div class="center-form-large">
        <?php     

       echo form_open_multipart("pila/archivocargado"); 
       ?>
        <h2>Cargar Pila</h2>
        <p>
        <?php
         echo form_label('Cargar archivos <span class="required">*</span>', 'archivo');
        ?>
         <input type="file" name="uploads[]" multiple>
        </p>
        <p>
                <?php  echo anchor('pila/consultarempresa', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>
                <?php 
                $data = array(
                       'name' => 'button',
                       'id' => 'submit-button',
                       'value' => 2097152,
                       'type' => 'submit',
                       'content' => '<i class="fa fa-cloud-upload fa-lg"></i> Cargar',
                       'class' => 'btn btn-success'
                       );

                echo form_button($data);    
                ?>
            <?php echo form_error('archivo','<div>','</div>'); ?>    
        </p>
        <?php echo form_close(); ?>
</div>
