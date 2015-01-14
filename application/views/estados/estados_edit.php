<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<div class="center-form">

<?php     

echo form_open(current_url()); ?>
<?php echo $custom_error; ?>
<?php echo form_hidden('id',$result->COD_TIPOESTADO) ?>
<h2>Editar Estado</h2>
<p>
<?php
 echo form_label('Nombre Estado<span class="required">*</span>', 'nombreestado');
   $data = array(
              'name'        => 'nombreestado',
              'id'          => 'nombreestado',
              'value'       => $result->NOMBRE_ESTADO,
              'maxlength'   => '100',
              'class'       => 'span4',
              'required'    => 'required'
            );

   echo form_input($data);
   echo form_error('nombre','<div>','</div>');
?>
</p>
<p>
<?php
 echo form_label('Estado<span class="required">*</span>', 'tipoestado');                               
      foreach($estadost as $row) {
          $options[$row->IDESTADO_P] = $row->TIPOESTADO_P;
       }
  echo form_dropdown('tipoestado', $options,$result->IDESTADO_P,'id="tipoestado" class="chosen"');
 
   echo form_error('tipoestado','<div>','</div>');
?>
</p>
<p>
<?php
 echo form_label('Estado<span class="required">*</span>', 'estado_id');                               
      foreach($estados as $row) {
          $options0[$row->IDESTADO] = $row->NOMBREESTADO;
       }
  echo form_dropdown('estado_id', $options0,$result->IDESTADO,'id="estado_id" class="chosen"');
 
   echo form_error('estado_id','<div>','</div>');
?>
</p>
<p>
<?php
         echo form_label('Descripción<span class="required">*</span>', 'descripcion');
           $datadesc = array(
                      'name'        => 'descripcion',
                      'id'          => 'descripcion',
                      'value'       => $result->DESCRIPCION_ESTADO,
                      'maxlength'   => '200',
                      'required'    => 'required',
                      'rows'        => '3',
                      'class'       => 'span3'
                    );

           echo form_textarea($datadesc);
           echo form_error('descripcion','<div>','</div>');
        ?>
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
        <?php  if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('estados/delete'))
               {echo anchor('#', '<i class="fa fa-trash-o fa-lg"></i> Eliminar', 'class="btn btn-danger" id="borrar"');
               } ?>
        
</p>

<?php echo form_close(); ?>

</div>
<!--// |:::::Deshabilitar el resize de los text_area -->
<style type="text/css">
  textarea{ resize:none }
</style>
  
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
                    location.href='<?php echo base_url()."index.php/estados/delete/".$result->COD_TIPOESTADO; ?>';
                    
                },
                Cancelar: function() {
                    $( this ).dialog( "close" );
                }
            }
        });
       });
    });
    </script>

<div id="dialog-confirm" title="¿Eliminar el Estado?" style="display:none;">
    <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>¿Confirma que desea eliminar el Estado "<?php echo $result->NOMBRE_ESTADO; ?>"?</p>
</div>