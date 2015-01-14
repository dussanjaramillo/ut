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
            <h1>Agregar Rango Tasa Interés Histórica</h1>
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

                            echo form_dropdown('tipot', $tipo,$result->IDESTADO,'id="tipot" disabled="disabled" class="chosen"');
                                           
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
        
        <?php echo $custom_error; ?>

        

        <table align="center">
          <tr>
            <td>
              <p>
                <?php
         echo form_label('Vigencia Desde<span class="required">*</span>', 'fechadesde');
         ?>
        <div class="input-append date" id="fechadesde" data-date="dateValue: Customer.DateOfBirth" data-date-format="dd/mm/yyyy">
        <input class="span2" size="16" name="vigenciadesde"  id="vigenciadesde" type="text"  value="<?php echo date("d/m/Y"); ?>"  />
        <span class="add-on"><i class="icon-calendar"></i></span>
        </div> 
        </p>
         <p>
          <?php
         echo form_label('Vigencia Hasta<span class="required">*</span>', 'fechahasta');
         ?>
        <div class="input-append date" id="fechahasta" data-date="dateValue: Customer.DateOfBirth" data-date-format="dd/mm/yyyy">
        <input class="span2" size="16" name="vigenciahasta"  id="vigenciahasta" type="text" value="<?php echo date("d/m/Y"); ?>"   />
        <span class="add-on"><i class="icon-calendar"></i></span>
        </div> 
        </p>

        <p>
        <?php
         echo form_label('Valor Tasa <span class="required">*</span>', 'tasarango');
           $tasarangot = array(
                      'name'        => 'tasarango',
                      'id'          => 'tasarango',
                      'value'       => set_value('tasarango'),
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
            </td>
          </tr>
        </table>
          
                
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
   