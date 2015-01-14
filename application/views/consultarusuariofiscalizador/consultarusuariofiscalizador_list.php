<?php 
if (isset($message)){
    echo $message;
   }
?>
<!--//:::::generación de la tabla mediante json-->
<script type="text/javascript" language="javascript" charset="utf-8">


$(document).ready(function() {

oTable = $('#tablaq').dataTable( {

"aoColumns": [ 
                      { "sClass": "center"}, /*id 0*/
                      { "sClass": "item" },
                      { "sClass": "center" },      
                      { "sClass": "center" },
                      { "sClass": "center" }, 
                      { "sClass": "center" },
                      { "sClass": "item" },
                      { "sClass": "item" },
                      { "sClass": "center" },
                      { "bSearchable": false,"bSortable": false,"sClass": "center" },
                      { "sClass": "center","bSortable": false,"bSearchable": false},

                    
                      ],
             "fnRowCallback":function( nRow, aData, iDataIndex ) {
              if ( aData[9] ==1 )
                {
                   $('td:eq(9)', nRow).html( '<a href="<?php echo base_url(); ?>index.php/consultarusuariofiscalizador/deactivate/'+aData[0]+'" class="btn btn-small" title="Desactivar"><i class="fa fa-unlock" style="color:green"></i> </a>' );  
                }else
                {
                  $('td:eq(9)', nRow).html( '<a href="<?php echo base_url(); ?>index.php/consultarusuariofiscalizador/activate/'+aData[0]+'" class="btn btn-small" title="Activar"><i class="fa fa-lock" style="color:red"></i></a>' );  
                }
             },                      
       

} );
//:::::Se inicializan los filtros para cada columna que se desee filtrar.
//:::::Documentacion acerca del plugin http://jquery-datatables-column-filter.googlecode.com/svn/trunk/index.html
//:::::File:        jquery.dataTables.columnFilter.js
//:::::Version:     1.5.3.
oTable.dataTable().columnFilter(

       {
          sPlaceHolder: "head:after",
          
            aoColumns: [
            
                              {sSelector: "#id_usuario"},
                              null,
                              null,
                              null,
                              null,
                              null,
                              null,
                              null,
                              {sSelector: "#regional_id", type: "select", values: [
                                <?php 
                                    foreach($regional as $key => $value) {
                                        echo '"'.$value->NOMBRE_REGIONAL.'"'.',';
                                    }
                                ?>
                              ]},
                              null,
                              null,
                              
            
           
                       ]
      }
);


   




} );

//::::: Estilo que evita el desbordamiento de la tabla.
</script> 
<style type="text/css">
table.dataTable {
  table-layout: fixed; 
  word-wrap:break-word;
}
</style>

<!--//:::::Formulario de identificacion de campos para el filtrado.-->
<h1>Consultar Usuarios Fiscalizadores</h1>
<div class="center-form">
              <h3>Filtros De Busqueda</h3>
              
              <div class="controls controls-row">
                <div class="span3">
                    ID Usuario
                    <p id="id_usuario" class = "filterOption"></p>
                </div>
                </div>
                
              
              

              <div class="controls controls-row">
              <div class="span3">
                  Regional
                  <p id="regional_id" class = "filterOption"></p>
              </div>
              </div>
              

              
                     
          
</div>
</div>




<!--//:::::Contenedor de la Tabla-->
<br><br>
<table id="tablaq" >
 <thead>
     <tr>
     
     <th>ID Usuario </th>
     <th>Nombres</th>
     <th>Apellidos</th>
     <th>Direccion</th>
     <th>Teléfono</th>
     <th>Celular</th>
     <th>Correo Empresarial</th>
     <th>Correo Personal</th>
     <th>Regional</th>
     <th>Estado</th>

     
     <th>Accíones</th>
     
   </tr>
 </thead>

 <tbody>
        <?php if (!empty($fiscalizadores)){ ?> 
        <?php foreach($fiscalizadores->result_array as $data){?>
        <tr>
           

            <td><?php echo $data['IDUSUARIO']  ?></td>
            <td><?php echo $data['NOMBRES']  ?></td>
            <td><?php echo $data['APELLIDOS']  ?></td>
            <td><?php echo $data['DIRECCION']  ?></td>
            <td><?php echo $data['TELEFONO']  ?></td>
            <td><?php echo $data['CELULAR']  ?></td>
            <td><?php echo $data['EMAIL']  ?></td>
            <td><?php echo $data['CORREO_PERSONAL']  ?></td>
            <td><?php echo $data['NOMBRE_REGIONAL']  ?></td>
            <td><?php echo $data['ACTIVO']  ?></td>
            <td><?php   if ($this->ion_auth->is_admin() || $this->session->userdata('regional') == 999 ){ ?> 
                              <div class="btn-toolbar">
                                <div class="btn-group">
                                  <a href="<?=base_url()?>index.php/consultarusuariofiscalizador/edit/<?php echo $data['IDUSUARIO']  ?>" class="btn btn-small" title="Editar"><i class="icon-edit"></i></a>
                                </div>
                              </div>
                
                <?php   } else {

                            if ($this->ion_auth->in_menu('consultarusuariofiscalizador/edit')) { ?>
                                <div class="btn-toolbar">
                                  <div class="btn-group">
                                    <a href="<?=base_url()?>index.php/consultarusuariofiscalizador/edit/<?php echo $data['IDUSUARIO']  ?>" class="btn btn-small" title="Editar"><i class="icon-edit"></i></a>
                                  </div>
                                </div> 
                <?php       } else { ?> 
                              <div class="btn-toolbar">
                                <div class="btn-group">
                                  <a href="#" class="btn btn-small" title="Editar"><i class="icon-edit"></i></a>
                                </div>
                              </div>
                <?php       } ?>
                <?php   } ?>   
            </td>
            
            
        </tr>
        <?php } ?>
        <?php } ?>
     </tbody>
<tfoot>
   
</tfoot>
</table>


