<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<div class="center-form">

<?php     

echo form_open(current_url()); ?>
<?php echo $custom_error; ?>
<?php echo form_hidden('id',$result->IDCARGO) ?>
<h2>Editar cargo</h2>
<p>
<?php
 echo form_label('Nombre<span class="required">*</span>', 'nombre');
   $data = array(
              'name'        => 'nombre',
              'id'          => 'nombre',
              'value'       => $result->NOMBRECARGO,
              'maxlength'   => '128'
            );

   echo form_input($data);
   echo form_error('nombre','<div>','</div>');
?>
</p>
<p>
<?php
 echo form_label('Descripción<span class="required">*</span>', 'descripcion');
   $data = array(
              'name'        => 'descripcion',
              'id'          => 'descripcion',
              'value'       => $result->DESCRIPCIONCARGO,
              'maxlength'   => '128'
            );

   echo form_input($data);
   echo form_error('descripcion','<div>','</div>');
?>
</p>
<p>
<?php
 echo form_label('Estado<span class="required">*</span>', 'estado_id');                               
      foreach($estados as $row) {
          $options[$row->IDESTADO] = $row->NOMBREESTADO;
       }
  echo form_dropdown('estado_id', $options,$result->IDESTADO,'id="estado" class="chosen"');
 
   echo form_error('estado_id','<div>','</div>');
?>
</p>
<p>     
        
        <?php  echo anchor('cargos', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>
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
        if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('cargos/delete'))
               {
                echo anchor('#', '<i class="fa fa-trash-o fa-lg"></i> Eliminar', 'class="btn btn-danger" id="borrar"'); 
               }
        ?>
        
</p>

<?php echo form_close(); ?>
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
                    location.href='<?php echo base_url()."index.php/cargos/delete/".$result->IDCARGO; ?>';
                    
                },
                Cancelar: function() {
                    $( this ).dialog( "close" );
                }
            }
        });
       });
    });
    </script>

<div id="dialog-confirm" title="¿Eliminar el cargo?" style="display:none;">
    <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>¿Confirma que desea eliminar el cargo "<?php echo $result->DESCRIPCIONCARGO; ?>"?</p>
</div>