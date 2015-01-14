<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>

<div class="center">
        <?php     

        echo form_open(current_url()); ?>

        <h2>Agregar Estructura</h2>

        <?php echo $custom_error; ?>

        <?php echo form_hidden('fechacreacion',date("d/m/Y")) ?>

        <br>
        <br>
        <div>
          <table border="1" align="center">
            <tr>
              <td>

                  <table width="601" border="0" align="center">
                        <tr>
                          <td colspan="4">Datos generales</td>
                        </tr>
                        <tr>
                          <td width="97"><?php echo form_label('Estructura: <span class="required">*</span>', 'estructura');?></td>
                          <td width="189">
                          <?php 
                            $data = array(
                              'name'        => 'estructura',
                              'id'          => 'estructura',
                              'value'       => set_value('estructura'),
                              'maxlength'   => '50',
                            );

                             echo form_input($data);
                             
                             echo form_error('estructura','<div>','</div>');
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
                        <tr>
                          <td><?php echo form_label('Origen de Datos: <span class="required">*</span>', 'origen');?></td>
                          <td>
                            <?php 
                            foreach($origendatos as $row) {
                            $origend[$row->COD_ORIGENDATOS] = $row->NOMBRE_ORIGENDATOS;
                            }

                              echo form_dropdown('origenda', $origend,'','id="origenda" class="chosen" data-placeholder="seleccione..." ');
                           
                              echo form_error('origenda','<div>','</div>');
                            ?>

                          </td>
                          <td><?php echo form_label('Tipo: <span class="required">*</span>', 'tipo');?></td>
                          <td>
                            <?php 

                              foreach($tipoestructura as $row) {
                              $origent[$row->COD_TIPOESTRUCTURA] = $row->NOMBRE_TIPO;
                              }

                              echo form_dropdown('origendt', $origent,'','id="origendt" class="chosen" data-placeholder="seleccione..." ');
                           
                              echo form_error('origendt','<div>','</div>');
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

        <div align="center">
          <table border="1" align="center">
            <tr>
              <td>
                  <table width="100%" border="0" align="center">
                    <tr>
                      <td colspan="4">Extensiones</td>
                    </tr>
                    <tr>
                      <td width="120" colspan="1"><?php echo form_label('Seleccione: <span class="required">*</span>', 'sel');?></td>
                      <td width="188" colspan="1">
                          <?php 
                              foreach($extensiones as $row) {
                              $extensionest[$row->COD_EXTENSION] = $row->NOMBRE_EXTENSION;
                              }

                            echo form_dropdown('extensiont', $extensionest,'','id="extensiont" class="chosen" data-placeholder="seleccione..." ');
                         
                            echo form_error('extensiont','<div>','</div>');
                          ?>
                      </td>
                      <td width="120" colspan="1"><?php echo form_label('Tipo cartera no misional: <span class="required">*</span>', 'cart');?></td>
                      <td width="188" colspan="1">
                          <?php

                              $tipocarterat['0'] = 'Seleccione...';
                             foreach($tipocartera as $row) {
                              $tipocarterat[$row->COD_TIPOCARTERA] = $row->NOMBRE_CARTERA;
                              }
                            echo form_dropdown('tipocarterac', $tipocarterat,'','id="tipocarterac" class="chosen" data-placeholder="seleccione..." ');
                         
                            echo form_error('tipocarterac','<div>','</div>');
                          ?>
                      </td>
                    </tr>
                  </table>
              </td>
            </tr>
          </table>
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
   