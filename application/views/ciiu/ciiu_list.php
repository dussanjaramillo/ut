<?php
if (isset($message)) {
  echo $message;
}
?>
<script type="text/javascript" language="javascript" charset="utf-8">
//generación de la tabla mediante json
  $(document).ready(function() {

    $('#tablaq').dataTable({
      "bProcessing": true,
      "bServerSide": true,
      "sAjaxSource": "<?php echo base_url(); ?>index.php/ciiu/dataTable",
      "sServerMethod": "POST",
      "aoColumns": [
        {"sClass": "center"}, /*id 0*/
        {"sClass": "item"},
        {"sClass": "item"},
        {"sClass": "item"},
        {"sClass": "center"},
        {"bSearchable": false, "bVisible": false},
        {"sClass": "center", "bSortable": false, "bSearchable": false, "sWidth": "1%"},
      ],
    });


  });

</script>

<h1>CIIU</h1>
<?php
echo anchor(base_url() . 'index.php/ciiu/add/', '<i class="icon-star"></i> Nueva', 'class="btn btn-large  btn-primary"');
?>
<br><br>
<table id="tablaq">
  <thead>
    <tr>
      <th>ID</th>
      <th>División</th>
      <th>Grupo</th>
      <th>Clase</th>
      <th>Descripción</th>
      <th>num</th>
      <th>Accíones</th>
    </tr>
  </thead>
  <tbody></tbody>     
</table>

