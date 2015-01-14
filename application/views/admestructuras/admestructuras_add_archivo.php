<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>

<div align="center">
        <?php     

        echo form_open(current_url()); ?>

        <h2>Agregar Estructura</h2>

        <?php echo $custom_error; ?>
        <?php echo form_hidden('idestructura',$result->COD_ESTRUCTURA) ?>
        <?php echo form_hidden('fechacreacion',date("d/m/Y")) ?>
        
        <br>
        <br>
        <div>
          <table border="0" align="center">
            <tr>
              <td>

                  <table width="100%" border="0" align="center">
                        <tr>
                          <td colspan"2"><?php echo form_label('Estructura: <span class="required">*</span>', 'estructura');?></td>
                          <td colspan"2">
                          <?php 
                            $data = array(
                              'name'        => 'estructura',
                              'id'          => 'estructura',
                              'value'       => $result->NOMBRE_ESTRUCTURA,
                              'maxlength'   => '10',
                              'readOnly'    => 'readOnly'
                            );

                             echo form_input($data);
                             
                             echo form_error('estructura','<div>','</div>');
                            ?>
                          </td>
                        </tr>
                        <tr>
                          <td width="97"><?php echo form_label('Archivo: <span class="required">*</span>', 'archivo');?></td>
                          <td width="189">
                          <?php 
                            $data = array(
                              'name'        => 'archivo',
                              'id'          => 'archivo',
                              'value'       => set_value('archivo'),
                              'maxlength'   => '50'
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
                            echo form_dropdown('estado_id', $select,'','id="estado" class="chosen" data-placeholder="seleccione..." ');
                         
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
        <p>
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
            
                
        </p>
       

        <?php echo form_close(); ?>

</div>
  <script type="text/javascript">
  //style selects
     function format(state) {
    if (!state.id) return state.text; // optgroup
         return "<i class='fa fa-home fa-fw fa-lg'></i>" + state.id.toLowerCase() + " " + state.text;
    }
    $(".chosen0").select2({
    formatResult: format,
    formatSelection: format,
    escapeMarkup: function(m) { return m; }
    });


    $(document).ready(function() { $(".chosen").select2();

     });

  </script>
   