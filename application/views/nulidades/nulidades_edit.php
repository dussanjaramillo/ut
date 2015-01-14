<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<div class="center-form">

<?php     

echo form_open(current_url()); ?>
<?php echo $custom_error; ?>
<?php echo form_hidden('id',$result->COD_CAUSAL_NULIDAD) ?>
<h2>Editar Concepto</h2>
<p>
        <?php
         echo form_label('Nombre Causal<span class="required">*</span>', 'nombreconcepto');
           $dataid = array(
                      'name'        => 'nombreconcepto',
                      'id'          => 'nombreconcepto',
                      'value'       => $result->NOMBRE_CAUSAL,
                      'maxlength'   => '100',
                      'required'    => 'required',
                      'class'       => 'span4'
                    );

           echo form_input($dataid);
           echo form_error('nombreconcepto','<div>','</div>');
        ?>
        </p>
<p>
<?php
 echo form_label('Estado<span class="required">*</span>', 'estado_id');                               
      foreach($estados as $row) {
          $options[$row->IDESTADO] = $row->NOMBREESTADO;
       }
  echo form_dropdown('estado_id', $options,$result->COD_ESTADO,'id="estado" class="chosen"');
 
   echo form_error('estado_id','<div>','</div>');
?>
</p>
<br>
<p>     
        
        <?php  echo anchor('nulidades', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>
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
         if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('nulidades/delete'))
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
                    location.href='<?php echo base_url()."index.php/nulidades/delete/".$result->COD_CAUSAL_NULIDAD; ?>';
                    
                },
                Cancelar: function() {
                    $( this ).dialog( "close" );
                }
            }
        });
       });
    });
    </script>

<div id="dialog-confirm" title="¿Eliminar la Causal de Nulidad?" style="display:none;">
    <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>¿Confirma que desea eliminar la causal Nulidad "<?php echo $result->NOMBRE_CAUSAL; ?>"?</p>
</div>