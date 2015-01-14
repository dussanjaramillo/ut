<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>


<?php     

echo form_open(current_url()); ?>
<?php echo $custom_error; ?>
<?php echo form_hidden('id',$result->COD_ESTRUCTURA) ?>

<div align="center">

          <h2>Editar Estructura</h2>
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
                          <td width="97"><?php echo form_label('Estructura: <span class="required">*</span>', 'estructura');?></td>
                          <td width="189">
                          <?php 
                            $data = array(
                              'name'        => 'estructura',
                              'id'          => 'estructura',
                              'value'       => $result->NOMBRE_ESTRUCTURA,
                              'maxlength'   => '10',
                              'readOnly'    => 'readOnly',
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
                            echo form_dropdown('estado_id', $select,$result->COD_ESTADO,'id="estado" class="chosen" data-placeholder="seleccione..." ');
                         
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

                              echo form_dropdown('origenda', $origend,$result->COD_ORGIENDATOS,'id="origenda" class="chosen" data-placeholder="seleccione..." ');
                           
                              echo form_error('origenda','<div>','</div>');
                            ?>

                          </td>
                          <td><?php echo form_label('Tipo: <span class="required">*</span>', 'tipo');?></td>
                          <td>
                            <?php 

                              foreach($tipoestructura as $row) {
                              $origent[$row->COD_TIPOESTRUCTURA] = $row->NOMBRE_TIPO;
                              }

                              echo form_dropdown('origendt', $origent,$result->COD_TIPOESTRUCTURA,'id="origendt" class="chosen" data-placeholder="seleccione..." ');
                           
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

                            echo form_dropdown('extensiont', $extensionest,$result->COD_EXTENSION,'id="extensiont" class="chosen" data-placeholder="seleccione..." ');
                         
                            echo form_error('extensiont','<div>','</div>');
                          ?>
                      </td>
                      <td width="120" colspan="1"><?php echo form_label('Tipo cartera no misional: <span class="required">*</span>', 'cart');?></td>
                      <td width="188" colspan="1">
                          <?php
                             foreach($tipocartera as $row) {
                              $tipocarterat[$row->COD_TIPOCARTERA] = $row->NOMBRE_CARTERA;
                              }
                            echo form_dropdown('tipocarterac', $tipocarterat,$result->COD_TIPOCARTERA,'id="tipocarterac" class="chosen" data-placeholder="seleccione..." ');
                         
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
                    location.href='<?php echo base_url()."index.php/admestructuras/delete/".$result->COD_ESTRUCTURA; ?>';
                    
                },
                Cancelar: function() {
                    $( this ).dialog( "close" );
                }
            }
        });
       });
    });
    </script>

<div id="dialog-confirm" title="¿Eliminar La Estructura?" style="display:none;">
    <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>¿Confirma que desea eliminar La Estructura "<?php echo $result->NOMBRE_ESTRUCTURA; ?>"?</p>
</div>