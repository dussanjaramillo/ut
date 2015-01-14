<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<div class="center-form">

<?php     

echo form_open(current_url()); ?>
<?php echo $custom_error; ?>
<?php echo form_hidden('id',$result->COD_TIPO_TASAINTERES) ?>
<h2>Editar Cartera</h2>
<p>
        <?php
         echo form_label('Nombre Tipo de Tasa<span class="required">*</span>', 'nombretasa');
           $dataid = array(
                      'name'        => 'nombretasa',
                      'id'          => 'nombretasa',
                      'value'       => $result->NOMBRE_TASAINTERES,
                      'maxlength'   => '128',
                      'required'    => 'required',
                      'readOnly'    => 'readOnly'
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

          echo form_dropdown('tipo', $tipot,$result->TIPO,'id="tipo" class="chosen"');
                         
          echo form_error('tipo','<div>','</div>');
                          
        ?>
        </p>

        <p>
        <?php
         echo form_label('Tipo de periodo que la tasa remunera<span class="required">*</span>', 'tasaremunera');

         foreach($periodotasa as $row) {
                  $tasaremunerat[$row->COD_TIPOPERIODOTASA] = $row->NOMBRE_TIPOPERIODO;
               }

          echo form_dropdown('tasaremunera', $tasaremunerat,$result->COD_TIPOPERIODO,'id="tasaremunera" class="chosen"');
                         
          echo form_error('tasaremunera','<div>','</div>');
                          
        ?>
        </p>

        <p>
        <?php
         echo form_label('Periodo de liquidación de intereses<span class="required">*</span>', 'liquidainteres');

          foreach($periodoliquidacion as $row) {
                  $liquidainterest[$row->COD_PERIDOLIQUIDACION] = $row->NOMBRE_PERIODOLIQUIDACION;
               }

          echo form_dropdown('liquidainteres', $liquidainterest,$result->COD_FORMAPAGO_INTERESES,'id="liquidainteres" class="chosen"');
                         
          echo form_error('liquidainteres','<div>','</div>');
                          
        ?>
        </p>

        <p>
        <?php
         echo form_label('Forma de Pago de Intereses<span class="required">*</span>', 'pagointeres');

          foreach($pagointeres as $row) {
                  $pagointerest[$row->COD_FORMA_PAGO_INTERESES] = $row->NOMBRE_FORMAPAGO;
               }

          echo form_dropdown('pagointeres', $pagointerest,$result->COD_PER_LIQUIDACION,'id="pagointeres" class="chosen"');
                         
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
        <?php  
         if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('tipostasainteres/delete'))
           {
                echo anchor('#', '<i class="fa fa-trash-o fa-lg"></i> Eliminar', 'class="btn btn-danger" id="borrar"');
           }
        ?>
        
</p>

<?php echo form_close(); ?>

  <script type="text/javascript">
  //style selects
    var config = {
      '.chosen'           : {}
    }
    for (var selector in config) {
      $(selector).chosen(config[selector]);
    }

  </script>

<script>
    $(function() {
        // a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
    $("#borrar").click(function(evento){    
        evento.preventDefault();
         var link = $(this).attr('href');
        $( "#dialog:ui-dialog" ).dialog( "destroy" );
    
        $( "#dialog-confirm" ).dialog({
            resizable: false,
            height:180,
            modal: true,
            buttons: {
                "Confirmar": function() {
                    location.href='<?php echo base_url()."index.php/tipostasainteres/delete/".$result->COD_TIPO_TASAINTERES; ?>';
                    
                },
                Cancelar: function() {
                    $( this ).dialog( "close" );
                }
            }
        });
       });
    });
    </script>

<div id="dialog-confirm" title="¿Eliminar el tipo tasa interés?" style="display:none;">
    <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>¿Confirma que desea eliminar el tipo tasa interés "<?php echo $result->NOMBRE_TASAINTERES; ?>"?</p>
</div>