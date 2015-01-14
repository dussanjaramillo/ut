<?php if (isset($message)) echo $message; ?>
<h1>Íconos</h1>
<?php echo anchor(base_url().'index.php/iconos/add/','<i class="icon-star"></i> Nuevo','class="btn btn-large  btn-primary"'); ?>
<br>
<br>
<table id="tablaq">
	<thead>
    <tr>
      <th>Id</th>
      <th>Traducción</th>
      <th>Nombre real</th>
      <th>ícono</th>
      <th>Acciones</th>
    </tr>
  </thead>
  <tbody>
  </tbody>
</table>
<script type="text/javascript" language="javascript" charset="utf-8">
//generación de la tabla mediante json
$(document).ready(function() {
	$('#tablaq').dataTable( {
		"bProcessing": true,
		"bServerSide": true,
		"sAjaxSource": "<?php echo base_url(); ?>index.php/iconos/dataTable",
		"sServerMethod": "POST",
		"aoColumns": [
			{ "sClass": "center","bSortable": false,"bSearchable": false },
			{ "sClass": "item" }, 
			{ "sClass": "item" },  
			{ "bSearchable": false, "bVisible": false },
			{ "sClass": "center","bSortable": false,"bSearchable": false,"sWidth": "1%" },
		]
	});
});
</script>