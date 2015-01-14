<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<div class="center-form">

<?php     

echo form_open(current_url()); ?>
<?php echo $custom_error; ?>
<?php echo form_hidden('id',$result->IDTASA) ?>
<h2>Editar Cartera</h2>
<p>
        <?php
         echo form_label('Tipo Tasa Histórico <span class="required">*</span>', 'valortasa');
           $dataid = array(
                      'name'        => 'valortasa',
                      'id'          => 'valortasa',
                      'value'       => $result->IDTASA,
                      'maxlength'   => '128',
                      'required'    => 'required'
                    );

           echo form_input($dataid);
           echo form_error('valortasa','<div>','</div>');
        ?>
        </p>
        <p>
        <?php
         echo form_label('Tipo de Tasa <span class="required">*</span>', 'tipo');
            foreach($estados as $row) {
            $tipot[$row->IDESTADO] = $row->NOMBREESTADO;
                             }

            echo form_dropdown('tipo', $tipot,$result->IDESTADO,'id="tipo" class="chosen"');
                                           
            echo form_error('tipo','<div>','</div>');
                          
        ?>
        </p>
        
        <?php  echo anchor('intereshistorico', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>
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
         if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('intereshistorico/delete'))
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
                    location.href='<?php echo base_url()."index.php/intereshistorico/delete/".$result->IDTASA; ?>';
                    
                },
                Cancelar: function() {
                    $( this ).dialog( "close" );
                }
            }
        });
       });
    });
    </script>

<div id="dialog-confirm" title="¿Eliminar el tipo de tasa Histórico?" style="display:none;">
    <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>¿Confirma que desea eliminar el tipo de tasa Histórico "<?php echo $result->NOMBRECONCEPTO; ?>"?</p>
</div>