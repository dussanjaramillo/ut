<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<div class="center-form">

<?php     

echo form_open(current_url()); ?>
<?php echo $custom_error; ?>
<?php echo form_hidden('id',$result->COD_GESTION) ?>
<h2>Editar Gestión</h2>
<p>
        <?php
         echo form_label('Tipo Gestión<span class="required">*</span>', 'tipogestion');
           $dataid = array(
                      'name'        => 'tipogestion',
                      'id'          => 'tipogestion',
                      'value'       => $result->TIPOGESTION,
                      'maxlength'   => '100',
                      'class'       => 'span4',
                      'required'    => 'required'
                    );

           echo form_input($dataid);
           echo form_error('tipogestion','<div>','</div>');
        ?>
        </p>
<p>
<?php
 echo form_label('Tipo Proceso<span class="required">*</span>', 't_proceso');                               
      foreach($procesos as $row) {
          $options[$row->COD_TIPO_PROCESO] = $row->TIPO_PROCESO;
       }
  echo form_dropdown('t_proceso', $options,$result->CODPROCESO,'id="t_proceso" class="chosen span4"');
 
   echo form_error('t_proceso','<div>','</div>');
?>
</p>
 <br><br>
<p>     
        
        <?php  echo anchor('gestion', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>
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
        if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('gestion/delete'))
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
                    location.href='<?php echo base_url()."index.php/gestion/delete/".$result->IDGESTION; ?>';
                    
                },
                Cancelar: function() {
                    $( this ).dialog( "close" );
                }
            }
        });
       });
    });
    </script>

<div id="dialog-confirm" title="¿Eliminar la Gestión?" style="display:none;">
    <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>¿Confirma que desea eliminar la Gestión "<?php echo $result->TIPOGESTION; ?>"?</p>
</div>