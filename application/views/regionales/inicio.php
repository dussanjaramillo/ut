<div class="preload"></div>
<img class="load" src="<?php echo base_url('img/27.gif'); ?>" width="128" height="128" />
<div class="text-center">
	<h2><?php echo $title ?></h2>
  <h4 class="titulo"><?php echo $stitle ?></h4>
</div>
<?php
if (isset($message)) {
  echo $message;
}
echo anchor('regionales/agregar', '&nbsp;<i class="fa fa-plus-square"></i> Nueva regional', 'class="btn btn-info btn-lg"');
?>
<br><br>
<div class="control-group">
	<table id="tablaq">
    <thead>
      <tr>
      	<th>COD</th>
        <th>NOMBRE</th>
        <th>EMAIL</th>
        <th>DIRECTOR</th>
        <th>COORDINADOR</th>
        <th>COORDINADOR RELACIONES CORPORATIVAS</th>
        <th>SECRETARIO</th>
        <th>ACCIONES</th>
      </tr>
    </thead>
    <tbody>
    	<?php
				if(!empty($regionales)) :
					foreach($regionales as $regional) :
      ?>
    	<tr>
      	<td><?php echo $regional['COD_REGIONAL'] ?></td>
        <td><?php echo $regional['NOMBRE_REGIONAL'] ?></td>
        <td><?php echo $regional['EMAIL_REGIONAL'] ?></td>
        <td><?php echo $regional['DIRECTOR'] ?></td>
        <td><?php echo $regional['COORDINADOR'] ?></td>
        <td><?php echo $regional['COORDINADOR_RELACIONES'] ?></td>
        <td><?php echo $regional['SECRETARIO'] ?></td>
        <td><?php
					echo anchor('regionales/detalle/'.$regional['COD_REGIONAL'], '<i class="fa fa-eye"></i>', 'class="btn btn-info"');
					echo anchor('regionales/editar/'.$regional['COD_REGIONAL'], '<i class="fa fa-pencil"></i>', 'class="btn btn-info"');
        ?></td>
      </tr>
      <?php
					endforeach;
				endif;
			?>
    </tbody>     
  </table>
</div>
<script type="text/javascript" language="javascript" charset="utf-8">
  $(document).ready(function() {
    $(".preload, .load").hide();
		datatable();
	});
	
	function datatable() {
		$('#tablaq').dataTable({
			"bJQueryUI": true,
			"bServerSide" : false,
			"bProcessing" : true,
			"bSort" : true,
			"bPaginate" : true,
			"iDisplayStart" : 0,
			"iDisplayLength" : 10,
			"sPaginationType": "full_numbers",
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
			"iTotalRecords": <?php echo $iTotalRecords ?>,
			"iTotalDisplayRecords": <?php echo $iTotalRecords ?>,
			"aoColumns": [
				{"bSearchable": true, "sClass": "center"},
				{"bSearchable": true, "sClass": "center"},
				{"bSearchable": true, "sClass": "center"},
				{"bSearchable": true, "sClass": "center"},
				{"bSearchable": true, "sClass": "center"},
				{"bSearchable": true, "sClass": "center"},
				{"bSearchable": true, "sClass": "center"},
				{"bSearchable": false, "sClass": "center"}
			],
		});
	}
	
	function ajaxValidationCallback() {}
</script>