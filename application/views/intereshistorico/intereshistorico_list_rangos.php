<?php 
if (isset($message)){
    echo $message;
   }
?>
<script type="text/javascript" language="javascript" charset="utf-8">
//generación de la tabla mediante json
$(document).ready(function() {

$('#tablaq').dataTable( {
"bProcessing": true,
"bServerSide": true,
"sPaginationType": "full_numbers",
"sAjaxSource": "<?php echo base_url(); ?>index.php/intereshistorico/datatablerangos/<?php echo $this->uri->segment(3); ?>",
"sServerMethod": "POST",
"aoColumns": [ 
                      { "sClass": "center" }, /*id 0*/
                      { "sClass": "item" },
                      { "sClass": "center" }, 
                      { "bSearchable": false, "bVisible": false },
                      { "sClass": "center","bSortable": false,"bSearchable": false,"sWidth": "4%" }

                    
                      ],

} );


} );
</script> 
<h1>Interés Histórico</h1>
<?php
 if ($this->ion_auth->is_admin() || $this->ion_auth->in_menu('intereshistorico/addrango'))
    {
      echo anchor(base_url().'index.php/intereshistorico/addrango/'.$this->uri->segment(3).'','<i class="icon-star"></i> Crear','class="btn btn-large  btn-primary"');
    }
?>
<br><br>

<div class="center">
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

<br><br>        
<table id="tablaq">
 <thead>
    <tr>
     <th>Id</th>
     <th>Tipo Tasa Histórica</th>
     <th>Tipo de Tasa</th>
     <th>num</th>
     <th>Editar Rango</th>
   </tr>
 </thead>
 <tbody></tbody>     
</table>

