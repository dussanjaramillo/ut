<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<script type="text/javascript" language="javascript" charset="utf-8">
$(document).ready(function() {
  $('#fechadesde').datepicker();
  $('#fechahasta').datepicker();
  }
);
</script>
<?php     

        echo form_open(current_url()); 

        ?>   

        <div class="center">
            <h1>Editar Rango Tasa Interés Histórica</h1>
            <br>
            <br>
          <table border="0" align="center">
            <tr>
              <td>
                  <table width="100%" border="0" align="center">
                    
                    <tr>
                      <td colspan="1"><?php echo form_label('Tipo Tasa Histórica: <span class="required">*</span>', 'sel');?></td>
                      <td colspan="1">
                          <?php 
                            $dataid = array(
                                        'name'        => 'valortasa',
                                        'id'          => 'valortasa',
                                        'value'       => $result->VALORTASA,
                                        'maxlength'   => '128',
                                        'required'    => 'required',
                                        'readOnly'    => 'readOnly'
                                      );

                             echo form_input($dataid);
                             echo form_error('valortasa','<div>','</div>');
                          ?>
                      </td>
                      <td colspan="1"><?php echo form_label('Tipo de Tasa: <span class="required">*</span>', 'tipot');?></td>
                      <td colspan="1">
                          <?php 
                            foreach($estados as $row) {
                                $tipo[$row->IDESTADO] = $row->NOMBREESTADO;
                             }

                            echo form_dropdown('tipot', $tipo,$result->IDESTADO,'id="tipot" disabled="disabled"');
                                           
                            echo form_error('tipot','<div>','</div>');
                          ?>
                      </td>
                    </tr>
                  </table>
              </td>
            </tr>
          </table>
      </div>

<div class="center-form">

   <table border="0" align="center">
            <tr>
              <td>
                <?php echo $custom_error; ?>
                <?php echo form_hidden('idtasa',$result->IDTASA) ?>
                <?php echo form_hidden('idrango',$result->IDTASA) ?>

                        <p>
                                <?php
                         echo form_label('Vigencia Desde<span class="required">*</span>', 'fechadesde');
                         ?>
                        <div class="input-append date" id="fechadesde" data-date="dateValue: Customer.DateOfBirth" data-date-format="dd/mm/yyyy">
                        <input class="span2" size="16" name="vigenciadesde"  id="vigenciadesde" type="text"  value="<?php echo $result->IDESTADO; ?>"  />
                        <span class="add-on"><i class="icon-calendar"></i></span>
                        </div> 
                        </p>
                         <p>
                          <?php
                         echo form_label('Vigencia Hasta<span class="required">*</span>', 'fechahasta');
                         ?>
                        <div class="input-append date" id="fechahasta" data-date="dateValue: Customer.DateOfBirth" data-date-format="dd/mm/yyyy">
                        <input class="span2" size="16" name="vigenciahasta"  id="vigenciahasta" type="text" value="<?php echo $result->IDESTADO; ?>"   />
                        <span class="add-on"><i class="icon-calendar"></i></span>
                        </div> 
                        </p>

                        <p>
                        <?php
                         echo form_label('Valor Tasa <span class="required">*</span>', 'tasarango');
                           $tasarangot = array(
                                      'name'        => 'tasarango',
                                      'id'          => 'tasarango',
                                      'value'       => $result->IDESTADO,
                                      'maxlength'   => '128'
                                    );

                           echo form_input($tasarangot);
                           echo form_error('tasarango','<div>','</div>');
                        ?>
                        </p>
                        <p>
                        <?php  echo anchor('intereshistorico/rangos/'.$this->uri->segment(3).'', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>
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
                         if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('intereshistorico/deleterango'))
                           {
                                echo anchor('#', '<i class="fa fa-trash-o fa-lg"></i> Eliminar', 'class="btn btn-danger" id="borrar"');
                           }
                        ?>

                        
                      </p>
                      </td>
                      </tr>
                    </table>

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
                    location.href='<?php echo base_url()."index.php/intereshistorico/deleterango/".$result->IDTASA; ?>';
                    
                },
                Cancelar: function() {
                    $( this ).dialog( "close" );
                }
            }
        });
       });
    });
    </script>

<div id="dialog-confirm" title="¿Eliminar el rango de tasa Histórico?" style="display:none;">
    <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>¿Confirma que desea eliminar el rango de tasa Histórico "<?php echo $result->NOMBRECONCEPTO; ?>"?</p>
</div>