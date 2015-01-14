<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<div class="center-form">

<?php     

echo form_open(current_url()); ?>
<?php echo $custom_error; ?>
<?php echo form_hidden('id',$result->IDAPLICACION) ?>
<h2>Editar aplicación</h2>
<p>
<?php
 echo form_label('Nombre<span class="required">*</span>', 'nombre');
   $data = array(
              'name'        => 'nombre',
              'id'          => 'nombre',
              'value'       => $result->NOMBREAPLICACION,
              'maxlength'   => '30'
            );

   echo form_input($data);
   echo form_error('nombre','<div>','</div>');
?>
</p>
<p>
<?php
 echo form_label('macroproceso<span class="required">*</span>', 'macroproceso_id');                               
      foreach($macroprocesos as $row) {
          $options1[$row->CODMACROPROCESO] = $row->NOMBREMACROPROCESO;
       }
  echo form_dropdown('macroproceso_id', $options1,$result->CODPROCESO,'id="macroproceso" class="chosen"');
 
   echo form_error('macroproceso_id','<div>','</div>');
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
<?php
 echo form_label('Ícono<span class="required">*</span>', 'icono');  
     ?> <select name="icono" id="icono" class="chosen" data-placeholder="seleccione..."> <?php
      foreach($iconos as $row) {
        if ($row->NOMBREICONO==$result->ICONOAPLICACION) {
        echo   $selected='selected';
        } else {
           $selected='';
        }
      echo '<option value="'.$row->NOMBREICONO.'" class="fa fa-'.$row->NOMBREICONO.' fa-lg" '.$selected.' > '.$row->TRADUCCION.'</option>';
       }
     ?> </select> <?php 
   echo form_error('icono','<div>','</div>');
?>

</p>
<p>     
        
        <?php  echo anchor('aplicaciones', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>
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
        <?php  echo anchor('#', '<i class="fa fa-trash-o fa-lg"></i> Eliminar', 'class="btn btn-danger" id="borrar"'); ?>
        
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
                    location.href='<?php echo base_url()."index.php/aplicaciones/delete/".$result->IDAPLICACION; ?>';
                    
                },
                Cancelar: function() {
                    $( this ).dialog( "close" );
                }
            }
        });
       });
    });
    </script>

<div id="dialog-confirm" title="¿Eliminar la aplicación?" style="display:none;">
    <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>¿Confirma que desea eliminar la aplicación "<?php echo $result->NOMBREAPLICACION; ?>"?</p>
</div>