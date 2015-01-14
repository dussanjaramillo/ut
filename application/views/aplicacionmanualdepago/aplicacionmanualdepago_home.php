<?php
if (isset($message)) {
  echo $message;
}
?>
<div class="preload"></div><img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<div class="center-form-large">
  <div class="text-center"><h2><?php echo $title ?></h2><h3 class="titulo"><?php echo $stitle ?></h3></div>
  <fieldset>
    <?php $attributes = array('class' => 'form-inline', 'id' => 'myformajax');
    echo form_open("aplicacionmanualdepago/buscar", $attributes); ?>
    <div class="span4">
      <label for="identificacion">NÚMERO DE IDENTIFICACIÓN</label> <?php
      $data = array(
          'name' => 'identificacion',
          'id' => 'identificacion',
          'maxlength' => '10',
          'class' => 'validate[groupRequired[datos], custom[onlyNumber], minSize[3]] input-medium'
      );
      echo form_input($data);
      ?>
    </div>
    <div class="span4">
      <label for="razon">RAZÓN SOCIAL</label> <?php
      $data = array(
          'name' => 'razon',
          'id' => 'razon',
          'class' => 'validate[groupRequired[datos], custom[onlyLetterNumber]] input-large'
      );
      echo form_input($data)
      ?>
    </div><br><br>
    <div class="text-center">
      <?php echo anchor('aplicacionmanualdepago', '<i class="fa fa-minus-circle"></i> Cancelar', 'class="btn btn-warning btn-lg"'); ?>
      <?php
      $data = array(
          'name' => 'button',
          'id' => 'submit-button',
          'value' => 'seleccionar',
          'type' => 'submit',
          'content' => '<i class="fa fa-search"></i> Buscar',
          'class' => 'btn btn-success'
      );

      echo form_button($data);
      ?>
    </div>
<?php echo form_close(); ?>
  </fieldset>
  <div class="resultados">
<?php $attributes = array('class' => 'form-inline', 'id' => 'myform1');
echo form_open("aplicacionmanualdepago/aplicarpago", $attributes); ?>
    <table id="tablaq">
      <thead>
        <tr>
          <th>SELECCIONAR</th>
          <th>Número de identificación</th>
          <th>RAZÓN SOCIAL</th>
        </tr>
      </thead>
      <tbody id="tbody"></tbody>     
    </table><br>
    <div style="text-align:center">
      <?php
      $data = array(
          'name' => 'button',
          'id' => 'submit-button',
          'value' => 'seleccionar',
          'type' => 'submit',
          'content' => '<i class="fa fa-bank fa-lg"></i> Aplicar pago manual',
          'class' => 'btn btn-success aplicapago'
      );
      echo form_button($data);
      ?>
    </div>
<?php echo form_close(); ?>
  </div>
</div>

<script type="text/javascript" language="javascript" charset="utf-8">
//generación de la tabla mediante json
  $(document).ready(function() {
    $("#tablaq, .aplicapago").hide();
    jQuery(".preload, .load").hide();
  });
// Called once the server replies to the ajax form validation request
  function ajaxValidationCallback(status, form, json, options) {
    if (window.console)
      console.log(status);

    if (status === true) {
      $("#tablaq, .aplicapago").show();
			//$("#tablaq tbody").html("");
      //datatable(json.aaData, json.iTotalRecords, json.iTotalDisplayRecords);
			var oTable = $('#tablaq').dataTable({
				"bJQueryUI": true,
				"bProcessing": true,
				"bServerSide": false,
				"bProcess": true,
				"bFilter": false,
				"bSort": false,
				"bDestroy": false,
				"bRetrieve": true,
				"bPaginate":  true,
				"sPaginationType": "full_numbers",
				/*"aaData": json.aaData,*/
				"iDisplayStart" : 0,
				"iDisplayLength" : 10,
				"bLengthChange": true,
				"iTotalRecords": json.iTotalRecords,
				"iTotalDisplayRecords": json.iTotalDisplayRecords,
				"sEcho" : 1,
				"oLanguage": {
					"sProcessing":     "<i class='fa fa-spinner fa-spin'></i>",
					"sLengthMenu":     "Mostrar _MENU_ registros",
					"sZeroRecords":    "No se encontraron resultados",
					"sEmptyTable":     "Ningún dato disponible en esta tabla",
					"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
					"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
					"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
					"sInfoPostFix":    "",
					"sSearch":         'Buscar:',
					"sUrl":            "",
					"sInfoThousands":  ",",
					"sLoadingRecords": "Cargando...",
					"oPaginate": {
						"sFirst":    "Primero",
						"sLast":     "Último",
						"sNext":     "Siguiente",
						"sPrevious": "Anterior"
					},
					"oAria": {
						"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
						"sSortDescending": ": Activar para ordenar la columna de manera descendente"
					}
				},
				"aoColumns": [
					{"bSearchable": false, "sClass": "center"},
					{"bSearchable": false, "sClass": "center"},
					{"bSearchable": false, "sClass": "center"}
				],
			});
			oTable.fnClearTable();
			oTable.fnAddData(json.aaData);
			oTable.fnDraw();
      if(json.aaData.length == 0) {
        $(".aplicapago").attr("disabled", true);
      }
      jQuery('#myform1').validationEngine('attach');
    }
    else {
      alert('error de consulta de datos');
    }
    setTimeout(function() {
        jQuery(".preload, .load").hide();
    }, 2000);
  }
	
	function datatable(aaData, iTotalRecords, iTotalDisplayRecords) {
		
	}
</script>