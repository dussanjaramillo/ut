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
"sAjaxSource": "<?php echo base_url(); ?>index.php/admestructuras/datatablearchivos/<?php echo $this->uri->segment(3); ?>",
"sServerMethod": "POST",
"aoColumns": [ 
                      { "sClass": "center" }, /*id 0*/
                      { "sClass": "item" },
                      { "sClass": "center" },
                      { "bSearchable": false, "bVisible": false },
                      { "sClass": "center","bSortable": false,"bSearchable": false,"sWidth": "4%" },
                      { "sClass": "center","bSortable": false,"bSearchable": false,"sWidth": "4%" },
                      { "sClass": "center","bSortable": false,"bSearchable": false,"sWidth": "4%" },


                    
                      ],

} );


} );
</script> 
<h1>Estructuras - Archivos</h1>
<?php     

echo form_open(current_url()); ?>
<?php echo $custom_error; ?>
<?php echo form_hidden('id',$result->COD_ESTRUCTURA) ?>

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
                            echo form_dropdown('estado_id', $select,$result->COD_ESTADO,'id="estado"  disabled="disabled" data-placeholder="seleccione..." ');
                         
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

                              echo form_dropdown('origenda', $origend,$result->COD_ORGIENDATOS,'id="origenda" disabled="disabled" data-placeholder="seleccione..." ');
                           
                              echo form_error('origenda','<div>','</div>');
                            ?>

                          </td>
                          <td><?php echo form_label('Tipo: <span class="required">*</span>', 'tipo');?></td>
                          <td>
                            <?php 

                              foreach($tipoestructura as $row) {
                              $origent[$row->COD_TIPOESTRUCTURA] = $row->NOMBRE_TIPO;
                              }

                              echo form_dropdown('origendt', $origent,$result->COD_TIPOESTRUCTURA,'id="origendt" disabled="disabled" data-placeholder="seleccione..." ');
                           
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

                            echo form_dropdown('extensiont', $extensionest,$result->COD_EXTENSION,'id="extensiont" disabled="disabled" data-placeholder="seleccione..." ');
                         
                            echo form_error('extensiont','<div>','</div>');
                          ?>
                      </td>
                      <td width="120" colspan="1"><?php echo form_label('Tipo cartera no misional: <span class="required">*</span>', 'cart');?></td>
                      <td width="188" colspan="1">
                          <?php
                             foreach($tipocartera as $row) {
                              $tipocarterat[$row->COD_TIPOCARTERA] = $row->NOMBRE_CARTERA;
                              }
                            echo form_dropdown('tipocarterac', $tipocarterat,$result->COD_TIPOCARTERA,'id="tipocarterac" disabled="disabled" data-placeholder="seleccione..." ');
                         
                            echo form_error('tipocarterac','<div>','</div>');
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
     <th>Archivos</th>
     <th>Estado</th>
     <th>num</th>
     <th>Editar Archivo</th>
     <th>Campos Identificación</th>
     <th>Campos Archivo</th>
   </tr>
 </thead>
 <tbody></tbody>     
</table>
<br>
<p>
    <div align="center">
                
                
                <?php  echo anchor('admestructuras', '<i class="icon-remove"></i> Cancelar', 'class="btn"'); ?>

                <?php 
                echo anchor(base_url().'index.php/admestructuras/addarchivo/'.$this->uri->segment(3).'','<i class="icon-star"></i> Crear Archivo','class="btn btn-success"');
                ?>

                <?php echo form_close(); ?>

      </div>
  </p>