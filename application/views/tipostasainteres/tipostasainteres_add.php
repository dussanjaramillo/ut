<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<div class="center-form">
        <?php     

        echo form_open(current_url()); ?>
        <?php echo $custom_error; ?>
        <h2>Agregar Tipo Tasa Interés</h2>
        
        <p>
        <?php
         echo form_label('Nombre Tipo de Tasa<span class="required">*</span>', 'nombretasa');
           $dataid = array(
                      'name'        => 'nombretasa',
                      'id'          => 'nombretasa',
                      'value'       => set_value('nombretasa'),
                      'maxlength'   => '128'
                    );

           echo form_input($dataid);
           echo form_error('nombretasa','<div>','</div>');
        ?>
        </p>
         <p>
        <?php
         echo form_label('Tipo<span class="required">*</span>', 'tipo');
         $tipot=array(
                    "EFECTIVA" => "EFECTIVA",
                    "NOMINAL" => "NOMINAL");

          echo form_dropdown('tipo', $tipot,'','id="tipo" class="chosen" data-placeholder="seleccione..." ');
                         
          echo form_error('tipo','<div>','</div>');
                          
        ?>
        </p>
        
        <p>
        <?php
         echo form_label('Tipo de periodo que la tasa remunera<span class="required">*</span>', 'tasaremunera');
         
         foreach($periodotasa as $row) {
                  $tasaremunerat[$row->COD_TIPOPERIODOTASA] = $row->NOMBRE_TIPOPERIODO;
               }

          echo form_dropdown('tasaremunera', $tasaremunerat,'','id="tasaremunera" class="chosen" data-placeholder="seleccione..." ');
                         
          echo form_error('tasaremunera','<div>','</div>');
                          
        ?>
        </p>

        <p>
        <?php
         echo form_label('Periodo de liquidación de intereses<span class="required">*</span>', 'liquidainteres');
         
         foreach($periodoliquidacion as $row) {
                  $liquidainterest[$row->COD_PERIDOLIQUIDACION] = $row->NOMBRE_PERIODOLIQUIDACION;
               }

          echo form_dropdown('liquidainteres', $liquidainterest,'','id="liquidainteres" class="chosen" data-placeholder="seleccione..." ');
                         
          echo form_error('liquidainteres','<div>','</div>');
                          
        ?>
        </p>

        <p>
        <?php
         echo form_label('Forma de Pago de Intereses<span class="required">*</span>', 'pagointeres');
         
         foreach($pagointeres as $row) {
                  $pagointerest[$row->COD_FORMA_PAGO_INTERESES] = $row->NOMBRE_FORMAPAGO;
               }

          echo form_dropdown('pagointeres', $pagointerest,'','id="pagointeres" class="chosen" data-placeholder="seleccione..." ');
                         
          echo form_error('pagointeres','<div>','</div>');
                          
        ?>
        </p>
        
        <p>
                <?php  echo anchor('tipostasainteres', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>
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
   