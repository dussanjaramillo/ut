<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<div class="center-form">
        <?php     

        echo form_open(current_url()); ?>
        <?php echo $custom_error; ?>
        <h2>Nuevo Estado</h2>
        
        <p>
        <?php
         echo form_label('Nombre Estado<span class="required">*</span>', 'nombreestado');
           $dataid = array(
                      'name'        => 'nombreestado',
                      'id'          => 'nombreestado',
                      'value'       => set_value('nombreestado'),
                      'maxlength'   => '100',
                      'class'       => 'span4',
                      'required'    => 'required'
                    );

           echo form_input($dataid);
           echo form_error('nombreestado','<div>','</div>');
        ?>
        </p>
        <p><?php
         echo form_label('Tipo Estado<span class="required">*</span>', 'tipoestado');  
              foreach($estadost as $row) {
                  $select[$row->IDESTADO_P] = $row->TIPOESTADO_P;
               }
          echo form_dropdown('tipoestado', $select,set_value('tipoestado'),'id="tipoestado" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('tipoestado','<div>','</div>');
        ?>
        </p>
        
        <p>
        <?php
         echo form_label('Estado<span class="required">*</span>', 'estado_id');  
              foreach($estados as $row) {
                  $select0[$row->IDESTADO] = $row->NOMBREESTADO;
               }
          echo form_dropdown('estado_id', $select0,set_value('estado_id'),'id="estado_id" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('estado_id','<div>','</div>');
        ?>
        </p>
        <p>
        
        <?php
         echo form_label('Descripción<span class="required">*</span>', 'descripcion');
           $datadesc = array(
                      'name'        => 'descripcion',
                      'id'          => 'descripcion',
                      'value'       => set_value('descripcion'),
                      'maxlength'   => '200',
                      'required'    => 'required',
                      'rows'        => '3',
                      'class'       => 'span3'
                    );

           echo form_textarea($datadesc);
           echo form_error('descripcion','<div>','</div>');
        ?>
        </span>
        </p>
        <br>
        <p>
                <?php  echo anchor('estados', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>
                <?php 
                $data = array(
                       'name' => 'button',
                       'id' => 'submit-button',
                       'value' => 'Guardar',
                       'type' => 'submit',
                       'content' => '<i class="fa fa-floppy-o fa-lg"></i> Guardar',
                       'class' => 'btn btn-success'
                       );

                echo form_button($data);    
                ?>
                
        </p>

        <?php echo form_close(); ?>

</div>

<!--// |:::::Deshabilitar el resize de los text_area -->
<style type="text/css">
  textarea{ resize:none }
</style>
  

  <script type="text/javascript">
  //style selects
     function format(state) {
    if (!state.id) return state.text; // optgroup
         return "<i class='fa fa-home fa-fw fa-lg'></i>" + state.id.toLowerCase() + " " + state.text;
    }
    $(".chosen0").select2({
    formatResult: format,
    formatSelection: format,
    escapeMarkup: function(m) { return m; }
    });


    $(document).ready(function() { $(".chosen").select2();

     });

  



  </script>
   