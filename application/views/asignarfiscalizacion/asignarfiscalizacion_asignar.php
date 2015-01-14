<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>

<div class="center-form-large">

<?php     

echo form_open(current_url()); ?>
<?php echo $custom_error; ?>
<?php echo form_hidden('id',$result->CODEMPRESA) ?>
<h2>Asignar Manualmente Una Fiscalizacion</h2>
<div class="controls controls-row">
        <div class="span3">
        <?php     

           echo form_label('NIT<span class="required">*</span>', 'nit');
           $data = array(
                      'name'        => 'nit',
                      'id'          => 'nit',
                      'value'       => $result->CODEMPRESA,
                      'maxlength'   => '12',
                      'class'       => 'span3',
                      'readonly'    => 'readonly'
                    );

           echo form_input($data);
           echo form_error('nit','<div style="color: red">','</div>');
        ?>
        </div>
        <div class="span3">
        <?php     

           echo form_label('Razón Social<span class="required">*</span>', 'razonsocial');
           $data = array(
                      'name'        => 'razonsocial',
                      'id'          => 'razonsocial',
                      'value'       => $result->RAZON_SOCIAL,
                      'maxlength'   => '255',
                      'class'       => 'span3',
                      'readonly'    => 'readonly'
                    );

           echo form_input($data);
           echo form_error('razonsocial','<div style="color: red">','</div>');
        ?>
        </div>
        </div>
        <div class="controls controls-row">
        <div class="span3">
         <?php
         echo form_label('Regional Actual<span class="required">*</span>', 'regional_a');  
              foreach($regional as $row) {
                  $selectr[$row->COD_REGIONAL] = $row->NOMBRE_REGIONAL;
               }
          echo form_dropdown('regional_a', $selectr,$result->COD_REGIONAL,'id="regional_a" class="span3" disabled="disabled" data-placeholder="seleccione..." ');
         
           echo form_error('regional_a','<div style="color: red">','</div>');
        ?>
        </div>
        <div class="span3">
         <?php
         echo form_label('Ciudad Actual<span class="required">*</span>', 'ciudad_a');  
              foreach($ciudad as $row) {
                  $selectci[$row->CODMUNICIPIO] = $row->NOMBREMUNICIPIO;
               }
          echo form_dropdown('ciudad_a', $selectci,$result->COD_MUNICIPIO,'id="ciudad_a" class="span3" disabled="disabled" data-placeholder="seleccione..." ');
          
           echo form_error('ciudad_a','<div style="color: red">','</div>');
        ?>
        </div>
        </div>
         <div class="controls controls-row">
         
        <div class="span3">
        <?php
          echo form_label('Fiscalizador<span class="required">*</span>', 'fiscalizador');                               
          $selectf = array(
                                  '' => 'Seleccione...'
                              );
              foreach($fiscalizador as $row) {
                  $selectf[$row->IDUSUARIO] = $row->NOMBRES.' '.$row->APELLIDOS;
               }
          echo form_dropdown('fiscalizador', $selectf,set_value('fiscalizador'),'id="fiscalizador" class="span6 chosen" data-placeholder="seleccione..." ');
         
           echo form_error('fiscalizador','<div style="color: red">','</div>');
        ?>
        
        </div>
        </div>
        <div class="controls controls-row">
          <div class="span3">
           <?php     

           echo form_label('Observaciones<span class="required">*</span>', 'observaciones');
           $data = array(
                      'name'        => 'observaciones',
                      'id'          => 'observaciones',
                      'value'       => set_value('observaciones'),
                      'maxlength'   => '1000',
                      'rows'        => '8',
                      'class'       => 'span6',
                      'required'    => 'required',


                      
                    );

           echo form_textarea($data);
           echo form_error('observaciones','<div style="color: red">','</div>');
        ?>
      </div>
      </div>
      
        
        
        <br>
        <div class="controls controls-row"></div>
        <div class="controls controls-row">
        <p class="pull-right">        
        <?php  echo anchor('asignarfiscalizacion', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>
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
</div>

<?php echo form_close(); ?>


</div>

<!--// |:::::Deshabilitar el resize de los text_area -->
<style type="text/css">
  textarea{ resize:none }
</style>
  
  <!--// |:::::JS para los form_dropdown class="chosen" -->
  <script type="text/javascript">
  //style selects
    var config = {
      '.chosen'           : {}
    }
    for (var selector in config) {
      $(selector).chosen(config[selector]);
    }

  </script>

