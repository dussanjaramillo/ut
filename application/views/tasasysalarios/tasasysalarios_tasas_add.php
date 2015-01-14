<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<script type="text/javascript" language="javascript" charset="utf-8">
$(document).ready(function() {
  $('#fechadesde').datepicker();
  $('#fechahasta').datepicker();
  }
);
</script>
<div class="center-form">
        <?php     

        echo form_open(current_url()); ?>
        <?php echo $custom_error; ?>
        <h2>Actualizar Tasas</h2>
       
        <p>
        <?php
         echo form_label('Tasa<span class="required">*</span>', 'tasa');
         $data = array(
                    'name'        => 'tasa',
                    'id'          => 'tasa',
                    'value'       => set_value('tasa'),
                    'maxlength'   => '10',
                    'class'       =>  'input-mini'
                  );

         echo form_input($data);
         echo '<span class="add-on">%</span>';
         echo form_error('tasa','<div>','</div>');
        ?>

        </p>

        <p>
        <?php
         echo form_label('Tipo Tasa<span class="required">*</span>', 'tipotasa');
          foreach($tasas as $row) {
                  $selecc[$row->COD_TIPO_TASA] = $row->DESCRIPCION;
               }
          echo form_dropdown('tipotasa', $selecc,'','id="tipotasa" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('tipotasa','<div>','</div>');
        ?>
        </p>


         <p>
          <?php
         echo form_label('Vigencia Desde<span class="required">*</span>', 'fechadesde');
         ?>
        <div class="input-append date" id="fechadesde" data-date="dateValue: Customer.DateOfBirth" data-date-format="dd/mm/yyyy">
        <input class="span2" size="16" name="vigenciadesde"  id="vigenciadesde" type="text"  value="<?php echo date("d/m/Y"); ?>"  />
        <span class="add-on"><i class="icon-calendar"></i></span>
        </div> 
        </p>
         <p>
          <?php
         echo form_label('Vigencia Hasta<span class="required">*</span>', 'fechahasta');
         ?>
        <div class="input-append date" id="fechahasta" data-date="dateValue: Customer.DateOfBirth" data-date-format="dd/mm/yyyy">
        <input class="span2" size="16" name="vigenciahasta"  id="vigenciahasta" type="text" value="<?php echo date("d/m/Y"); ?>"   />
        <span class="add-on"><i class="icon-calendar"></i></span>
        </div> 
        </p>

        <p>
        <?php
         echo form_label('Fecha Creación<span class="required">*</span>', 'fechacreacion');
           $dataFecha = array(
                      'name'        => 'fechacreacion',
                      'id'          => 'fechacreacion',
                      'value'       => date("d/m/Y"),
                      'maxlength'   => '128',
                      'readonly'    => 'readonly'
                    );

           echo form_input($dataFecha);
           echo form_error('fechacreacion','<div>','</div>');
        ?>

        </p>

        <p>
        <?php
         echo form_label('Acuerdo/Resolución<span class="required">*</span>', 'acuerdores');
           $datanombre = array(
                      'name'        => 'acuerdores',
                      'id'          => 'acuerdores',
                      'value'       => set_value('acuerdores'),
                      'maxlength'   => '128',
                      'required'    => 'required'
                    );

           echo form_input($datanombre);
           echo form_error('acuerdores','<div>','</div>');
        ?>
        </p>

        <p>
        <?php
         echo form_label('Estado<span class="required">*</span>', 'estado_id');  
              foreach($estados as $row) {
                  $select[$row->IDESTADO] = $row->NOMBREESTADO;
               }
          echo form_dropdown('estado_id', $select,'','id="estado" class="chosen" data-placeholder="seleccione..." ');
         
           echo form_error('estado_id','<div>','</div>');
        ?>
        </p>

        <p>
                <?php  echo anchor('tasasysalarios', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>
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
   