<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<div class="center-form">

<?php     

echo form_open(current_url()); ?>
<?php echo $custom_error; ?>
<?php echo form_hidden('id',$result->IDMODULO) ?>
<h2>Editar módulo</h2>
<p>
<?php
 echo form_label('Nombre<span class="required">*</span>', 'nombre');
   $data = array(
              'name'        => 'nombre',
              'id'          => 'nombre',
              'value'       => $result->NOMBREMODULO,
              'maxlength'   => '30'
            );

   echo form_input($data);
   echo form_error('nombre','<div>','</div>');
?>
</p>
<p>
<?php
 echo form_label('Controlador<span class="required">*</span>', 'url');
   $data = array(
              'name'        => 'url',
              'id'          => 'url',
              'value'       => $result->URL,
              'maxlength'   => '30',
              'class'   => 'span3'
            );

   echo form_input($data);
   echo form_error('url','<div>','</div>');
?>
</p>
<p>
<?php
 echo form_label('Aplicación<span class="required">*</span>', 'aplicacion_id');                               
      foreach($aplicaciones as $row) {
          $options[$row->IDAPLICACION] = $row->NOMBREAPLICACION;
       }
  echo form_dropdown('aplicacion_id', $options,$result->IDAPLICACION,'id="aplicacion" class="chosen"');
 
   echo form_error('aplicacion_id','<div>','</div>');
?>
</p>
<p>
<?php
 echo form_label('Estado<span class="required">*</span>', 'estado_id');                               
      foreach($estados as $row) {
          $options2[$row->IDESTADO] = $row->NOMBREESTADO;
       }
  echo form_dropdown('estado_id', $options2,$result->IDESTADO,'id="estado" class="chosen"');
 
   echo form_error('estado_id','<div>','</div>');
?>
</p>
<?php
 echo form_label('Ícono<span class="required">*</span>', 'icono');  
     ?> <select name="icono" id="icono" class="chosen" data-placeholder="seleccione..."> <?php
      foreach($iconos as $row) {
        if ($row->NOMBREICONO==$result->ICONOMODULO) {
        echo   $selected='selected';
        } else {
           $selected='';
        }
      echo '<option value="'.$row->NOMBREICONO.'" class="fa fa-'.$row->NOMBREICONO.' fa-lg" '.$selected.' > '.$row->TRADUCCION.'</option>';
       }
     ?> </select> <?php 
   echo form_error('icono','<div>','</div>');
?>
<p>     
        
        <?php  echo anchor('modulos', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>
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
      '.chosen'           : {disable_search_threshold: 10}
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
                    location.href='<?php echo base_url()."index.php/modulos/delete/".$result->IDMODULO; ?>';
                    
                },
                Cancelar: function() {
                    $( this ).dialog( "close" );
                }
            }
        });
       });
    });
    </script>

<div id="dialog-confirm" title="¿Eliminar el módulo?" style="display:none;">
    <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>¿Confirma que desea eliminar el módulo "<?php echo $result->NOMBREMODULO; ?>"?</p>
</div>