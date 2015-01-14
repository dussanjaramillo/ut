<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<div class="center-form">
        <?php     

        echo form_open(current_url()); ?>
        <?php echo $custom_error; ?>
        <h2>Agregar Cartera</h2>
        
        <p>
        <?php
         echo form_label('Nombre Cartera<span class="required">*</span>', 'nombrecartera');
           $dataid = array(
                      'name'        => 'nombrecartera',
                      'id'          => 'nombrecartera',
                      'value'       => set_value('nombrecartera'),
                      'maxlength'   => '128'
                    );

           echo form_input($dataid);
           echo form_error('nombrecartera','<div>','</div>');
        ?>
        </p>
         <p>
        <?php
         echo form_label('Tipo<span class="required">*</span>', 'tipo');
         $tipo=array(
                'No Misional' => 'No Misional');

          echo form_dropdown('tipot', $tipo,'','id="tipot" data-placeholder="seleccione..." ');
                         
          echo form_error('tipot','<div>','</div>');
                          
        ?>
        </p>
        
        <p>
        <?php
         echo form_label('Fecha Creacion<span class="required">*</span>', 'fechacreacion');
           $dataFecha = array(
                      'name'        => 'fechacreacion',
                      'id'          => 'fechacreacion',
                      'value'       => date("d/m/Y"),
                      'maxlength'   => '128',
                      'readOnly'    => 'readOnly'
                    );

           echo form_input($dataFecha);
           echo form_error('fechacreacion','<div>','</div>');
        ?>

        </p>
        <p><?php
         echo form_label('Estado<span class="required">*</span>', 'estado_id');  
              foreach($estados as $row) {
                  $select[$row->IDESTADO] = $row->NOMBREESTADO;
               }
          echo form_dropdown('estado_id', $select,'','id="estado" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('estado_id','<div>','</div>');
        ?>
      </spam>
        </p>
        
        <p>
                <?php  echo anchor('tiposcartera', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>
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
   