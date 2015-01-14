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
"sAjaxSource": "<?php echo base_url(); ?>index.php/admestructuras/datatablecamposidentificacion/<?php echo $this->uri->segment(3); ?>",
"sServerMethod": "POST",
"aoColumns": [ 
                      { "sClass": "center" }, /*id 0*/
                      { "sClass": "item" },
                      { "sClass": "center" },
                      { "sClass": "item" },
                      { "sClass": "center" },
                      { "sClass": "center" },
                      { "sClass": "center" },
                      { "sClass": "center" },
                      { "bSearchable": false, "bVisible": false },
                      { "sClass": "center","bSortable": false,"bSearchable": false,"sWidth": "4%" },
                      

                    
                      ],

} );


} );
</script> 
<h1>Archivos - Campos</h1>
<?php     

echo form_open(current_url()); ?>
<?php echo form_hidden('idarchivo',$result->COD_ARCHIVO) ?>

<div align="center">

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
                              'maxlength'   => '10',
                              'readOnly'    => 'readOnly',
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
                            echo form_dropdown('estado_id', $select,$result->COD_ESTADO,'id="estado"  disabled="disabled" data-placeholder="seleccione..." ');
                         
                            echo form_error('estado_id','<div>','</div>');
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
     <th>Nombre Campo</th>
     <th>Posición</th>
     <th>Descripción</th>
     <th>Longitud</th>
     <th>Caracter Relleno</th>
     <th>Formato</th>
     <th>Constante</th>
     <th>num</th>
     <th>Campo Identificación</th>
   </tr>
 </thead>
 <tbody></tbody>     
</table>
<br>
<p>
    <div align="center">
                
                
                <?php  echo anchor('admestructuras', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>

                <?php 
                echo anchor(base_url().'index.php/admestructuras/addcampo/'.$this->uri->segment(3).'','<i class="icon-star"></i> Agregar Campo','class="btn btn-success"');
                ?>

                <?php echo form_close(); ?>

      </div>
  </p>