<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>


<?php     

echo form_open(current_url()); ?>
<?php echo $custom_error; ?>
<?php echo form_hidden('id',$result->COD_ARCHIVO) ?>

<div align="center">

          <h2>Editar Archivo</h2>
          <br>
          <br>

          <table border="1" align="center">
            <tr>
              <td>

                  <table width="601" border="0" align="center">
                        <tr>
                          <td colspan="4">Datos generales</td>
                        </tr>
                        <tr>
                          <td width="97"><?php echo form_label('Archivo: <span class="required">*</span>', 'archivo');?></td>
                          <td width="189">
                          <?php 
                            $data = array(
                              'name'        => 'archivo',
                              'id'          => 'archivo',
                              'value'       => $result->NOMBRE_ARCHIVO,
                              'maxlength'   => '128',
                            );

                             echo form_input($data);
                             
                             echo form_error('archivo','<div>','</div>');
                            ?>
                          </td>
                          <td width="94"><?php echo form_label('Estado: <span class="required">*</span>', 'estado');?></td>
                          <td width="193">
                          <?php 
                            foreach($estados as $row) {
                            $select[$row->IDESTADO] = $row->NOMBREESTADO;
                            }
                            echo form_dropdown('estado_id', $select,$result->COD_ESTADO,'id="estado" class="chosen" data-placeholder="seleccione..." ');
                         
                            echo form_error('estado_id','<div>','</div>');
                        ?>
                        </td>
                        </tr>
                        
                    </table>
                  </td>
                </tr>
              </table>

        </div>

          <br>
          <br>

          <br>
          <br>
        <div align="center">
        <?php  echo anchor('admestructuras', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>
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
         if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('admestructuras/delete'))
           {
                echo anchor('#', '<i class="fa fa-trash-o fa-lg"></i> Eliminar', 'class="btn btn-danger" id="borrar"');
           }
        ?>
        </div>


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
                    location.href='<?php echo base_url()."index.php/admestructuras/deletearchivo/".$result->COD_ARCHIVO; ?>';
                    
                },
                Cancelar: function() {
                    $( this ).dialog( "close" );
                }
            }
        });
       });
    });
    </script>

<div id="dialog-confirm" title="¿Eliminar El Archivo?" style="display:none;">
    <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>¿Confirma que desea eliminar El Archivo "<?php echo $result->NOMBRE_ARCHIVO; ?>"?</p>
</div>