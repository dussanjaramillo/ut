<?php if( ! defined('BASEPATH') ) exit('No direct script access allowed'); ?>
<?php 
if (isset($message)){
    echo $message;
   }
?>
<h1>Empresas Posibles Deudoras Sin Fiscalizar</h1>
<div class="center-form-large">

<?php echo form_open(current_url()); ?>
    <?php echo $custom_error; ?>
    <h2>Filtros De Asignación</h2>
    <div class="controls controls-row">
        <div class="span8">
            <?php
                echo form_label('CIIU<span class="required">*</span>', 'ciu');  
                
                foreach($ciu as $row) {
                
                    $chosen1[$row->CLASE] = $row->CLASE.' - '.$row->DESCRIPCION;
                }
                
                echo form_dropdown('ciu', $chosen1,set_value('ciu'),'id="ciu" class="chosen span8" data-placeholder="seleccione..." ');
                     
               
                echo form_error('ciu','<div>','</div>');
            ?>
        </div>
    </div>
    <div class="controls controls-row">
        <div class="span4">
            <?php
                echo form_label('Ciudad<span class="required">*</span>', 'ciudad');                          
                $chosen2 = array(0 => 'Seleccione...' );
                foreach($municipios as $row) {
                
                    $chosen2[$row->CODMUNICIPIO] = $row->NOMBREMUNICIPIO;
                }

                echo form_dropdown('ciudad', $chosen2,set_value('ciudad'),'id="ciudad" class="chosen span4" placeholder="seleccione..." ');

             
                echo form_error('ciudad','<div>','</div>');
            ?>
        </div>
        <div class="span4">
            <?php
                echo form_label('Tipo de empresa<span class="required">*</span>', 'tipoempresa_id');                               
                $chosen3 = array(0 => 'Seleccione...' );
                foreach($tiposempresa as $row) {
                    $chosen3[$row->CODTIPOEMPRESA] = $row->NOM_TIPO_EMP;
                }
                
                echo form_dropdown('tipoempresa_id', $chosen3,set_value('tipoempresa_id'),'id="tipoempresa_id" class="chosen span4" placeholder="seleccione..." ');

             
                echo form_error('tipoempresa_id','<div>','</div>');
            ?>
        </div>
    </div>
    
    <br><br>    
    <div class="controls controls-row" align="center">  

        <?php 
            $data = array(
                       'name' => 'buscar',
                       'id' => 'buscar',
                       'value' => 'Buscar',
                       'type' => 'button',
                       'content' => '<i class="fa fa-search"></i> Buscar',
                       'class' => 'btn btn-success',
                       'onclick' => 'buscarEvasoras()'
            );

            echo form_button($data);    
        ?>

        <?php 
            $data = array(
                       'name' => 'button',
                       'id' => 'submit-button',
                       'value' => 'Asignar',
                       'type' => 'submit',
                       'content' => '<i class="fa fa-plus"></i> Asignar',
                       'class' => 'btn btn-success'
            );

            echo form_button($data);    
        ?>
    </div>   
<?php echo form_close(); ?>
</div>

<!--//:::::Contenedor de la Tabla-->
<br><br>
    
<table id="tablaq">
        <thead>
           <tr>
            <th style="width: 10%;">NIT</th>
            <th>Razón Social</th>
            <th>Ciudad</th>
            <th>Empresa</th>
            <th>CIIU</th>
            
          </tr>
        </thead>
        <tbody ></tbody>     
</table>

<!--// |:::::SCRIPTS JS -->

<script type="text/javascript" language="javascript" charset="utf-8">
var oTable;
    function buscarEvasoras(){
       oTable.fnDraw();
    }
        
    oTable = $('#tablaq').dataTable( {
        
        "sServerMethod": "POST", 
        "bJQueryUI":    true,
        "bProcessing":  true,  
        "bServerSide":  true,
        "bPaginate" :   true ,
        "iDisplayStart" : 0,
        "iDisplayLength" : 10,
        "sSearch": '',
        "fnServerParams": function ( aoData ) {
            aoData.push( { "name": "ciu", "value": $('#ciu').val() } );
            aoData.push( { "name": "ciudad", "value": $('#ciudad').val() } );
            aoData.push( { "name": "tipoempresa_id", "value": $('#tipoempresa_id').val() } );
        },
        "oLanguage": {
            "sProcessing":     "Procesando...",
            "sLengthMenu":     "Mostrar _MENU_ registros",
            "sZeroRecords":    "No se encontraron resultados",
            "sEmptyTable":     "Ningún dato disponible en esta tabla",
            "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix":    "",
            "sSearch":         "Buscar:",
            "sUrl":            "",
            "sInfoThousands":  ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
	            "sFirst":    "Primero",
	            "sLast":     "Último",
	            "sNext":     "Siguiente",
	            "sPrevious": "Anterior"
			},
    		"fnInfoCallback": null,
		},
        "autoWidth": true,
        "sPaginationType": "full_numbers",
        "bSort": false,
        "aLengthMenu": [10, 50],
        "sEcho": 0,
        "iTotalRecords": 10,
        "iTotalDisplayRecords": 10,
        "sAjaxSource": "<?=base_url()?>index.php/asignacionsemiautomatica/getData",          
        "aoColumns": [  
               { "mDataProp": "COD_EMPRESA" },  
               { "mDataProp": "RAZON_SOCIAL" },  
               { "mDataProp": "NOMBREMUNICIPIO" },  
               { "mDataProp": "NOM_TIPO_EMP" },
               { "mDataProp": "CIIU" },
               
           ]

    } );

</script> 

</div>  
  <script type="text/javascript">
  //style selects
    var config = {
      '.chosen'           : {}
    }
    for (var selector in config) {
      $(selector).chosen(config[selector]);
    }

  </script>

